<?php
defined( 'ABSPATH' ) || exit;
function shortcode_show_tags() {
    $terms = get_terms( array(
        'taxonomy' => 'category',
    ) );

    if ( isset( $terms ) && !empty( $terms ) ) {
      $t = '<div class="tags-container"><ul>';
      foreach ( $terms as $term ) {
      $t .= '<li><a href="' . get_term_link( $term ) . '">' . $term->name . '</a></li>';
    }
    $t .= '</ul></div>';
    } else {
      $t = '<p>Nenhuma tag encontrada.</p>';
    }

    return $t;
}
add_shortcode( 'tags', 'shortcode_show_tags' );
