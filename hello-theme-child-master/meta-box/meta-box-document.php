<?php
defined( 'ABSPATH' ) || exit;
add_action( 'add_meta_boxes', 'meta_box_document' );
function meta_box_document() {
    add_meta_box(
        'meta_box_document',        // ID único do meta box
        'Documento',                // Título do meta box
        'meta_inner_document',      // Callback para a função que exibirá o conteúdo do meta box
        'documents',                // Nome do custom post type onde o meta box será exibido
        'normal',                   // Contexto do meta box (normal, side ou advanced)
        'high'                      // Prioridade do meta box (high, core, default ou low)
    );
}

function meta_inner_document( $post ) {
    // Busca tipo de documentos
    $terms = get_terms( array(
        'taxonomy'   => 'document_type',
        'hide_empty' => false,
    ) );

    $document_type_options = array();
    foreach ( $terms as $term ) {
        $document_type_options[ $term->term_id ] = $term->name;
    }
    
    // Busca empresas
    $companies_args = array(
        'post_type'      => 'companies',
        'posts_per_page' => -1,
    );
    $companies_query = get_posts( $companies_args );
    $companies_options = array();
    foreach ( $companies_query as $company ) {
        $company_name = $company->post_title;
        $companies_options[ $company->ID ] = $company_name;
    }

    // Obtém os valores atuais dos meta dados
    $document_type = get_the_terms( $post, 'document_type' );
    if ( isset( $document_type ) && !empty( $document_type ) ) {
        $document_type = $document_type[0]->term_id;
    } 
    $company = get_post_meta( $post->ID, 'companies', true );

    $pdf = get_post_meta( $post->ID, 'pdf', true );

    $meta_inputs = array(
        // Tipo de Documento
        array(
            'type'          => 'select',
            'title'         => 'Tipo de Documento',
            'key'           => 'document_type',
            'options'       => $document_type_options,
            'value'         => $document_type,
        ),
        // Empresa
        array(
            'type'          => 'select',
            'title'         => 'Empresa',
            'key'           => 'companies',
            'options'       => $companies_options,
            'value'         => $company,
        ),
        // Arquivo PDF
        array(
            'type'          => 'text',
            'title'         => 'PDF',
            'key'           => 'pdf',
            'value'         => $pdf,
        ),
    );

    echo '<div class="meta-box">';
    foreach ( $meta_inputs as $input ) {
        $key = $input['key'];
        $value = $input['value'];
        echo '<p>';
        echo '<label for="' . esc_attr( $key ) . '">' . esc_html( $input['title'] ) . '</label><br>';
        if ( $input['type'] === 'select' ) {
            echo '<select name="post_meta[' . esc_attr( $key ) . ']" id="' . esc_attr( $key ) . '">';
            echo '<option value="">Selecionar...</option>';
            foreach ( $input['options'] as $option_value => $option_label ) {
                echo '<option value="' . esc_attr( $option_value ) . '" ' . selected( $value, $option_value, false ) . '>' . esc_html( $option_label ) . '</option>';
            }
            echo '</select>';
        } else {
            echo '<input name="post_meta[' . esc_attr( $key ) . ']" id="' . esc_attr( $key ) . '" value="' . $pdf . '">';
        }
        echo '</p>';
    }
    echo '</div>';
}

add_action( 'save_post_documents', 'save_meta_box_data' );
function save_meta_box_data( $post_id ) {
    if ( isset( $_POST['post_meta'] ) && is_array( $_POST['post_meta'] ) ) {
        foreach ( $_POST['post_meta'] as $key => $value ) {
            if( $key == 'document_type' ) {
                wp_set_post_terms( $post_id, [(int) $value], $key );
            } else {
                update_post_meta( $post_id, $key, $value );
            }
        }
    }
}
