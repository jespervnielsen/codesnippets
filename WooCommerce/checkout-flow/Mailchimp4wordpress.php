<?php
namespace ModifyMailchimp4WooCommerce;

class Mailchimp4Wordpress {

	/**
	 * Constructor
	 */
	public function __construct() {

		add_filter( 'mc4wp_integration_woocommerce_checkbox_attributes', [ __CLASS__, 'add_woocommerce_classes_to_checkbox' ], 10, 2 );
		add_filter( 'mc4wp_integration_woocommerce_options', [ __CLASS__, 'modify_options' ], 9000000 );

		add_action( 'mc4wp_integration_woocommerce_before_checkbox_wrapper', [ __CLASS__, 'wrap_input_start' ] );
		add_action( 'mc4wp_integration_woocommerce_after_checkbox_wrapper', [ __CLASS__, 'wrap_input_end' ] );
	}

	public static function wrap_input_start( $mc4wp_woocommerce ) {
		echo '<p class="form-row mc4wp-checkbox-woocommerce-wrap">';
	}

	public static function wrap_input_end( $mc4wp_woocommerce ) {
		echo '</p>';
	}

	public static function add_woocommerce_classes_to_checkbox( $attributes, $integration ) {
		
		$current_classes = '';
		
		if ( isset( $attributes['class'] ) ) {
			$current_classes = $attributes['class'];
		}

		$attributes['class'] = 'input-checkbox ' . $current_classes;

		return $attributes;
	}

	public static function modify_options( $options ) {

		$options['wrap_p'] = false;

		return $options;
	}

}
