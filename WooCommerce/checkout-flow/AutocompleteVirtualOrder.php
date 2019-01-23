<?php

namespace AutocompleteOrders;
use WC_Email_Customer_Invoice;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use WC_Email_Customer_Invoice;

class AutoCompleteVirtualOrder {

	public function __construct() {
		add_action( 'woocommerce_thankyou', [ __CLASS__, 'auto_complete_order' ] );
	}

	public static function auto_complete_order( $order_id ) {
		if ( ! $order_id ) {
			return;
		}

		//make sure the mail class is loaded
		if ( ! class_exists( 'WC_Email_Customer_Invoice' ) ) {
			( WC() )->mailer();
		}

		$order                   = wc_get_order( $order_id );
		$items                   = $order->get_items();
		$has_non_virtual_product = false;

		foreach ( $items as $item ) {

			if ( 0 != $item->get_variation_id() ) {
				$product_id = $item->get_variation_id();
			} else {
				$product_id = $item->get_product_id();
			}

			$product = wc_get_product( $product_id );

			if ( ! $product->is_virtual() ) {
				$has_non_virtual_product = true;
			}
		}

		if ( ! $has_non_virtual_product && ! $order->needs_payment() ) {
			$order->update_status( 'completed' );
			$email_ci = new WC_Email_Customer_Invoice();
			$email_ci->trigger( $order_id );
		}

	}

}

$autocomplete_order = new AutoCompleteVirtualOrder();
