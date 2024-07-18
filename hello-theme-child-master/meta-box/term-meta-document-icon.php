<?php
/* ADD FIELD TAXONOMY */
function document_type_taxonomy_meta_fields( $term ) {
    $data = '';
    $term_meta = get_term_meta( $term->term_id );
    $meta_inputs = array(
        // array(
        //     'type'          => 'text',
        //     'title'         => 'Dimensões',
        //     'key'           => 'dimensions',
        //     'description'   => 'Valor mínimo a ser usado na verificação'
        // ),
        // array(
        //     'type'          => 'text',
        //     'title'         => 'Fabricante',
        //     'key'           => 'manufacturer',
        //     'description'   => 'URL da Marca do Fabricante'
        // ),
        array(
            'type'          => 'text',
            'title'         => 'Ícone para o tipo de documento',
            'key'           => 'document_icon',
            // 'description'   => 'URL da bandeira do país de origem'
        ),
        // array(
        //     'type'          => 'text',
        //     'title'         => 'Catálogo para download',
        //     'key'           => 'download-product-cat',
        //     'description'   => 'URL da pasta no Google Drive contendo os papéis desta coleção.'
        // ),
    );
    foreach( $meta_inputs as $input ) {
        $value = '';
        if( isset( $term_meta[$input['key']] ) && !empty( $term_meta[$input['key']][0] ) ) $value = $term_meta[$input['key']][0];
        $data .= '<tr class="form-field">
            <th scope="row" valign="top"><label for="term_meta[' . $input['key'] . ']">' . $input['title'] . '</label></th>
            <td>
                <input type="' . $input['type'] . '" name="term_meta[' . $input['key'] . ']" id="term_meta[' . $input['key'] . ']" value="' . $value . '">';
        if( isset( $input['description'] ) ) $data .= '<p class="description">' . $input['description'] . '</p>';
        $data .= '</td></tr>';
    }
    echo $data;
}
add_action( 'document_type_edit_form_fields', 'document_type_taxonomy_meta_fields', 10, 2 );
function save_taxonomy_custom_meta( $term_id ) {
    $term = get_term_by( 'id', $term_id, 'document_type' );
    if ( isset( $_POST['term_meta'] ) ) {
        foreach ( $_POST['term_meta'] as $meta_key => $meta_value ) {
            update_term_meta( $term_id, $meta_key, $meta_value );
        }
    }
}  
add_action( 'edited_document_type', 'save_taxonomy_custom_meta', 10, 2 );