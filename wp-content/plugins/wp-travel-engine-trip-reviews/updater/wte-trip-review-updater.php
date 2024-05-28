<?php
/**
 * Easy Digital Downloads Plugin Updater
 *
 * @package WTE_Trip_Review
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
define( 'WTE_TRIP_REVIEW_ITEM_ID', 2107 );

/**
 * Setup the updater for the WTE_Trip_Review Add-on.
 *
 * @since 1.0.0
 */
function wte_trip_reviews_updater() {

	// retrieve our license key from the DB
	$settings    = get_option( 'wp_travel_engine_license' );
	$license_key = isset( $settings['wte_trip_reviews_license_key'] ) ? esc_attr( $settings['wte_trip_reviews_license_key'] ) : '';

	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater(
		WP_TRAVEL_ENGINE_STORE_URL,
		WTE_TRIP_REVIEW_FILE_PATH,
		array(
			'version' => WTE_TRIP_REVIEW_VERSION,                    // current version number
			'license' => $license_key,             // license key (used get_option above to retrieve from DB)
			'item_id' => WTE_TRIP_REVIEW_ITEM_ID,       // ID of the product
			'author'  => 'WP Travel Engine', // author of this plugin
			'beta'    => false,
		)
	);

}
add_action( 'admin_init', 'wte_trip_reviews_updater', 0 );

/**
 * Add-ons name for plugin license page.
 *
 * @since 1.0.0
 */
function wte_trip_reviews_name( $array ) {
	$array['WP Travel Engine - Trip Reviews'] = 'wte_trip_reviews';
	return $array;
}
add_filter( 'wp_travel_engine_addons', 'wte_trip_reviews_name' );

/**
 * Add-ons Item ID for plugin license page.
 *
 * @since 1.0.0
 */
function wte_trip_reviews_id( $array ) {
	$array['wte_trip_review'] = WTE_TRIP_REVIEW_ITEM_ID;
	return $array;
}
add_filter( 'wp_travel_engine_addons_id', 'wte_trip_reviews_id' );

/**
 * Add-ons License details for showing updates in plugin license page.
 *
 * @since 1.0.0
 */
function wte_trip_reviews_license( $array ) {
	$settings    = get_option( 'wp_travel_engine_license' );
	$license_key = isset( $settings['wte_trip_reviews_license_key'] ) ? esc_attr( $settings['wte_trip_reviews_license_key'] ) : '';

	$array[] =
		array(
			'version' => WTE_TRIP_REVIEW_VERSION,       // current version number
			'license' => $license_key,  // license key (used get_option above to retrieve from DB)
			'item_id' => WTE_TRIP_REVIEW_ITEM_ID,   // id of this product in EDD
			'author'  => 'WP Travel Engine',  // author of this plugin
			'url'     => home_url(),
		);
	return $array;
}

$wp_travel_engine = get_option( 'wp_travel_engine_license' );
$license_status   = isset( $wp_travel_engine['wte_trip_reviews_license_status'] ) ? esc_attr( $wp_travel_engine['wte_trip_reviews_license_status'] ) : '';

if ( isset( $license_status ) && $license_status == 'valid' ) {
	add_filter( 'wp_travel_engine_licenses', 'wte_trip_reviews_license' );
}
