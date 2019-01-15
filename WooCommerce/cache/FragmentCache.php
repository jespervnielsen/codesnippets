<?php

namespace Woo_adjustments\Frontend;

use Woo_adjustments\Plugin;

class FragmentCache {

	public function __construct() {
		$this->init();
	}

	/**
	 * Run core bootstrap hooks.
	 */
	public function init() {

		// add_action('woocommerce_before_template_part', [__CLASS__, 'add_lazyload_to_woo_images'], 10, 4 );
		// add_action('woocommerce_after_template_part', [__CLASS__, 'add_lazyload_to_woo_images'], 10, 4 );

		add_filter('wc_get_template_part', [__CLASS__, 'wc_get_template_part'], 99999, 3 );

		add_filter('wc_get_template', [__CLASS__, 'wc_get_template'], 99999, 5 );
	}

	public static function get_key_per_fragment( $fragment ) {
		return( wp_hash( $fragment ) );
	}

	public static function get_key_pr_url( $fragment ) {
		return( self::get_key_per_fragment( $fragment . $_SERVER['REQUEST_URI'] ) );
	}

	public static function get_key_pr_user( $fragment ) {
		return self::get_key_per_fragment( $fragment . get_current_user_id() );
	}

	public static function get_key_pr_page_id( $fragment ) {
		// @note get_the_ID isn't optimal if get_template_part is called outside of the loop
		return self::get_key_per_fragment( $fragment . get_the_ID() );
	}

	public static function wc_get_template( $located, $template_name, $args, $template_path, $default_path ) {

		if (  ! isset( $args['pco_cache_template_part'] )  ) {

			if ( Plugin::view_exist('woocommerce/' . $template_name ) ) {

				$located = Plugin::view_path( 'woocommerce/' . $template_name );
				// $located = '';
			}
		}

		return $located;
	}

	public static function get_cached_content( $key ) {

	}

	public static function wc_get_template_part( $template, $slug, $name ) {
		// s( $template );
		$key = self::get_key_pr_page_id( $template . $slug . $name  );
		$ttl = 3600;

		$output = get_transient($key);
		if ( empty($output) ) {
			ob_start();
			if ( $template ) {
				load_template( $template, false );
			}
			$output = ob_get_clean();
			set_transient($key, $output, $ttl);
		}

		echo $output;

		return null; //returning null, is important! that way woocommerce thinks, there is no templates - https://github.com/woocommerce/woocommerce/blob/6b1d8fbb57d74a4afd4326db68a53862e4ce8c1a/includes/wc-core-functions.php#L175
	}

	public static function cache_template_start( $template_name, $template_path, $located, $args ) {
		ob_start();
	}

	public static function cache_template_end( $template_name, $template_path, $located, $args ) {
		echo ob_get_clean();
	}



	public static function fragment_cache($key, $ttl, $function) {
		if ( is_user_logged_in() ) {
			call_user_func($function);
			return;
		}
		$key = apply_filters('fragment_cache_prefix','fragment_cache_').$key;
		$output = get_transient($key);
		if ( empty($output) ) {
			ob_start();
			call_user_func($function);
			$output = ob_get_clean();
			set_transient($key, $output, $ttl);
		}
		echo $output;
	}

}
