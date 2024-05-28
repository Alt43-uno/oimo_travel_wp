<?php

/**
 * Fired during plugin activation
 *
 * @link       https://wptravelengine.com/
 * @since      1.0.0
 *
 * @package    WTE_Fixed_Starting_Dates
 * @subpackage WTE_Fixed_Starting_Dates/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WTE_Fixed_Starting_Dates
 * @subpackage WTE_Fixed_Starting_Dates/includes
 * @author     wptravelengine <test@test.com>
 */
class WTE_Fixed_Starting_Dates_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// \WTE_Fixed_Starting_Dates_Functions::set_min_and_max_date();
		$arr     = array();
		$options = get_option( 'wp_travel_engine_settings' );

		if ( isset( $options['departure']['section'] ) ) {
			return;
		}

		$arr['departure']['section'] = '1';
		$enquiry_page                = array_merge_recursive( $options, $arr );
		update_option( 'wp_travel_engine_settings', $enquiry_page );

	}
}
