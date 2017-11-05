<?php
/*
Plugin Name: Extra terms field
Description: Adds another checkbox to checkout. if woo subscribtions is active, we check if a subscribtion is in cart, before adding checkbox
Author: Jesper
Version: 1.0
Author URI: http://github.com/jespervnielsen
*/


//add actions
add_action('init','ms_etf_add_actions');

function ms_etf_add_actions() {
	//if subscribtions exits, and there is no product in cart wich has a subscribtion, return. 
	//if subscribtions not exits, show the checkbox anyway
	if( class_exists( 'WC_Subscriptions_Cart' ) ) {
		if(!WC_Subscriptions_Cart::cart_contains_subscription()) {
			return;
		}
	}
	add_action('woocommerce_review_order_after_submit', 'ms_etf_checkout_field');
	add_action('woocommerce_checkout_process', 'ms_etf_checkout_field_process');
	add_action('woocommerce_checkout_update_order_meta', 'ms_etf_checkout_field_update_order_meta');
}

/**
 * Add checkbox field to the checkout
 **/

 
function ms_etf_checkout_field( $checkout ) {
 
    echo '<p class="form-row terms">';
 
/*     woocommerce_form_field( 'extra_terms_checkbox', array(
        'type'          => 'checkbox',
        'class'         => array('input-checkbox'),
        'label'         => __('I have read and agreed.'),
        'required'  => true,
        ), false); */
		print '<label for="extra_terms_checkbox" class="checkbox">'.__('Jeg accepterer at jeg er ved at købe et abonnement','ms_etf').'</label>';
 print ' <input class="input-checkbox" name="extra_terms_checkbox" id="extra_terms_checkbox" type="checkbox">';
    echo '</p>';
}
 
/**
 * Process the checkout
 **/

 
function ms_etf_checkout_field_process() {
    global $woocommerce;
 
    // Check if set, if its not set add an error.
    if (!$_POST['extra_terms_checkbox'])
         wc_add_notice(__('Du skal acceptere, at du er ved at købe et abonnement','ms_etf'), 'error' );
}
 
/**
 * Update the order meta with field value
 **/
 
function ms_etf_checkout_field_update_order_meta( $order_id ) {
    if ($_POST['extra_terms_checkbox']) update_post_meta( $order_id, 'extra_terms_checkbox', esc_attr($_POST['extra_terms_checkbox']));
}
