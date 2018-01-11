<?php
/**
 * acf-convert-to-json
 *
 * @package acf-convert-to-json
 * @author  Jesper V Nielsen
 *
 * Plugin Name:       Convert acf php to json
 * Plugin URI:        https://github.com/jespervnielsen
 * Description:       Converts acf php to json
 * Version:           1.0
 * Author:            Peytz & Co (Jesper V Nielsen)
 * Author URI:        https://github.com/jespervnielsen
 * Text Domain:       acf-convert-to-json
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/jespervnielsen
 */

// Do not access this file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AcfConvertToJson {

	public function __construct() {
		// sd(esc_url( admin_url('admin-post.php') ));
		// https://uniavisen.test/wp/wp-admin/admin-post.php?action=convert_acf_to_json
		add_action( 'admin_post_convert_acf_to_json', [ $this, 'convert_acf_to_json' ] );
	}

	public function convert_acf_to_json() {
		//https://awesomeacf.com/snippets/convert-an-acf-field-group-from-php-to-json/

		// get all the local field groups
	$field_groups = acf_get_local_field_groups();

		// loop over each of the gield gruops
		foreach( $field_groups as $field_group ) {

			// get the field group key
			$key = $field_group['key'];

			// if this field group has fields
			if( acf_have_local_fields( $key ) ) {
				// append the fields
				$field_group['fields'] = acf_get_local_fields( $key );

			}

			// save the acf-json file to the acf-json dir by default
			acf_write_json_field_group( $field_group );

			if ( wp_get_referer() ) {
				wp_safe_redirect( wp_get_referer() );
			}
			else {
				wp_safe_redirect( get_home_url() );
			}
		}
	}

}

$uniavisen_acf_convert_to_json = new AcfConvertToJson();
