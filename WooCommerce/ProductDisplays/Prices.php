<?php
namespace PluginName\ProductDisplay\ProductData;

class Prices {

	public function __construct() {

		//Change price range display
		add_filter( 'woocommerce_format_price_range', [ __CLASS__, 'wc_format_price_range' ], 10, 3 );
		
		add_action( 'init', [ $this, 'init' ] );
		
	}

	public function init() {

	}

	/**
	 * Change price ranges, to display "from xx" instead of "xx - yy" (from - to)
	 *
	 * @param  string $price Current price range.
	 * @param  string $from  Price from.
	 * @param  string $to    Price to.
	 * @return string
	 */
	public static function wc_format_price_range( $price, $from, $to ) {

		//Choose either to use default woocommerce from span, or use custom From span/text

		//Custom "From" span/text - outcomment this if you want to use this.
		/* translators: 1: price from, istead of price from - to */
		// $price = sprintf( _x( 'From %1$s', 'Price range: from', Plugin::get_text_domain() ), is_numeric( $from ) ? wc_price( $from ) : $from );
		
		//By Using default WooCommerce "From" span. There is a filter to change that woocommerce_get_price_html_from_text
		$price = sprintf( '%1$s %2$s', 
			wc_get_price_html_from_text(),
			is_numeric( $from ) ? wc_price( $from ) : $from
		);

		return $price;
	}

}
