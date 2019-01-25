namespace ModifyMailchimp4WooCommerce;
class mailchimp4wordpress {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'mc4wp_integration_woocommerce_checkbox_attributes', [ __CLASS__, 'add_woocommerce_classes_to_checkbox' ], 10, 2 );
	}
  
	public static function add_woocommerce_classes_to_checkbox( $attributes, $integration ) {
		$current_classes = '';
		if( isset( $attributes['class'] ) ) {
			$current_classes = $attributes['class'];
		}

		$attributes['class'] = 'input-checkbox ' . $current_classes;

		return $attributes;
	}
  
}
