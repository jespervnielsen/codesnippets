<?php
/*
Plugin Name: JVN woocommerce improvements
Description: standard improvements
Author: Jesper
Version: 1.0
Author URI: https://github.com/jespervnielsen
*/

/* -----------------------------------------------
*   Settings Tab under WooCommerce settings
** ----------------------------------------------- */
class WC_mswc {
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_filter( 'woocommerce_get_sections_mswc', __CLASS__ . '::mswc_add_sections' );
        add_action( 'woocommerce_settings_tabs_mswc', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_mswc', __CLASS__ . '::update_settings' );        
    }

    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['mswc'] = __( 'MS WC', 'woocommerce-settings-tab-mswc' );        
        return $settings_tabs;
    }

    function mswc_add_sections( $sections ) {
        //http://docs.woothemes.com/document/adding-a-section-to-a-settings-tab/
        //http://stackoverflow.com/questions/26355697/woocommerce-add-sections-inside-the-tabs
        $sections[''] = __( 'General', 'woocommerce-settings-tab-mswc' );
        $sections['products'] = __( 'Shop / Products Page', 'woocommerce-settings-tab-mswc' );
        $sections['single_product'] = __( 'Single Product Page', 'woocommerce-settings-tab-mswc' );
        $sections['cart'] = __( 'Cart page', 'woocommerce-settings-tab-mswc' );
        $sections['checkout'] = __( 'Checkout page', 'woocommerce-settings-tab-mswc' );
        echo '<pre>'.print_r($sections, true).'</pre>';
        return $sections;
        
    }

    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }

    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }

    public static function get_settings() {
        //http://www.skyverge.com/blog/add-custom-options-to-woocommerce-settings/
        $settings = array(
            'section_general' => array(
                'name'  => __( 'Mediastyle WooCommerce', 'woocommerce-settings-tab-mswc' ),
                'type'  => 'title',
                'desc'  => '',
                'id'    => 'wc_mswc'
                ),

            'dkk_currency'  => array(
                'name'      => __( 'Add DKK Currency', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'checkbox',
                'desc'      => __( 'Add DKK Currency to WooCommerce', 'woocommerce-settings-tab-mswc' ),
                'id'        => 'wc_settigs_tab_mswc_dkk_currency',
                'options'   => array(
                    'yes'   => __('Enabled','woocommerce-settings-tab-mswc'),
                    ),
                'default'   => 'no'
                ),

            'order_prefix'  => array(
                'name'      => __( 'Use order prefix', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'checkbox',
                'desc'      => __( 'Use the below defined order prefix', 'woocommerce-settings-tab-mswc' ),
                'id'        => 'wc_settigs_tab_mswc_order_prefix',
                'options'   => array(
                    'yes'   => __('Enabled','woocommerce-settings-tab-mswc'),
                    ),
                'default'   => 'no'
                ),

            'order_prefix_text' => array(
                'name'      => __( 'Order prefix', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'text',
                'desc'      => __( 'Type the order prefix you would like to use', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_order_prefix_text',
                'default'   => ''
                ),

            'remove_breadcrum'  => array(
                'name'      => __( 'Remove Breadcrum', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'checkbox',
                'desc'      => __( 'Remove WooCommerce breadcrum before main content', 'woocommerce-settings-tab-mswc' ),
                'id'        => 'wc_settigs_tab_mswc_remove_breadcrum',
                'options'   => array(
                    'yes'   => __('Enabled','woocommerce-settings-tab-mswc'),
                    ),
                'default'   => 'no'
                ),

            'remove_sku'    => array(
                'name'      => __( 'Remove SKU', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'select',
                'desc'      => __( 'Remove SKU from product page, disable all together or leave as is', 'woocommerce-settings-tab-mswc' ),
                'id'        => 'wc_settigs_tab_mswc_remove_sku',
                'options'   => array(
                    'product_page'  => __('Remove from product page','woocommerce-settings-tab-mswc'),
                    'disable'   => __('Disable completely','woocommerce-settings-tab-mswc'),
                    'enabled'   => __('Keep enabled','woocommerce-settings-tab-mswc'),
                    ),
                'default'   => 'enabled'
                ),

            'section_end'   => array(
                'type'  => 'sectionend',
                'id'    => 'wc_mswc_section_end'
                ),

            /*******************
            *  Shop / Products Page
            *******************/

            'section_products' => array(
                'name'  => __( 'Shop / Products Page', 'woocommerce-settings-tab-mswc' ),
                'type'  => 'title',
                'desc'  => '',
                'id'    => 'wc_mswc_products'
                ),

            'products_per_page' => array(
                'name'      => __( 'Products per page', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'number',
                'desc'      => __( 'Number of products you want to display per page', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_products_per_page',
                'default'   => 12
                ),

            'columns_per_row'   => array(
                'name'      => __( 'Columns per page', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'number',
                'desc'      => __( 'Number of products columns per Row count you want', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_columns_per_row',
                'default'   => 4
                ),

            'product_sales_ticker'  => array(
                'name'      => __( 'Product Sales Ticker', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'checkbox',
                'desc'      => __( 'Hide products sales ticker', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_product_sales_ticker',
                'options'   => array(
                    'yes'   => __('Enabled','woocommerce-settings-tab-mswc'),
                    ),
                'default'   => 'no'
                ),

            'product_price' => array(
                'name'      => __( 'Product Price', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'checkbox',
                'desc'      => __( 'Hide the product price', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_product_price',
                'options'   => array(
                    'yes'   => __('Enabled','woocommerce-settings-tab-mswc'),
                    ),
                'default'   => 'no'
                ),

            'product_add_to_cart'   => array(
                'name'      => __( '"Add to Cart" Button', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'checkbox',
                'desc'      => __( 'Hide "Add to Cart" Button', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_product_add_to_cart',
                'options'   => array(
                    'yes'   => __('Enabled','woocommerce-settings-tab-mswc'),
                    ),
                'default'   => 'no'
                ),

            'remove_heading'    => array(
                'name'      => __( 'Product description heading', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'checkbox',
                'desc'      => __( 'Remove the description heading from the product page', 'woocommerce-settings-tab-mswc' ),
                'id'        => 'wc_settigs_tab_mswc_remove_heading',
                'options'   => array(
                    'yes'   => __('Enabled','woocommerce-settings-tab-mswc'),
                    ),
                'default'   => 'no'
                ),

            'section_products_end'  => array(
                'type'  => 'sectionend',
                'id'    => 'wc_mswc_produtcs_section_end'
                ),

            /*******************
            *  Single Product Page
            *******************/

            'section_single_products' => array(
                'name'  => __( 'Single Product Page', 'woocommerce-settings-tab-mswc' ),
                'type'  => 'title',
                'desc'  => '',
                'id'    => 'wc_mswc_single_product'
                ),

            'single_product_sales_ticker'   => array(
                'name'      => __( 'Product Sales Ticker', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'checkbox',
                'desc'      => __( 'Hide products sales ticker', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_single_product_sales_ticker',
                'options'   => array(
                    'yes'   => __('Enabled','woocommerce-settings-tab-mswc'),
                    ),
                'default'   => 'no'
                ),

            'single_product_price'  => array(
                'name'      => __( 'Product Price', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'checkbox',
                'desc'      => __( 'Hide the product price', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_single_product_price',
                'options'   => array(
                    'yes'   => __('Enabled','woocommerce-settings-tab-mswc'),
                    ),
                'default'   => 'no'
                ),

            'single_product_excerpt'    => array(
                'name'      => __( 'Product Excerpt', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'checkbox',
                'desc'      => __( 'Hide Product Excerpt', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_single_product_excerpt',
                'options'   => array(
                    'yes'   => __('Enabled','woocommerce-settings-tab-mswc'),
                    ),
                'default'   => 'no'
                ),

            'single_product_add_to_cart'    => array(
                'name'      => __( '"Add to Cart" Button', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'checkbox',
                'desc'      => __( 'Hide "Add to Cart" Button', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_single_product_add_to_cart',
                'options'   => array(
                    'yes'   => __('Enabled','woocommerce-settings-tab-mswc'),
                    ),
                'default'   => 'no'
                ),

            'single_product_meta'   => array(
                'name'      => __( 'Product Meta', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'checkbox',
                'desc'      => __( 'Hide Product Meta (categories/tags)', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_single_product_meta',
                'options'   => array(
                    'yes'   => __('Enabled','woocommerce-settings-tab-mswc'),
                    ),
                'default'   => 'no'
                ),

            'single_product_related'    => array(
                'name'      => __( 'Related Products', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'checkbox',
                'desc'      => __( 'Hide Related Products', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_single_product_related',
                'options'   => array(
                    'yes'   => __('Enabled','woocommerce-settings-tab-mswc'),
                    ),
                'default'   => 'no'
                ),

            'single_product_related_columns'    => array(
                'name'      => __( 'Related products columns', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'number',
                'desc'      => __( 'Related Products Columns Per row', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_single_product_related_columns',
                'default'   => 3
                ),

            'single_product_related_number' => array(
                'name'      => __( 'Related products', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'number',
                'desc'      => __( 'Number of Related Products to display', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_single_product_related_number',
                'default'   => 3
                ),

            'single_product_upsells'    => array(
                'name'      => __( 'Up Sells Products', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'checkbox',
                'desc'      => __( 'Hide Up Sells Products', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_single_product_upsells',
                'options'   => array(
                    'yes'   => __('Enabled','woocommerce-settings-tab-mswc'),
                    ),
                'default'   => 'no'
                ),

            'single_product_upsells_number' => array(
                'name'      => __( 'Up Sells Products', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'number',
                'desc'      => __( 'Number of Up Sells to display', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_single_product_upsells_number',
                'default'   => 3
                ),

            'single_product_upsells_columns'    => array(
                'name'      => __( 'Up Sells Product Columns', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'number',
                'desc'      => __( 'Up Sells Product Columns Per row', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_single_product_upsells_columns',
                'default'   => 3
                ),

            'section_single_product_end'    => array(
                'type'  => 'sectionend',
                'id'    => 'wc_mswc_single_produtc_section_end'
                ),

            /*******************
            *  Cart
            *******************/

            'section_cart_page' => array(
                'name'  => __( 'Cart Page', 'woocommerce-settings-tab-mswc' ),
                'type'  => 'title',
                'desc'  => '',
                'id'    => 'wc_mswc_single_product'
                ),

            'cart_page_cross_sells' => array(
                'name'      => __( 'Cross Sells Products', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'checkbox',
                'desc'      => __( 'Hide Cross Sells Products', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_cart_page_cross_sells',
                'options'   => array(
                    'yes'   => __('Enabled','woocommerce-settings-tab-mswc'),
                    ),
                'default'   => 'no'
                ),

            'cart_page_cross_sells_number'  => array(
                'name'      => __( 'Cross Sells Display', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'number',
                'desc'      => __( 'Number of Cross Sells to display', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_cart_page_cross_sells_number',
                'default'   => 4
                ),

            'cart_page_cross_sells_columns' => array(
                'name'      => __( 'Cross Sells Columns', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'number',
                'desc'      => __( 'Cross Sells Product Columns Per row', 'woocommerce-settings-tab-mswc' ),
                //'desc_tip'    => true,
                'id'        => 'wc_settigs_tab_mswc_cart_page_cross_sells_columns',
                'default'   => 4
                ),

            'section_cart_page_end' => array(
                'type'  => 'sectionend',
                'id'    => 'wc_mswc_cart_page_section_end'
                ),

            /*******************
            *  Checkout
            *******************/

            'section_checkout_page' => array(
                'name'  => __( 'Checkout Page', 'woocommerce-settings-tab-mswc' ),
                'type'  => 'title',
                'desc'  => '',
                'id'    => 'wc_mswc_single_product'
                ),

            'modify_checkout'   => array(
                'name'      => __( 'Checkout layout', 'woocommerce-settings-tab-mswc' ),
                'type'      => 'checkbox',
                'desc'      => __( 'Modify the checkout layout', 'woocommerce-settings-tab-mswc' ),
                'id'        => 'wc_settigs_tab_mswc_modify_checkout',
                'options'   => array(
                    'yes'   => __('Enabled','woocommerce-settings-tab-mswc'),
                    ),
                'default'   => 'no'
                ),

            'section_checkout_page_end' => array(
                'type'  => 'sectionend',
                'id'    => 'wc_mswc_cart_page_section_end'
                ),
            );
        return apply_filters( 'wc_mswc_settings', $settings );
    }
}
WC_mswc::init();

/* -----------------------------------------------
*   DKK currency
** ----------------------------------------------- */
if( get_option('wc_settigs_tab_mswc_dkk_currency') == 'yes' ){
    add_filter( 'woocommerce_currencies', 'ms_add_my_currency' );
	if ( ! function_exists( 'ms_add_my_currency' ) ) {
		function ms_add_my_currency( $currencies ) {
			$currencies['DKK'] = __( 'Danske Kroner', 'woocommerce' );
			return $currencies;
		}
	}
    add_filter('woocommerce_currency_symbol', 'ms_add_my_currency_symbol', 10, 2);

	if ( ! function_exists( 'ms_add_my_currency_symbol' ) ) {
		function ms_add_my_currency_symbol( $currency_symbol, $currency ) {
			switch( $currency ) {
				case 'DKK': $currency_symbol = 'DKK'; break;
			}
			return $currency_symbol;
		}
	}
}

/* -----------------------------------------------
*   Add Prefix to WooCommerce Order Number
** ----------------------------------------------- */
if( get_option('wc_settigs_tab_mswc_order_prefix') == 'yes' ){
    add_filter( 'woocommerce_order_number', 'ms_woocommerce_order_number', 1, 2 );

    function ms_woocommerce_order_number( $oldnumber, $order ) {
        $wc_prefix = get_option('wc_settigs_tab_mswc_order_prefix_text', '');
        return $wc_prefix . $order->id;
    }
}

/* -----------------------------------------------
*   Remove breadcrum
** ----------------------------------------------- */
if( get_option('wc_settigs_tab_mswc_remove_breadcrum') == 'yes' ){
    remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);
}

/* -----------------------------------------------
*   Remove or disable SKU
** ----------------------------------------------- */
function mswc_remove_product_page_skus( $enabled ) {
    $mswc_remove_sku = get_option('wc_settigs_tab_mswc_remove_sku');
    if ( ! is_admin() && is_product() && $mswc_remove_sku == 'product_page') {
        return false;
    }
    if($mswc_remove_sku == 'disable'){
        return false;
    }

    return $enabled;
}
add_filter( 'wc_product_sku_enabled', 'mswc_remove_product_page_skus' );

/* -----------------------------------------------
*   Products per page
** ----------------------------------------------- */
function mswc_products_per_page(){
    add_filter('loop_shop_per_page', get_option('wc_settigs_tab_mswc_products_per_page', 12),20);
}
add_action('init', 'mswc_products_per_page');

/* -----------------------------------------------
*   Columns per row
** ----------------------------------------------- */
function mswc_columns_per_row(){
    // add_filter('loop_shop_columns', get_option('wc_settigs_tab_mswc_columns_per_row', 4));
}
add_action('init', 'mswc_columns_per_row');

/* -----------------------------------------------
*   Product Sales Ticker
** ----------------------------------------------- */
if ( get_option('wc_settigs_tab_mswc_product_sales_ticker') == 'yes' ) {
    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
}

/* -----------------------------------------------
*   Product Price
** ----------------------------------------------- */
if ( get_option('wc_settigs_tab_mswc_product_price') == 'yes' ) {
    remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
}

/* -----------------------------------------------
*   Loop "Add to Cart" Button
** ----------------------------------------------- */
if ( get_option('wc_settigs_tab_mswc_product_add_to_cart') == 'yes' ) {
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
}

/* -----------------------------------------------
*   WOOCOMMERCE TABS - REMOVE PRODUCT DESCRIPTION HEADING
** ----------------------------------------------- */
if( get_option('wc_settigs_tab_mswc_remove_heading') == 'yes' ){
    add_filter('woocommerce_product_description_heading', false);
}

/* -----------------------------------------------
*   Product Sales Ticker
** ----------------------------------------------- */
if( get_option('wc_settigs_tab_mswc_single_product_sales_ticker') == 'yes' ){
    remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
}

/* -----------------------------------------------
*   Product Price
** ----------------------------------------------- */
if( get_option('wc_settigs_tab_mswc_single_product_price') == 'yes' ){
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
}

/* -----------------------------------------------
*   Product Excerpt
** ----------------------------------------------- */
if( get_option('wc_settigs_tab_mswc_single_product_excerpt') == 'yes' ){
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
}

/* -----------------------------------------------
*   Product "Add to Cart" Button
** ----------------------------------------------- */
if( get_option('wc_settigs_tab_mswc_single_product_add_to_cart') == 'yes' ){
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
}

/* -----------------------------------------------
*   Product Meta
** ----------------------------------------------- */
if( get_option('wc_settigs_tab_mswc_single_product_meta') == 'yes' ){
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
}

/* -----------------------------------------------
*   Related Products
** ----------------------------------------------- */
if( get_option('wc_settigs_tab_mswc_single_product_related') == 'yes' ){
    add_filter('woocommerce_related_products_args', 'wc_remove_related_products', 10);
}else{
    add_filter('woocommerce_related_products_args', 'wc_filter_related_products', 10);
}

function wc_remove_related_products( $args ) {
    return array();
}

function wc_filter_related_products($args){
    $args = array(
            'posts_per_page' => get_option('wc_settigs_tab_mswc_single_product_related_number', 3),
            'columns' => get_option('wc_settigs_tab_mswc_single_product_related_columns',3),
            'orderby' => 'rand'
            );
    
    return $args;
}

/* -----------------------------------------------
*   Product Upsells
** ----------------------------------------------- */
if( get_option('wc_settigs_tab_mswc_single_product_upsells') == 'yes' ){
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display',15 );
}else{
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display',15 );
    add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_upsells', 15);
}

function woocommerce_output_upsells() {
    woocommerce_upsell_display(get_option('wc_settigs_tab_mswc_single_product_upsells_number',3),get_option('wc_settigs_tab_mswc_single_product_upsells_columns',3)); // Display 3 products in rows of 3
}

/* -----------------------------------------------
*   Product Upsells
** ----------------------------------------------- */
if( get_option('wc_settigs_tab_mswc_cart_page_cross_sells') == 'yes' ){
}


/* -----------------------------------------------
*   Remove pagetitles from woocommerce, on shop page
** ----------------------------------------------- */
if ( ! function_exists( 'ms_remove_shop_page_title' ) ) {
//add_action( 'wp', 'ms_remove_shop_page_title');
	function ms_remove_shop_page_title() {
		if( is_shop()) {
			add_filter('woocommerce_show_page_title',false);
		}
	}
}
/* -----------------------------------------------
*   Reorder single product page layout
** ----------------------------------------------- */
add_action( 'wp', 'ms_reorder_single_product');
function ms_reorder_single_product() {
    // remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
    // add_action( 'woocommerce_before_single_product_summary', 'woocommerce_template_single_title', 5 );

    // remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
    // add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 20 );

    // remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );    

    // remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

    // add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

    // add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

    // remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
    // add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 8 );

    // add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );


    // remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );

    // remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

    //remove result count
    //remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

    //remove catalog ordering. (sorting box)
    //remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
}

/* -----------------------------------------------
*   WOOCOMMERCE CHECKOUT
** ----------------------------------------------- */
add_action('plugins_loaded','ms_modify_checkout');
function ms_modify_checkout() {

	if( get_option('wc_settigs_tab_mswc_modify_checkout') == 'yes' ){

		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
	}
	
	
	
}
