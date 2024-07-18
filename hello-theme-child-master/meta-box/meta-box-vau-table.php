<?php
defined( 'ABSPATH' ) || exit;
add_action( 'add_meta_boxes', 'meta_box_vau' );
function meta_box_vau() {
    add_meta_box(
        'meta_box_vau',             // ID único do meta box
        'VAU',                      // Título do meta box
        'meta_inner_vau',           // Callback para a função que exibirá o conteúdo do meta box
        'vau',                      // Nome do custom post type onde o meta box será exibido
        'normal',                   // Contexto do meta box (normal, side ou advanced)
        'high'                      // Prioridade do meta box (high, core, default ou low)
    );
}

function meta_inner_vau( $post ) {
  // Estados
  $ufs = array(
    'AC'  =>  'Acre',
    'AL'  =>  'Alagoas',
    'AP'  =>  'Amapá',
    'AM'  =>  'Amazonas',
    'BA'  =>  'Bahia',
    'CE'  =>  'Ceará',
    'DF'  =>  'Distrito Federal',
    'ES'  =>  'Espírito Santo',
    'GO'  =>  'Goiás',
    'MA'  =>  'Maranhão',
    'MT'  =>  'Mato Grosso',
    'MS'  =>  'Mato Grosso do Sul',
    'MG'  =>  'Minas Gerais',
    'PA'  =>  'Pará',
    'PB'  =>  'Paraíba',
    'PR'  =>  'Paraná',
    'PE'  =>  'Pernambuco',
    'PI'  =>  'Piauí',
    'RJ'  =>  'Rio de Janeiro',
    'RN'  =>  'Rio Grande do Norte',
    'RS'  =>  'Rio Grande do Sul',
    'RO'  =>  'Rondônia',
    'RR'  =>  'Roraima',
    'SC'  =>  'Santa Catarina',
    'SP'  =>  'São Paulo',
    'SE'  =>  'Sergipe',
    'TO'  =>  'Tocantins'
  );
  // Tipo de imóvel
  $tipo_de_imoveis = array(
    'CP'  => 'Casa Popular',
    'CSL' => 'Comercial salas e lojas',
    'CHP' => 'Conjunto habitacional popular',
    'EG'  => 'Edifício de garagem',
    'GI'  => 'Galpão industrial',
    'RM'  => 'Residencial multifamiliar',
    'RU'  => 'Residencial unifamiliar',
  );

  echo '<table class="form-table">';
  echo '<thead><tr><th>UF</th>';
  
  // Cabeçalhos das colunas
  foreach ($tipo_de_imoveis as $tipo_imovel_key => $tipo_imovel_value) {
    echo '<th>' . $tipo_imovel_value . '</th>';
  }
  
  echo '</tr></thead><tbody>';
  
  $vau = get_post_meta( $post->ID, 'vau', true );

  // Linhas da tabela
  foreach ( $ufs as $uf_key => $uf_value ) {
    echo '<tr>';
    echo '<td>' . $uf_key . '</td>';
      
    // Células para cada tipo de imóvel
    foreach ($tipo_de_imoveis as $tipo_key => $tipo_value) {
        $meta_value = isset( $vau[$uf_key][$tipo_key] ) ? $vau[$uf_key][$tipo_key] : ''; 
        echo '<td><input class="input-vau" type="text" name="vau[' . $uf_key . '][' . $tipo_key . ']" value="' . $meta_value . '"></td>';
    }
      
    echo '</tr>';
  }
  
  echo '</tbody></table>';

  ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
      jQuery(document).ready(function($) {
        // Mask
        $('.input-vau').mask('#.##0,00', {reverse: true});
      });
    </script>

    <style>
      .form-table {
        thead tr {
          th {
            width: 0;
            vertical-align: middle;
            text-align: center;
            padding: 5px 0;
            /* border-collapse: collapse; */
            color: #3777C0;
            background-color: #D1E5EF;
            border: 1px solid #F0F0F1;
          }
        }
        tbody, tr {
          & > tr:nth-of-type(odd) { background-color: #E3E3E3; } 
          & > tr:nth-of-type(even) { background-color: #F1F1F1; } 
          td {
            padding: 0;
            text-align: center;
            border-collapse:collapse;
            border: 1px solid #F0F0F1;
            .input-vau {
              background-color: transparent;
              border: none;
              text-align: center;
              width: 100%;
              border-radius: 0;
              &:focus { outline: 1px solid #ccc }
            }
          }
        }
        tbody tr:hover { background-color: #D1E5EF; }
      }
    </style>
  <?php
}

add_action( 'save_post_vau', 'save_meta_box_vau' );
function save_meta_box_vau( $post_id ) {
  // Salva os dados
  if ( isset( $_POST['vau'] ) ) {
    $data = $_POST['vau'];
    update_post_meta( $post_id, 'vau', $data );
  }
}

// $fields = array(
//   'sc'  => array(
//     'cs'  => '3250',
//     'gi'  => '3000',
//   ),
//   'rs'  => array(
//     'cs'  => '3250',
//     'gi'  => '3000',
//   )
// );
// update_post_meta( $post_id, 'fields', $fields );
// get_post_meta($post_id, 'fields', true);
// $ufs = array();
// <input name="fields[sc][gi]" value="">
// <input name="fields[sc][cs]">