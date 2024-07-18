<?php
defined( 'ABSPATH' ) || exit;
add_action( 'init', 'codex_documents' );
function codex_documents() {
    $singular = 'Documento';
    $plural = 'Documentos';
    $labels = array(
        'name'               => $plural,
        'singular_name'      => $singular,
        'menu_name'          => $plural,
        'name_admin_bar'     => 'Adicionar novo ' . $singular,
        'add_new'            => 'Adicionar novo',
        'add_new_item'       => 'Adicionar novo ' . $singular,
        'new_item'           => 'Novo ' . $singular,
        'edit_item'          => 'Editar ' . $singular,
        'view_item'          => 'Ver ' . $singular,
        'all_items'          => 'Todos os ' . $plural,
        'search_items'       => 'Procurar ' . $singular,
        'parent_item_colon'  => $singular . 'parente:',
        'not_found'          => 'Nenhum ' . $singular,
        'not_found_in_trash' => 'Nenhum ' . $singular . 'no lixo',
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'rewrite'            => array( 'slug' => 'documento' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-media-default',
        'supports'           => array( 'title', 'custom-fields', 'editor', 'thumbnail', 'author' ),
        'publicly_queryable' => true,
        'query_var'          => true,
    );
    register_post_type( 'documents', $args );
}