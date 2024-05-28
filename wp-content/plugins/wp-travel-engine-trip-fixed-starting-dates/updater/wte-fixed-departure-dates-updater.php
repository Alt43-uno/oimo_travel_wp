<?php
/**
 * Easy Digital Downloads Plugin Updater
 *
 * @package WTE_Fixed_Starting_Dates
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Includes the files needed for the plugin updater.
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {

	include dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php';
}

/**
 * Download ID for the product in Easy Digital Downloads.
 *
 * @since 1.0.0
 */
define( 'WTE_FIXED_DEPARTURE_ITEM_ID', 79 );

/**
 * Setup the updater for the WTE Fixed Starting Dates Add-on.
 *
 * @since 1.0.0
 */
function wte_fixed_starting_dates_updater() {
	if ( version_compare( WP_TRAVEL_ENGINE_VERSION, '4.3.8', '>=' ) || ! defined( 'WP_TRAVEL_ENGINE_STORE_URL' ) ) {
		return;
	}

	// retrieve our license key from the DB
	$settings    = get_option( 'wp_travel_engine_license' );
	$license_key = isset( $settings['wte_fixed_starting_dates_license_key'] ) ? esc_attr( $settings['wte_fixed_starting_dates_license_key'] ) : '';

	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater(
		WP_TRAVEL_ENGINE_STORE_URL,
		WTE_FIXED_DEPARTURE_FILE_PATH,
		array(
			'version' => WTE_FIXED_DEPARTURE_VERSION,                    // current version number
			'license' => $license_key,             // license key (used get_option above to retrieve from DB)
			'item_id' => WTE_FIXED_DEPARTURE_ITEM_ID,       // ID of the product
			'author'  => 'WP Travel Engine', // author of this plugin
			'beta'    => false,
		)
	);

}
add_action( 'admin_init', 'wte_fixed_starting_dates_updater', 0 );

/**
 * Add-ons name for plugin license page.
 *
 * @since 1.0.0
 */
function wte_fixed_starting_dates_name( $array ) {
	$array['WP Travel Engine - Trip Fixed Starting Dates'] = 'wte_fixed_starting_dates';
	return $array;
}
add_filter( 'wp_travel_engine_addons', 'wte_fixed_starting_dates_name' );

/**
 * Add-ons Item ID for plugin license page.
 *
 * @since 1.0.0
 */
function wte_fixed_starting_dates_id( $array ) {
	$array['wte_fixed_starting_dates'] = WTE_FIXED_DEPARTURE_ITEM_ID;
	return $array;
}
add_filter( 'wp_travel_engine_addons_id', 'wte_fixed_starting_dates_id' );

/**
 * Add-ons License details for showing updates in plugin license page.
 *
 * @since 1.0.0
 */
function wte_fixed_starting_dates_license( $array ) {
	$settings    = get_option( 'wp_travel_engine_license' );
	$license_key = isset( $settings['wte_fixed_starting_dates_license_key'] ) ? esc_attr( $settings['wte_fixed_starting_dates_license_key'] ) : '';

	$array[] =
		array(
			'version' => WTE_FIXED_DEPARTURE_VERSION,       // current version number
			'license' => $license_key,  // license key (used get_option above to retrieve from DB)
			'item_id' => WTE_FIXED_DEPARTURE_ITEM_ID,   // id of this product in EDD
			'author'  => 'WP Travel Engine',  // author of this plugin
			'url'     => home_url(),
		);
	return $array;
}

$wp_travel_engine           = get_option( 'wp_travel_engine_license' );
$wte_fixed_departure_status = isset( $wp_travel_engine['wte_fixed_starting_dates_license_status'] ) ? esc_attr( $wp_travel_engine['wte_fixed_starting_dates_license_status'] ) : '';

if ( isset( $wte_fixed_departure_status ) && $wte_fixed_departure_status == 'valid' ) {
	add_filter( 'wp_travel_engine_licenses', 'wte_fixed_starting_dates_license' );
}
