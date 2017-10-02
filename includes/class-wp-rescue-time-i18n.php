<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wpmanageninja.com
 * @since      1.0.0
 *
 * @package    Wp_Rescue_Time
 * @subpackage Wp_Rescue_Time/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Rescue_Time
 * @subpackage Wp_Rescue_Time/includes
 * @author     wpmanageninja <plugins@wpmanageninja.com>
 */
class Wp_Rescue_Time_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-rescue-time',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
