<?php
defined( 'ABSPATH' ) || exit;
add_action('wp_ajax_user_data_ajax', 'user_data');
add_action('wp_ajax_nopriv_user_data_ajax', 'user_data');
function user_data() {
  // Recebe dados do PDF
  $cpf = $_POST['cpf'];
  $cpf = str_replace('.', '', str_replace('-', '', $cpf));
  $cnpj = $_POST['cnpj'];
  $cnpj = str_replace('.', '', str_replace('/', '', str_replace('-', '', $cnpj)));
  
  $type = (int) $_POST['type'];
  $pdf = $_POST['pdf'];

  // Busca usuários
  $users = get_users();
  $found_user = null;
  foreach ($users as $user) {
    $cpf_user_login = $user->user_login;
    $cpf_user_login = str_replace('.', '', str_replace('-', '', $cpf_user_login));
    if ($cpf_user_login === $cpf) {
      $found_user = $user;
      break;
    }
  }

  // Busca empresas
  $companies_args = array(
    'post_type'      => 'companies',
    'posts_per_page' => -1,
  );
  $companies_query = get_posts( $companies_args );
  $found_company = null;
  if ( isset( $companies_query ) ) {
    foreach ( $companies_query as $post ) {
      $company_cnpj = get_post_meta( $post->ID, '_companies_cnpj', true );
      $company_cnpj = str_replace('.', '', str_replace('/', '', str_replace('-', '', $company_cnpj)));
      if ( $company_cnpj === $cnpj ) {
        $found_company = $post;
        break;
      } 
    }
  }

  // Insere POST
  $postarr = array(
    'post_title'    => 'POST de ' . $found_user->display_name,
    // 'post_content'  => $post_content,
    'post_status'   => 'publish',
    'post_author'   => $found_user->ID,
    'post_type'     => 'documents',
  );
  $post = wp_insert_post( $postarr );

  // Define tipo de documento
  wp_set_object_terms( $post, $type, 'document_type' );
  // Define empresa 
  update_post_meta( $post, 'companies', $found_company->ID );
  // Define PDF
  update_post_meta( $post, 'pdf', $pdf );

  // Resposta
  if ($found_user !== null) {
    $response = array(
      'status'  =>  'success',
      'ARCHIVE' =>  $pdf,
      'TYPE'    =>  $type,
      'PDF' =>  array(
        'CPF'   =>  $cpf,
        'CNPJ'  =>  $cnpj,
      ),  
      'USER'  =>  array(
        'ID'            =>  $found_user->ID,
        'user_login'    =>  $cpf_user_login,
        'user_email'    =>  $found_user->user_email,
        'display_name'  =>  $found_user->display_name
      ),
      'COMPANY' => array(
        'company_name'  =>  $found_company->post_title,
        'company_id'    =>  $found_company->ID,
        'company_cnpj'  =>  $company_cnpj
      ),
    );
  }
  wp_send_json($response);
  ?>
  <script>
  </script>
  <?php
}
