<?php
defined( 'ABSPATH' ) || exit;
add_action( 'init', 'codex_companies' );
function codex_companies() {
    $singular = 'Empresa';
    $plural = 'Empresas';
    $labels = array(
        'name'               => $plural,
        'singular_name'      => $singular,
        'menu_name'          => $plural,
        'name_admin_bar'     => 'Adicionar nova ' . $singular,
        'add_new'            => 'Adicionar nova',
        'add_new_item'       => 'Adicionar nova ' . $singular,
        'new_item'           => 'Nova ' . $singular,
        'edit_item'          => 'Editar ' . $singular,
        'view_item'          => 'Ver ' . $singular,
        'all_items'          => 'Todas as ' . $plural,
        'search_items'       => 'Procurar ' . $singular,
        'parent_item_colon'  => $singular . 'parente:',
        'not_found'          => 'Nenhuma ' . $singular,
        'not_found_in_trash' => 'Nenhuma ' . $singular . 'no lixo',
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'rewrite'            => array( 'slug' => 'empresa' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-admin-multisite',
        'supports'           => array( 'title', 'custom-fields', 'editor', 'thumbnail' ),
        'publicly_queryable' => true,
        'query_var'          => true,
    );
    register_post_type( 'companies', $args );
}