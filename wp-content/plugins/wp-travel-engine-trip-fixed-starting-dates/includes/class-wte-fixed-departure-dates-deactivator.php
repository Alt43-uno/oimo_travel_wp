<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://wptravelengine.com/
 * @since      1.0.0
 *
 * @package    WTE_Fixed_Starting_Dates
 * @subpackage WTE_Fixed_Starting_Dates/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    WTE_Fixed_Starting_Dates
 * @subpackage WTE_Fixed_Starting_Dates/includes
 * @author     wptravelengine <test@test.com>
 */
class WTE_Fixed_Starting_Dates_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option( '_wte_trips_available_months_set' );
	}
}
