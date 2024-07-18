<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// Template name: Teste
?>
 <!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Converter PDF em Texto</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js" integrity="sha512-ml/QKfG3+Yes6TwOzQb7aCNtJF4PUyha6R3w8pSTo/VJSywl7ZreYvvtUso7fKevpsI+pYVVwnu82YO0q3V6eg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <style>
      h1 { width: 100%; text-align: center; }
      .pdfwork, .afterupload {
          display: flex;
          align-items: center;
          justify-content: center;
          flex-direction: column;
          width: 100%;
      }
      .pdfwork * { margin-top: 5px; }
      .afterupload { display: none; }
      .another { display: none; }
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
<h1>Converter PDF em Texto</h1>
<div class="pdfwork">
  <button class="another" onclick="location.reload()">Extrair Outro PDF</button>
  <!-- <span>Senha :</span> -->
  <!-- <input type="password" class="pwd" placeholder='opcional'> -->
  <button class="upload">Enviar</button>
  <div class="afterupload">
    <span>Selecionar Página</span>
    <select class="selectpage" onchange="afterProcess()"></select>
    <a href="" class="download" download>Baixar Texto do PDF</a>
    <textarea class="pdftext"></textarea>
    <div class="result"></div>
  </div>
</div>
<script>
pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js";
// let pwd = document.querySelector(".pwd");
let upload = document.querySelector(".upload");
let afterupload = document.querySelector(".afterupload");
let select = document.querySelector("select");
let download = document.querySelector(".download");
let pdftext = document.querySelector(".pdftext");
let divResult = document.querySelector(".result");

let pdfUrl = "http://local.tokdigital.cc/roff/wp-content/uploads/2024/07/240701154556BDc7v.pdf";
// upload.addEventListener('click', () => {
//     if (pwd.value == "") {
//         extractText(pdfUrl, false);
//     } else {
//         extractText(pdfUrl, true);
//     }
// });
upload.addEventListener('click', () => {
  extractText(pdfUrl, true);
});
let alltext = [];
async function extractText(url, pass) {
  try {
    let pdf;
    // if (pass) {
    //     pdf = await pdfjsLib.getDocument({ url: url, password: pwd.value }).promise;
    // } else {
    //     pdf = await pdfjsLib.getDocument(url).promise;
    // }
    pdf = await pdfjsLib.getDocument(url).promise;
    let pages = pdf.numPages;
    for (let i = 1; i <= pages; i++) {
      let page = await pdf.getPage(i);
      let txt = await page.getTextContent();
      let text = txt.items.map((s) => s.str).join(" ");
      alltext.push(text);
    }
    alltext.map((e, i) => {
      select.innerHTML += `<option value="${i+1}">${i+1}</option>`;
    });
    afterProcess();
  } catch (err) {
    alert(err.message);
  }
}
function afterProcess() {
  pdftext.value = alltext[select.value - 1];
  download.href = "data:text/plain;charset=utf-8," + encodeURIComponent(alltext[select.value - 1]);
  afterupload.style.display = "flex";
  document.querySelector(".another").style.display = "unset";
  if (pdftext.value.includes("CNPJ") !== null && pdftext.value.includes("CPF") !== null) {
    console.log("A palavra 'CNPJ' e 'CPF' foi encontrada no texto.");
    // Armazenar CPF e CNPJ em uma variavel
    // Expressões regulares para encontrar CPF e CNPJ
    let regex_cpf = /CPF:\s*(\d{3}\.\d{3}\.\d{3}-\d{2})/;
    let regex_cnpj = /CNPJ:\s*(\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2})/;
    // Procurar CPF
    let matches_cpf = pdftext.value.match(regex_cpf);
    let matches_cnpj = pdftext.value.match(regex_cnpj);
    if (matches_cpf !== null && matches_cnpj !== null) {
      let cpf = matches_cpf[1];
      let cnpj = matches_cnpj[1];
      // console.log("CPF encontrado:", cpf);
      // console.log("CNPJ encontrado:", cnpj);
      divResult.innerHTML = `CPF encontrado: ${cpf}<br>CNPJ encontrado: ${cnpj}`;
      jQuery(document).ready(function($) {
      try {
        $.ajax({
          url: 'http://local.tokdigital.cc/roff/wp-admin/admin-ajax.php',
          type: 'POST',
          dataType: 'json',
          data: {
            action: 'user_data_ajax',
            cpf: cpf,
            cnpj: cnpj,
            pdf: pdfUrl
          },
          success: function(response) {
            console.log(response);
          },
        });
      } catch (error) {
        throw error;
      }
      });
    }
  }
}
</script>
 </body>
 </html>
<?php





