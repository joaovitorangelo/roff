<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0' );

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles() {
	wp_enqueue_script( 'hello-elementor-child-script', get_stylesheet_directory_uri() . '/main.js', array('jquery'), '1.0' );
	wp_enqueue_script( 'jquery-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', array('jquery'), '1.14.16', false );
	wp_enqueue_script( 'pdf-js', 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js', array(), '3.4.120', true );
	wp_enqueue_style( 'hello-elementor-child-style', get_stylesheet_directory_uri() . '/style.css', ['hello-elementor-theme-style'], HELLO_ELEMENTOR_CHILD_VERSION);
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20 );

include('shortcode/_shortcode.php');
include('custom-post/_custom-post.php');
include('custom-tax/_custom-tax.php');
include('meta-box/_meta-box.php');
include('functions/_functions.php');
include('ajax/_ajax.php');

function loginFormLabels() {
  $your_content = ob_get_contents();
  $your_content = preg_replace( '/\<label for="user_login"\>(.*?)\<br/', 'CPF: ', $your_content );
  // $your_content = preg_replace( '/\<label for="user_pass"\>(.*?)\<br/', 'Passwiert:', $your_content );
  ob_get_clean();
  echo $your_content;
}
add_action( 'login_form', 'loginFormLabels' );

add_action( 'elementor_pro/forms/new_record', function( $record, $handler ) {
$form_name = $record->get_form_settings( 'form_name' );
$raw_fields = $record->get( 'fields' );
$fields = [];
foreach ( $raw_fields as $id => $field ) {
	$fields[$id] = array(
					'id'    => $id,
					'title' => $field['title'],
					'value' => $field['value'],
			);
}
switch ($form_name) {
	case 'simule_form':
		$return = simulate_form( $fields );
		$handler->data['customer_mail'] = $return;
		break;
	case 'document_form':
		$return = document_form( $fields );
		$handler->data['customer_mail'] = $return;
		break;
	default:
		# code...
		break;
}
}, 10, 2 );


