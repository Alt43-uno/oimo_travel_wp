<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wptravelengine.com/
 * @since      1.0.0
 *
 * @package    WTE_Fixed_Starting_Dates
 * @subpackage WTE_Fixed_Starting_Dates/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    WTE_Fixed_Starting_Dates
 * @subpackage WTE_Fixed_Starting_Dates/includes
 * @author     wptravelengine <test@test.com>
 */
class WTE_Fixed_Starting_Dates_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wte-fixed-departure-dates',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
