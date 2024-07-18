<?php
defined( 'ABSPATH' ) || exit;
add_action( 'admin_menu', 'settings_vau_menu' );
function settings_vau_menu() {
    add_submenu_page( 'edit.php?post_type=vau', 'Configurações', 'Configurações', 'manage_options', 'settings-vau', 'settings_vau' );
}
function settings_vau() {
  if( isset( $_POST['ped'] ) ) update_option( 'ped', $_POST['ped'], false );
  $ped = get_option( 'ped', array() );
  // $ped = array();
  // echo '<pre>teste';
  // print_r( $ped);
  // echo '</pre>'
    ?>
    <div class="wrap">
        <h2>Percentuais de Equivalência por Destinação</h2>
        <form method="POST">
            <?php 
              $tipo_de_imoveis = array(
                'CP'  => 'Casa Popular',
                'CSL' => 'Comercial salas e lojas',
                'CHP' => 'Conjunto habitacional popular',
                'EG'  => 'Edifício de garagem',
                'GI'  => 'Galpão industrial',
                'RM'  => 'Residencial multifamiliar',
                'RU'  => 'Residencial unifamiliar',
                'FS'  => 'Fator Social'
              );
              $tb = '';
              foreach( $tipo_de_imoveis as $key => $value ) {
                $tb .= '<table><thead><tr><th colspan="4">' . $value . '</th></tr>';
                $tb .= '<tr><th>Mínimo m²</th><th>Máximo m²</th><th>Fator</th><th>Excluir</th></tr></thead>';
                $tb .= '<tbody ped="' . $key . '">';
                $last = 0;
              if( isset( $ped[$key] ) && !empty( $ped[$key] ) ) {
                $last = count( $ped[$key] );
                $l = 0;
                foreach( $ped[$key] as $line_ped ) {
                  $tb .= '<tr class="line-ped ' . $key . '" line="' . $l . '"><td><input type="text" name="ped[' . $key . '][' . $l . '][min]" value="' . $line_ped['min'] . '" class="number"></td>
                  <td><input type="text" name="ped[' . $key . '][' . $l . '][max]" value="' . $line_ped['max'] . '" class="number"></td>
                  <td><input type="text" name="ped[' . $key . '][' . $l . '][fathor]" value="' . $line_ped['fathor'] . '" class="percent"></td>
                  <td><button type="button" class="remove-ped" line="' . $l . '" ped="' . $key . '"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg></button></td></tr>';
                  $l++;
                }
              } else {
                $tb .= '<tr class="line-ped ' . $key . '" line="0"><td><input type="text" name="ped[' . $key . '][0][min]" value="" class="number"></td>
                <td><input type="text" name="ped[' . $key . '][0][max]" value="" class="number"></td>
                <td><input type="text" name="ped[' . $key . '][0][fathor]" value="" class="percent"></td>
                <td><button type="button" class="remove-ped" line="0" ped="' . $key . '"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg></button></td></tr>';
              }
              $tb .= '</tbody><tfoot><tr><td colspan="4"><button type="button" class="add-ped button button-primary" ped="' . $key . '" last="' . $last . '">Adiciona</button></td></tr></tfoot></table>';
            }
            echo $tb;
            submit_button(); ?>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        jQuery(document).ready(function($){
          $('tbody').on('click', '.remove-ped', function(){
            $('tr.' + $(this).attr('ped') + '[line="' + $(this).attr('line') + '"]').remove();
          });
          $('.add-ped').on('click', function(){
            let ped = $(this).attr('ped');
            let l = parseInt( $(this).attr('last') );
            let tr = '<tr class="line-ped ' + ped + '" line="' + l + '"><td><input type="text" name="ped[' + ped + '][' + l + '][min]" value="" class="number"></td><td><input type="text" name="ped[' + ped + '][' + l + '][max]" value="" class="number"></td><td><input type="text" name="ped[' + ped + '][' + l + '][fathor]" value="" class="percent"></td><td><button class="remove-ped" type="button" line="' + l + '" ped="' + ped + '"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg></button></td></tr>';
            $('tbody[ped="' + ped + '"]').append( tr );
            l = l + 1;
            $(this).attr('last', l);
          });
          // Mask
          $('.number').mask('#.##0,00', {reverse: true});
        });
    </script>
    <style>
      .wrap {
        form {
          table {
            padding: 50px 0 50px 0;
            thead  {
              & tr:nth-child(1) th { 
                text-align: start; 
                text-transform: uppercase;
                text-decoration: underline;
              }
              & tr:nth-child(2) { 
                background-color: #D1E5EF; 
                th { color: #3777C0; }
              }
              th { padding: 5px 5px; }
            }
            tbody tr {
              background-color: #E3E3E3;
              & td:last-child { text-align: center; }
              & td {
                input {
                  text-align: center;
                  border: none;
                  border-radius: 0;
                  background-color: transparent;
                  &:focus { outline: 1px solid #ccc; }
                }
                button {
                  border: none;
                  background-color: transparent;
                  cursor: pointer;
                  transition: .2s all;
                  &:hover { transform: scale(1.1); }
                }
              } 
            }
          }
        }
      }
    </style>
<?php 
}