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
										'name'        => 'arg_1',
										'description' => 'first positional arg',
										'optional'    => false,
									],
									[
										'type'        => 'assoc',
										'name'        => 'assoc_arg_1',
										'description' => 'second positional arg',
										'optional'    => false,
									],
								],
			]
		);
	}

	public static function trigger_command( $args = [], $assoc_args = [] ) {

		list( $arg_1) = $args; //list, the args (positional args), to variables. names should match names in synopsis array
		$assoc_arg_1 = $assoc_args['assoc_arg_1'] ?? null; //get one assoc arg, and set default value to null
		// Add functionality here
	}

}
