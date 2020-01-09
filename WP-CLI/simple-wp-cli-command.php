<?php

namespace Custom\WPCLI\Commands\;

use WP_CLI;

class CustomCommand {

	public function __construct() {
		$this->init();
	}

	/**
	 * Run core bootstrap hooks.
	 */
	public function init() {
		if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
			return;
		}

		if ( ! class_exists( 'WP_CLI' ) ) {
			return;
		}

		WP_CLI::add_command(
			'pco appname command-name',
			[ __CLASS__, 'trigger_command' ],
			[
				'shortdesc'                => 'My synopsis',
								'synopsis' => [
									[
										'type'        => 'positional',
										'name'        => 'assoc_arg_1',
										'description' => 'first positional arg',
										'optional'    => false,
									],
									[
										'type'        => 'positional',
										'name'        => 'assoc_arg_2',
										'description' => 'second positional arg',
										'optional'    => false,
									],
									[
										'type'        => 'positional',
										'name'        => 'assoc_arg_3',
										'description' => 'third positional arg',
										'optional'    => true,
									],
								],
			]
		);
	}

	public static function trigger_command( $args = [], $assoc_args = [] ) {

		list( $assoc_arg_1, $assoc_arg_2, $assoc_arg_3) = $args; //list, the assoc_args (positional args), to variables. names should match names in synopsis array

		// Add functionality here
	}

}
