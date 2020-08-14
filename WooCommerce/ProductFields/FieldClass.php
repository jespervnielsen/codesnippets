<?php

namespace Example\WooCommerceProductFields;

/**
 * Class to contain a single- & Variable product meta field
 */

class HelperText {

	/**
	 * Key of field.
	 *
	 * @var string
	 */
	public static $field_key = '_nordiccell_pos_extension__helper_text';
	
	/**
	 * Label of field. - remember translation
	 *
	 * @var string
	 */
	public static $field_name = 'Helper Text';

	/**
	 * Field types. *Note, the complex ones is not yet supported
	 * woocommerce_wp_text_input
	 * woocommerce_wp_hidden_input
	 * woocommerce_wp_textarea_input
	 * woocommerce_wp_checkbox
	 * woocommerce_wp_select
	 * woocommerce_wp_radio
	 *
	 * @var string
	 */
	public static $field_type = 'woocommerce_wp_textarea_input';
	/**
	 * Avaviable posistions of custom fields inside existing product data tabs
	 *
	 * General tab: woocommerce_product_options_pricing | woocommerce_product_options_downloads | woocommerce_product_options_tax | woocommerce_product_options_general_product_data
	 * Advanved tab : woocommerce_product_options_reviews | woocommerce_product_options_advanced
	 * Data attributes: woocommerce_product_options_attributes
	 * Data inventory: woocommerce_product_options_sku | woocommerce_product_options_stock | woocommerce_product_options_stock_fields | woocommerce_product_options_stock_status | woocommerce_product_options_sold_individually | woocommerce_product_options_inventory_product_data
	 * Linked products: woocommerce_product_options_related
	 * Product data: woocommerce_product_write_panel_tabs | woocommerce_product_data_panels
	 * Shpping: woocommerce_product_options_dimensions | woocommerce_product_options_shipping
	 * Variations: woocommerce_variable_product_bulk_edit_actions
	 *
	 *
	 * @var string
	 */
	public static $product_tab = 'woocommerce_product_options_general_product_data';

	/**
	 * Types this field should be shown on. Note that "variabable" will be for each variable product inside parent product.
	 *
	 * @var array
	 */
	public static $product_types = [ 'simple', 'variable' ]; // simple | variable


	public function __construct() {
		$this->init();
		add_action( 'plugins_loaded', [ $this, 'init' ] ); // run on plugins loaded, to be sure we have woocommerce
	}

	public static function get_key() {
		return self::$field_key;
	}

	public static function get_name() {
		return self::$field_name;
	}

	public static function get_tab() {
		return self::$product_tab;
	}

	public static function get_type() {
		return self::$field_type;
	}

	public static function product_types() {
		return self::$product_types;
	}

	/**
	 * Run core bootstrap hooks.
	 */
	public function init() {

		/**
		 * Simple Products
		 */
		if ( in_array( 'simple', self::product_types() ) ) {
			add_action( self::get_tab(), [ $this, 'simple_field' ], 1 );
			add_action( 'save_post', [ $this, 'save_simple_field' ] );
		}

		/**
		 * Variable Products
		 */
		if ( in_array( 'variable', self::product_types() ) ) {
			add_action( 'woocommerce_product_after_variable_attributes', [ $this, 'variation_data_field' ], 10, 3 );
			add_action( 'woocommerce_save_product_variation', [ $this, 'variation_data_tab_save' ], 10, 2 );
		}

		//columns @link https://rudrastyh.com/woocommerce/columns.html
		add_filter( 'manage_edit-product_columns', [ $this, 'field_column' ], 20 );
		add_action( 'manage_posts_custom_column', [ $this, 'populate_field_column' ], 10, 2 );

	}

	public function simple_field() {

		$args = [
			'id'    => self::get_key(),
			'name'  => self::get_key(),
			'label' => self::get_name(),
		];

		$field = self::get_type();

		$field( $args );
	}

	public function save_simple_field( $product_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( isset( $_POST[ self::get_key() ] ) ) {
			update_post_meta( $product_id, self::get_key(), esc_attr( $_POST[ self::get_key() ] ) );
		}
	}

	/**
	 * Variation Product Meta Field
	 *
	 * @param $loop
	 * @param $variation_data
	 * @param $variation
	 */
	public function variation_data_field( $loop, $variation_data, $variation ) {

		$args = [
			'id'            => 'variable_' . self::get_key() . '_' . $loop,
			'name'          => 'variable_' . self::get_key() . '[' . $loop . ']',
			'value'         => esc_attr( get_post_meta( $variation->ID, self::get_key(), true ) ),
			'label'         => self::get_name(),
			'wrapper_class' => 'form-row',
		];

		$field = self::get_type();

		$field( $args );

	}

	/**
	 * Save Variation Product Meta
	 *
	 * @param $variation_id
	 * @param $i
	 */
	public function variation_data_tab_save( $variation_id, $i ) {

		if ( ! isset( $_POST['variable_post_id'] ) ) {
			return;
		}

		$variation_ids = [];

		foreach ( $_POST['variable_post_id'] as $key => $id ) {
			$variation_ids[ $key ] = $id;
		}

		foreach ( $_POST['variable_post_id'] as $key => $id ) {
			if ( isset( $_POST[ 'variable_' . self::get_key() ][ $key ] ) && ! empty( isset( $_POST[ 'variable_' . self::get_key() ][ $key ] ) ) ) {
				update_post_meta( $variation_ids[ $key ], self::get_key(), esc_attr( $_POST[ 'variable_' . self::get_key() ][ $key ] ) );
			} else {
				delete_post_meta( $variation_ids[ $key ], self::get_key() );
			}
		}
	}

	public function field_column( $columns_array ) {

		return array_slice( $columns_array, 0, 3, true ) + [ self::get_key() => self::get_name() ] + array_slice( $columns_array, 3, null, true );
	}

	public function populate_field_column( $column_name, $postid ) {

		if ( $column_name != self::get_key() ) {
			return;
		}

		echo get_post_meta( $postid, self::get_key(), true );
	}

}
