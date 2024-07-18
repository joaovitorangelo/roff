<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function companies_add_custom_meta_box() {
  add_meta_box(
    'companies_cnpj_meta_box',
    'Número do CNPJ', 
    'companies_custom_meta_box_html', 
    'companies', 
    'normal', 
    'high' 
  );
}
add_action( 'add_meta_boxes', 'companies_add_custom_meta_box' );

function companies_custom_meta_box_html( $post ) {
  $value = get_post_meta( $post->ID, '_companies_cnpj', true );
  ?>
  <label for="companies_cnpj_field">CNPJ: </label>
  <input type="text" id="companies_cnpj_field" name="companies_cnpj_field" value="<?php echo esc_attr( $value ); ?>" />
  <?php
}

function companies_save_custom_meta_box_data( $post_id ) {
  $cnpj = sanitize_text_field( $_POST['companies_cnpj_field'] );
  update_post_meta( $post_id, '_companies_cnpj', $cnpj );
}
add_action( 'save_post', 'companies_save_custom_meta_box_data' );
