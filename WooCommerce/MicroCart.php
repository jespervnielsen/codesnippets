/**
* Adds micro cart. the contents of the html output, should match some existing. 
* Thats why we do it with actions, because then we can in the files where it is used, just run do_action
**/

add_filter( 'woocommerce_add_to_cart_fragments', 'jvn_wc_header_add_to_cart_fragment' );

function jvn_wc_header_add_to_cart_fragment( $fragments ) {
	ob_start();
	do_action('jvn_micro_cart'); //Or just call the function directly
	//Dom Selector
	$fragments['a.jvn-micro-cart'] = ob_get_clean();

	return $fragments;
}

add_action( 'jvn_micro_cart', 'jvn_micro_cart' ); //only needed, if called using do_action

function jvn_micro_cart() {

	$icon_type = 'fa-shopping-cart';
	// $icon_type = 'fa-shopping-basket';

	?>
	<a class="jvn-micro-cart nav-link test" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e( 'View 	your shopping cart' ); ?>">
		<i class="fa <?php print $icon_type; ?>" aria-hidden="true"></i>
		<?php _e('Cart'); ?>
		<span class="tag tag-pill tag-primary cart-badge"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
	</a>
	<?php //echo WC()->cart->get_cart_total();  ?>
	<?php
}
