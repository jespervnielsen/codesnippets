<?php

namespace APP\PluginName\Core\Abstracts\WooCommerce;

//This is how I stucture my plugins, and wont work for others, without the Plugin file, in same namespace
//use APP\PluginName\Plugin;
/**
 * Abstract MyAccount endpoint Class
 *
 * @category WooCommerce
 */
abstract class MyAccountEndpointBase {


	/**
	 * endpoint
	 *
	 * @var string
	 */
	public $endpoint = '';

	/**
	 * args to build the endpoint. the passed args, will also be available in the view
	 *
	 * @var array
	 */
	public $args = [];

	/**
	 * value for the endpoint, used to pass for example ids or other query params by url
	 *
	 * @var string
	 */
	public $value;

	/**
	 * construct
	 *
	 */
	public function __construct() {

		add_action( 'plugins_loaded', [ $this, 'init' ], 1 );
	}

	/**
	 * settings - Should be overriden in child class, to define the settings, and return as array
	 *
	 * @return array
	 */
	abstract  public function settings() : array;

	/**
	 * content view args - Should be overriden in child class, to define args that are needed in the view file.
	 *
	 * @return array
	 */
	abstract public function content_view_args( $value = null ) : array;

	/**
	 * init the object, abort if no woocommerce, or there is missing an endpoint
	 *
	 * @return void
	 */
	public function init() {

		if ( ! class_exists( 'WooCommerce' ) ) {
			return;

		}

		$this->setup_object();

		if ( ! isset( $this->args['endpoint'] ) || empty( $this->args['endpoint'] ) ) {
			return '';
		}

		$this->add_hooks();

	}

	/**
	 * add the hooks, needed to create an myaccount page endpoint
	 *
	 * @return void
	 */
	public function add_hooks() {

		//Registers the endpoint
		add_action( 'init', [ $this, 'register_endpoint' ] );

		//Add to menu items
		add_filter( 'woocommerce_account_menu_items', [ $this, 'menu_items' ], 20 );

		//Ádd to query vars
		add_filter( 'woocommerce_get_query_vars', [ $this, 'register_endpoint_query_vars' ] );

		//The content of the endpoint
		add_action( 'woocommerce_account_' . $this->endpoint . '_endpoint', [ $this, 'endpoint_content' ] );

		// Change the My Accout page title, on endpoints
		add_filter( 'the_title', [ $this, 'endpoint_title' ], 10, 2 );
	}

	/**
	 * Setup the object.
	 * We Make sure only run this, after plugins is loaded, so wee can work with params, that requires other plugins, (like WooCommerce)
	 *
	 * @return void
	 */
	public function setup_object() {

		$args = $this->settings();

		$defaults = [
			'endpoint'      => '', // * this is required Remember to make it translatable
			'label'         => null, //should be overriden, but if not, we fallback to the enpoint key
			'title'         => null,  // The Title of the page - defaults to label
			'menu_position' => false, // use numeric value, for a specific place. true = last, false = not shown in menu
			'show_content'  => true,
		];

		$this->args = array_merge( $defaults, $args );

		$this->endpoint = $this->args['endpoint'];
		$this->value    = '';

		$this->args['label'] ?? $this->args['endpoint'];
		$this->args['title'] ?? $this->args['label'];
	}

	/**
	 *
	 * @note we find the value, the exact same way as WooCommerce
	 * @link https://github.com/woocommerce/woocommerce/blob/master/includes/wc-template-functions.php#L2831
	 * it could maybe been done with get_query_arg( $this->endpoint), but that results in fatal error, if endpoint is null when its being called. something like that
	 *
	 * @return void
	 */
	public function get_current_value() {
		global $wp;
		if ( ! empty( $wp->query_vars ) ) {
			if ( isset( $wp->query_vars[ $this->endpoint ] ) ) {
				return $wp->query_vars[ $this->endpoint ];
			}
		}
		return null;
	}

	/**
	 * menu_items adds the item to the my-account menu
	 *
	 * @param array $items
	 * @return array
	 */
	public function menu_items( $items ) {

		$new_items = [];

		if ( ! $this->args['menu_position'] || ! isset( $this->args['label'] ) ) {
			return $items;
		}

		$menu_position = (int) $this->args['menu_position'];

		$new_items[ $this->endpoint ] = $this->args['label'];

		if ( is_int( $menu_position ) ) {
			$items = self::array_insert( $items, $new_items, $menu_position );
		} else {
			$items = array_merge( $items, $new_items );
		}

		return $items;
	}

	/**
	 * Registers endpoint to the rewrite rules
	 *
	 * @return void
	 */
	public function register_endpoint() {
			add_rewrite_endpoint( $this->endpoint, EP_PAGES );
	}

	/**
	 * Registers endpoint query vars, alongside with WooCommerce's other query vars
	 *
	 * @param array $vars
	 * @return void
	 */
	public function register_endpoint_query_vars( $vars ) {

		$vars[ $this->endpoint ] = $this->endpoint;

		return $vars;
	}

	/**
	* Set endpoint title. since the MyAccount endpoint, is a standard page with a shortcode, we need to override that.
	*
	* @param string $title
	* @return string
	*/
	public function endpoint_title( $title, $post_id ) {
		global $wp_query;
		global $post;

		if ( ! is_admin() && is_main_query() && ! in_the_loop() && is_account_page() && $post_id == get_the_ID() && get_post_type() == 'page' ) {
			if ( $this->is_current_endpoint() ) {
				$title = $this->args['title'];
			}
		}

		return $title;
	}

	/**
	 * Endpoint content. echo's the content to display inside the myaccount content area.
	 *
	 * mixed $value referes to the query arg, that is passed in url, and passed through the action
	 * @return void
	 */
	public function endpoint_content( $value = null ) {

		if ( ! isset( $this->args['show_content'] ) || ! $this->args['show_content'] ) {
			return;
		}

		$value_arr['value']                   = $value;
		$value_arr['current_endpoint_object'] = $this;
		ob_start();

    /**
    Here should the output be.
    Ideally included using include_once or something similar, but that is up to you, and your workflow.
    
    In my Work we include the views by calling a function from our plugin class.
		Plugin::get_view(
			'my-account/' . $this->endpoint . '.php',
			array_merge( $this->args, $value_arr, $this->content_view_args( $value ) ) //all args passed to class, will be available in the view
		);
     */

		echo ob_get_clean();
	}

	/**
	 * Helper: array insert - insert at a specific offset into an array
	 *
	 * @param array $array
	 * @param array $values
	 * @param integer $offset
	 * @return array
	 */
	public static function array_insert( $array, $values, $offset ) {
		return array_slice( $array, 0, $offset, true ) + $values + array_slice( $array, $offset, null, true );
	}

	/**
	 * Helper: get endpoint url - gets the url / permalink, for this endpoint
	 *
	 * @return string
	 */
	public function get_endpoint_url( $value = null ) {
		//if no value is passed, lets check if wee need to get one
		if ( null === $value ) {
			$value = $this->get_current_value();
		}

		return wc_get_endpoint_url( $this->endpoint, $this->$value, wc_get_page_permalink( 'myaccount' ) );
	}

	/**
	 * Helper: is current endpoint
	 *
	 * @return bool
	 */
	public function is_current_endpoint() {

		global $wp_query;

		if ( ! $wp_query ) {
			return false;
		}

		return isset( $wp_query->query[ $this->endpoint ] );
	}

	/**
	 * Helper: get the active endpoint
	 *
	 * @return string
	 */
	public static function get_active_endpoint() {
		return WC()->query->get_current_endpoint();
	}

}
