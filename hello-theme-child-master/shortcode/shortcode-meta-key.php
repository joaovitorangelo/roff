<?php
defined( 'ABSPATH' ) || exit;
// [metakey meta_key="companies" object="post" custom_post="post_title"]
// [metakey meta_key="document_icon" object="term" type="img" taxonomy="document_type"]
function shortcode_meta_key( $attr ) {
    global $post;
    $t = '';
    $meta_value = get_post_meta( $post->ID, $attr['meta_key'], true );
    if( isset( $attr['custom_post'] ) ) {
        $custom_post = get_post( $meta_value );
        if( $custom_post ) {
            foreach( $custom_post as $key => $value ) if( $key == $attr['custom_post'] ) $t = $value;
        }
    }elseif( isset( $attr['object'] ) && $attr['object'] == 'term' ) {
        $term = get_the_terms( $post, $attr['taxonomy'] );
        $upload_dir = wp_upload_dir();
        $user_dirname = $upload_dir['baseurl'] . '/2024/07/outro-icon.png';
        $i = '<img src="' . $user_dirname . '">';
        if( empty( $term ) ) return $i;
        $term_meta = get_term_meta( $term[0]->term_id, $attr['meta_key'], true );
        if( isset( $attr['type'] ) ) {
            switch ( $attr['type'] ) {
                case 'img':
                    $t = '<img src="' . $term_meta . '">';
                    break;
                
                default:
                    # code...
                    break;
            }
        } else {
            $t = $term_meta;
        }
    }
    return $t;
}
add_shortcode( 'metakey', 'shortcode_meta_key' );
