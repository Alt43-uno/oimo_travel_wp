<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wptravelengine.com/
 * @since             1.0.0
 * @package           WTE_Fixed_Starting_Dates
 *
 * @wordpress-plugin
 * Plugin Name:       WP Travel Engine - Trip Fixed Starting Dates
 * Plugin URI:        https://wptravelengine.com/
 * Description:       An extension for WP Travel Engine plugin to add trip fixed starting dates or Fixed Departure Dates (FDT).
 * Version:           2.3.11
 * Author:            WP Travel Engine
 * Author URI:        https://wptravelengine.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wte-fixed-departure-dates
 * Domain Path:       /languages
 * WTE tested up to: 5.5.7
 * WTE requires at least: 4.3
 * WTE: 79:wte_fixed_starting_dates_license_key
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
define( 'WTE_FIXED_DEPARTURE_BASE_PATH', dirname( __FILE__ ) );
define( 'WTE_FIXED_DEPARTURE_VERSION', '2.3.11' );
define( 'WTE_FIXED_DEPARTURE_FILE_PATH', __FILE__ );
define( 'WTE_FIXED_DEPARTURE_FILE_URL', plugins_url( '', __FILE__ ) );
define( 'WTE_FIXED_DEPARTURE_REQUIRES_AT_LEAST', '4.3.0' );


register_activation_hook( __FILE__, 'activate_WTE_Fixed_Starting_Dates' );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wte-fixed-departure-dates-activator.php
 */
function activate_WTE_Fixed_Starting_Dates() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wte-fixed-departure-dates-activator.php';
	WTE_Fixed_Starting_Dates_Activator::activate();
}

register_deactivation_hook( __FILE__, 'deactivate_WTE_Fixed_Starting_Dates' );
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wte-fixed-departure-dates-deactivator.php
 */
function deactivate_WTE_Fixed_Starting_Dates() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wte-fixed-departure-dates-deactivator.php';
	WTE_Fixed_Starting_Dates_Deactivator::deactivate();
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_WTE_Fixed_Starting_Dates() {

	$plugin = new WTE_Fixed_Starting_Dates();
	$plugin->run();

}

add_action(
	'plugins_loaded',
	function() {
		if ( ! defined( 'WP_TRAVEL_ENGINE_VERSION' ) || version_compare( WP_TRAVEL_ENGINE_VERSION, WTE_FIXED_DEPARTURE_REQUIRES_AT_LEAST, '<' ) ) {
			add_action(
				'admin_notices',
				function() {
					echo wp_kses_post(
						sprintf(
							'<div class="error"><p>'
							// translators: 1. WTE Extension Name 2. Link to WTE Plugin.
							. sprintf( __( '%1$s requires the %2$s plugin to work. Please install and activate the latest <strong>WP Travel Engine</strong> plugin first.', 'wte-fixed-departure-dates' ), '<strong>WP Travel Engine - Trip Fixed Starting Dates</strong>', '<a href="https://wordpress.org/plugins/wp-travel-engine/" target="__blank">WP Travel Engine</a>' )
							. '</p></div>'
						)
					);
				}
			);
		} else {

			/**
			 * Vendors
			 */
			require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
			/**
			 * Helper functions
			 */
			require plugin_dir_path( __FILE__ ) . 'includes/helper-functions.php';
			/**
			 * The core plugin class that is used to define internationalization,
			 * admin-specific hooks, and public-facing site hooks.
			 */
			require plugin_dir_path( __FILE__ ) . 'includes/class-wte-fixed-departure-dates.php';

			run_WTE_Fixed_Starting_Dates();
		}
	}
);
