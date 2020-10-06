<?php

namespace AutocompleteOrders;
use WC_Email_Customer_Invoice;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class to autocomplete virtual orders.
 * WooCommerce only autocompletes if an order is virtual AND downloadable.
 *
 * @Note - this needs to be able to run in both frontend/admin/cli ect.
 */
class AutoCompleteVirtualOrder {

		public function __construct() {

		$this->init();
	}

	/**
	 * Run hooks.
	 */
	public function init() {
		//Autocomplete virtual orders, by only requiring that a product is virtual in order not to require proccessing
		add_filter('woocommerce_order_item_needs_processing', [__CLASS__, 'order_item_needs_proccessing' ], 10, 3);

		// @NOTE - This was the old way, before woocommerce introduced the nessary hook. keeping this around for documentation
		// add_action( 'woocommerce_payment_complete', [ __CLASS__, 'auto_complete_order' ], 50 ); // Dont run on woocommerce_before_thankyou, since the order might have failed. - and the triggers regarding payments havent run yet.
	}

	/**
	 * Let us only dependt on a product only should be virtual in order to allow for an order to be autocompleted - then WooCommerce handles the rest
	 * Woocommerce default requires a product to be downloadable AND virtual before autocomplete.
	 *
	 * @param bool $needs_processing
	 * @param object $product
	 * @param integer $order_id
	 * @return bool
	 */
	public static function order_item_needs_proccessing( $needs_processing, $product,$order_id ) {
		$needs_processing = ! $product->is_virtual();
		/**
		 * If we are on our local enviroment, the payment is beeing faked on a seperate callback for bambora, but then the status is still pending when visiting the thankyou page. - if so, reload thankyou page, and it should have correct status
		 */
		return $needs_processing;
	}

	/**
	 * Autocomplete order - OLD WAY keeping this around for documentation
	 *  This was the old way, before woocommerce introduced the nessary hook.
	 *
	 * @param int $order_id
	 * @return void
	 */
	public static function auto_complete_order( $order_id ) {
		if ( ! $order_id ) {
			return;
		}

		$order = wc_get_order( $order_id );

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

new AutoCompleteVirtualOrder();
