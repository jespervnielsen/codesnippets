<?php

namespace AutocompleteOrders;
use WC_Email_Customer_Invoice;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use WC_Email_Customer_Invoice;

class AutoCompleteVirtualOrder {

		public function __construct() {

		$this->init();
	}

	/**
	 * Run hooks.
	 */
	public function init() {
		//Autocomplete virtual orders
		add_action( 'woocommerce_thankyou', [ __CLASS__, 'auto_complete_order' ], 50 ); // Dont run on woocommerce_before_thankyou, since the order might have failed. - and the triggers regarding payments havent run yet.

		//maybe set bambora "instantcapture" when sending payment request, if the order only contains virtual orders
		// add_filter('woocommerce_get_checkout_payment_url', [ __CLASS__, 'auto_complete_order' ], 50 )

	}

	/**
	 * Autocomplete order
	 *
	 * @param int $order_id
	 * @return void
	 */
	public static function auto_complete_order( $order_id ) {
		if ( ! $order_id ) {
			return;
		}

		$order = wc_get_order( $order_id );

		// d( $order, $order->get_status() );

		$valid_statuses = [
			// 'pending', //we dont want to complete if we are waiting for pending payments
			'processing',
		];

		/**
		 * If we are on our local enviroment, the payment is beeing faked on a seperate callback for bambora, but then the status is still pending
		 */
		if( defined('PCO_ENV') && PCO_ENV == 'dev' ) {
			$valid_statuses[] = 'pending';
		}

		if ( ! in_array( $order->get_status(), $valid_statuses ) ) {
			return;
		}

		/**
		 * Bambora online classic, + WooCommerce Subscripts, then this fucks up, cause bambora checks if a parent order exist, then payment is needed. - even through we just payed.
		 */
		// if( $order->needs_payment() ) {
		// 	return;
		// }

		$items                   = $order->get_items();
		$has_non_virtual_product = false;

		foreach ( $items as $item ) {

			$product = $item->get_product();

			if ( ! $product->is_virtual() ) {
				$has_non_virtual_product = true;
			}
		}

		if ( $has_non_virtual_product ) {
			return;
		}

		//make sure the mail class is loaded
		if ( ! class_exists( 'WC_Email_Customer_Invoice' ) ) {
			( WC() )->mailer();
		}

		$order->update_status( 'completed' );

		/**
		 * Should we send out an invoice?
		 * Make sure, you dont send double emails, if other configuration also sends out an email
		 */
		// $email_ci = new WC_Email_Customer_Invoice();
		// $email_ci->trigger( $order_id );

		/**
		 * Should we send out ourder completed for customer?
		 * Make sure, you dont send double emails, if other configuration also sends out an email
		 */
		// $email_ci = new WC_Email_Customer_Invoice();
		// $email_ci->trigger( $order_id );

		return true;
	}
	
}

$autocomplete_order = new AutoCompleteVirtualOrder();
