jQuery(document).ready(function($) {
// # Header - <li>Acesso</li>
if ($('body').hasClass('logged-in')) {
  $('.access-text').text('Sair');
  $('.header-access-btn i').toggleClass('fa-unlock fa-lock');
} else {
}
// # Ajustes 
// $('#form-field-totalArea, #form-field-poolDiscovery').after('<span class="m">m²</span>');
// Linha reta ao lado da páginação - Blog Archive
$('.smaller-post-card-loop nav.elementor-pagination').append('<span class="line-on-pagination">line</span>');
// Texto ao lado da páginação - Document Archive
if ($(window).width() <= 768) {
  $('.documents-pagination .elementor-pagination').before($('.text-document-pagination'));
} else {
  $('.documents-pagination .elementor-pagination .page-numbers.prev').before($('.text-document-pagination'));
}
$(window).resize(function() {
  if ($(window).width() <= 768) {
    $('.documents-pagination .elementor-pagination').before($('.text-document-pagination'));
  } else {
    $('.documents-pagination .elementor-pagination .page-numbers.prev').before($('.text-document-pagination'));
  }
});
// Formatando input para aceitar type float
$('#form-field-totalArea').keyup(function() {
  $(this).val(this.value.replace(/[^\d.,]/g, ''));
});
$('#form-field-totalArea').mask('#.##0,00', {reverse: true});
// Posicionando select de ordenação após o titulo de todos os documentos
$('.title-all-documents').after($('#form_documents'));
// # Accordion - Mobile
var $imageToClone = $('.line-info').first(); 
$('.elementor-accordion .elementor-accordion-item').slice(1).each(function() {
  let $clonedImage = $imageToClone.clone(); 
  $(this).prepend($clonedImage); 
});
// Alert 
function AlertSuccess() {
  $('.alert').trigger('click');
  $('.alert-container').addClass('success'); 
  $('.alert-container.success .text-alert .elementor-icon-list-text').text('Documento recebido!'); 
}
function AlertError() {
  $('.alert').trigger('click');
  $('.alert-container').addClass('error'); 
  $('.alert-container.error .text-alert .elementor-icon-list-text').text('Erro ao receber...'); 
}
$('#form-field-document_archive').change(function() {
  let files = $(this).prop('files');
  let filesDiv = $('<div class="filesDiv"><ul class="files"><li class="title">Arquivos:</li></ul></div>');
  let filesList = filesDiv.find('ul.files');
  $(files).each(function(index, file) {
      filesList.append('<li>' + file.name + '</li>');
  });
  $('#document_form').after(filesDiv);
});
// # Form response
// Simule o INSS da sua obra
$('#simule_form').on('submit_success', function( event, response ) {
  if ( response.data.customer_mail ) {
    let data = response.data.customer_mail;
    console.log( data );
  } else {
    console.log('erro');
  }
});
$('#document_form').on('submit_success', function(event, response) {
  if (response.data.customer_mail) {
    let document_type = response.data.customer_mail.document_type.value;
    let document_archive = response.data.customer_mail.document_archive.value.split(',');

    pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js";

    if (Array.isArray(document_archive)) {
      // Promise.all para aguardar todas as extrações de texto
      Promise.all(document_archive.map(url => extractText(url)))
        .then(alltexts => {
          alltexts.forEach((text, index) => {
            afterProcess(text, document_type, document_archive[index]);
          });
        })
        .catch(err => {
          alert(err.message);
        });
    } else {
      extractText(document_archive).then(text => {
        afterProcess(text, document_type, document_archive);
      }).catch(err => {
        alert(err.message);
      });
    }
  } else {
    console.log('erro');
  }
});

async function extractText(url) {
  let alltext = [];
  try {
    let pdf = await pdfjsLib.getDocument(url).promise;
    let pages = pdf.numPages;
    for (let i = 1; i <= pages; i++) {
      let page = await pdf.getPage(i);
      let txt = await page.getTextContent();
      let text = txt.items.map((s) => s.str).join(" ");
      alltext.push(text);
    }
  } catch (err) {
    throw err;
  }
  return alltext.join(' ');
}

function afterProcess(text, document_type, document_archive) {
  let pdftext = text.replace(/\s/g, '');
  if (pdftext.includes("CNPJ") && pdftext.includes("CPF")) {
    console.log("A palavra 'CNPJ' e 'CPF' foi encontrada no texto.");
    let regex_cpf = /CPF:\s*(\d{3}\.\d{3}\.\d{3}-\d{2})/;
    let regex_cnpj = /CNPJ:\s*(\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2})/;
    let matches_cpf = pdftext.match(regex_cpf);
    let matches_cnpj = pdftext.match(regex_cnpj);
    if (matches_cpf !== null && matches_cnpj !== null) {
      let cpf = matches_cpf[1];
      let cnpj = matches_cnpj[1];
      console.log(`CPF encontrado: ${cpf} CNPJ encontrado: ${cnpj}`);
      try {
        $.ajax({
          url: ElementorProFrontendConfig['ajaxurl'],
          type: 'POST',
          dataType: 'json',
          data: {
            action: 'user_data_ajax',
            cpf: cpf,
            cnpj: cnpj,
            type: document_type,
            pdf: document_archive
          },
          success: function(response) {
            console.log(response);
            AlertSuccess();
          },
        });
      } catch (error) {
        AlertError();
        throw error;
      }
    }
  }
}
});


