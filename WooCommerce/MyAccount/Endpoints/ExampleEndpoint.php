<?php

namespace APP\PluginName\Core\WooCommerce\Endpoints;

class CustomEndpoint extends MyAccountEndpointBase {

	public function settings() : array {
		/**
		 * pass in the arguments here.
		 *
		 * Important! refresh permalinks, either by going to permalinks in backend, or run $ wp rewrite flush
		 */
		$settings = [
			'endpoint'      => __( 'custom-endpoint', 'custom_text_domain' ),
			'label'         => __( 'This is my Custom Endpoint', 'custom_text_domain' ), //should be overriden, but if not, we fallback to they enpoint key
			'title'         => null,  // The Title of the page - defaults to label
			'menu_position' => 2, // use numeric value, for a specific place. true = last, false = not shown in menu
			'show_content'  => true,

		];

		return $settings;
	}

	/**
	 * pass in view arguments, for data that should be included in view file.
	 *
	 * @param int|string $value , the value used in the url - typically an ID or something
	 * @return array
	 */
	public function content_view_args( $value = null ) : array {
		$args = [];

		return $args;
	}

}
