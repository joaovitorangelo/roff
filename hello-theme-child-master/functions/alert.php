<?php
defined( 'ABSPATH' ) || exit;

add_action('wp_head', 'print_function_query');
// Define a função print_function_query
function print_function_query() {
    // Adiciona a classe 'alert-box' ao html
    echo '<a class="alert" href="#"></a>';
}
add_action('wp_footer', 'print_function_query');

return;