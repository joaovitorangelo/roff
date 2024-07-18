<?php
defined( 'ABSPATH' ) || exit;

// Função para modificar a consulta do Elementor
add_action( 'elementor/query/view_documents', 'query_filter_documents' );
function query_filter_documents( $query ) {
    global $custom_query;
    $custom_query = $query;
}

// Função para exibir a contagem de resultados
function count_result_loop() {
    global $custom_query;
    if ( isset( $custom_query->post_count ) && !empty( $custom_query->post_count ) ) {
        $c = '<span class="text-document-pagination">Você está visualizando ' . $custom_query->post_count . ' de ' . $custom_query->found_posts . ' documentos. Utilize os números ao lado para ver documentos mais antigos.</span>';
        return $c;
    }
    return '';
}
add_shortcode( 'exibir-contagem', 'count_result_loop' );
