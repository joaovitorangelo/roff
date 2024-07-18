<?php
defined( 'ABSPATH' ) || exit;
add_action( 'init', 'codex_document_type' );
function codex_document_type() {
    $singular = 'Tipo de Documento';
    $plural = 'Tipos de Documentos';
    $labels = array(
        'name'                       => $plural,
        'singular_name'              => $singular,
        'search_items'               => 'Procurar ' . $plural,
        'popular_items'              => $plural . ' Populares',
        'all_items'                  => 'Todos os ' . $plural,
        'parent_item'                => $singular . ' pai',
        'parent_item_colon'          => $singular . ' pai:',
        'edit_item'                  => 'Editar ' . $singular,
        'update_item'                => 'Atualizar ' . $singular,
        'add_new_item'               => 'Adicionar ' . $singular,
        'new_item_name'              => 'Novo ' . $singular,
        'separate_items_with_commas' => 'Separar ' . $plural . ' com vírgula',
        'add_or_remove_items'        => 'Adicionar ou remover vínculo',
        'choose_from_most_used'      => 'Escolher ' . $plural . ' mais usados',
        'not_found'                  => 'Nenhum ' . $singular . ' encontrado.',
        'menu_name'                  => $plural,
    );
    $args = array(
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        // 'meta_box_cb'       => true,
        'show_in_menu'      => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
        'capabilities'      => array ( 'manage_terms' => 'manage_options' ),
        'rewrite'           => array( 'slug' => 'tipo-de-documento')
    );
    register_taxonomy( 'document_type', 'documents', $args );
}
