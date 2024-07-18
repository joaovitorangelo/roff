<?php
add_action('elementor/query/view_documents', 'orderby');
function orderby( $query ) {
  if (is_a($query, 'WP_Query')) {
    if (isset($_GET['orderby'])) {
      $orderby = sanitize_text_field($_GET['orderby']);
      if ($orderby == 'a-z') {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
      } elseif ( $orderby == 'z-a' ) {
        $query->set('orderby', 'title');
        $query->set('order', 'DESC');
      } elseif ($orderby == 'recent') {
        $query->set('orderby', 'date');
        $query->set('order', 'DESC');
      } elseif ($orderby == 'old') {
        $query->set('orderby', 'date');
        $query->set('order', 'ASC');
      } elseif ($orderby == 'documents') {
        $query->set('post_type', 'documents');
        $query->set('order', 'ASC');
      } elseif ($orderby == 'companies') {
        $query->set('post_type', 'companies');
        $query->set('order', 'ASC');
      }
    }
  }
  // Criação do select
  $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : '';
  $s = '<form method="get" id="form_documents"><label for="orderby">Ordenado por:</label><select name="orderby" onchange="this.form.submit()">';
  $s .= '<option value="">Selecionar...</option>';
  $s .= '<option value="a-z"' . selected($orderby, 'a-z', false) . '>Título (A-Z)</option>';
  $s .= '<option value="z-a"' . selected($orderby, 'z-a', false) . '>Título (Z-A)</option>';
  $s .= '<option value="recent"' . selected($orderby, 'recent', false) . '>Data (mais recente)</option>';
  $s .= '<option value="old"' . selected($orderby, 'old', false) . '>Data (mais antiga)</option>';
  $s .= '<option value="documents"' . selected($orderby, 'documents', false) . '>Documentos</option>';
  $s .= '<option value="companies"' . selected($orderby, 'companies', false) . '>Empresas</option>';
  $s .= '</select></form>';
  return $s;
}
add_shortcode('ordenar', 'orderby');
