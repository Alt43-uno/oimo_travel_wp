<?php
/**
 * The plugin bootstrap files
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wptravelengine.com/
 * @since             1.0.0
 * @package
 *
 * @wordpress-plugin
 * Plugin Name:       WP Travel Engine - Trip Reviews
 * Plugin URI:        https://wptravelengine.com/
 * Description:       An extension for WP Travel Engine plugin to add trip review with ratings.
 * Version:           2.1.2
 * Author:            WP Travel Engine
 * Author URI:        https://wptravelengine.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wte-trip-review
 * Domain Path:       /languages
 * WTE requires at least: 4.3.0
 * WTE tested up to: 5.5
 * WTE: 2107:wte_trip_reviews_license_key
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WTE_TRIP_REVIEW_FILE_URL', plugins_url( '', __FILE__ ) );
define( 'WTE_TRIP_REVIEW_VERSION', '2.1.2' );
define( 'WTE_TRIP_REVIEW_BASE_PATH', dirname( __FILE__ ) );
define( 'WTE_TRIP_REVIEW_FILE_PATH', __FILE__ );
defined( 'WTE_TRIP_REVIEW_REQUIRES_AT_LEAST' ) || define( 'WTE_TRIP_REVIEW_REQUIRES_AT_LEAST', '4.3.0' );

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function wte_trip_review_load_textdomain() {
	load_plugin_textdomain( 'wte-trip-review', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/**
 * Check if all plugin requirements are met.
 *
 * @since 1.0.0
 *
 * @return bool True if requirements are met, otherwise false.
 */
function wte_tr_meets_requirements() {
	return class_exists( 'WP_Travel_Engine' ) && defined( 'WP_TRAVEL_ENGINE_VERSION' ) && version_compare( WP_TRAVEL_ENGINE_VERSION, '4.0.0', '>=' );
}

/**
 * Function to check if parent plugin is enabled
 */
function wte_tr_show_message_for_parent_plugin() {
	if ( ! wte_tr_meets_requirements() ) {
		echo '<div class="notice notice-error is-dismissable">';
		echo wp_kses_post( '<p><strong>WP Travel Engine - Trip Reviews</strong> requires the <a href="https://wptravelengine.com" target="__blank">WP Travel Engine</a> <b>version - 4.0.0 or later</b> to work. Please install and activate the latest WP Travel Engine plugin first. <b>WP Travel Engine - Trip Reviews</b> will be deactivated now.</p>' );
		echo '</div>';
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}
}

register_activation_hook( __FILE__, 'wte_trip_reviews_activation_function' );
/**
 *
 * Activation hook
 */
function wte_trip_reviews_activation_function() {
	$wp_travel_engine_option_settings = get_option( 'wp_travel_engine_settings', array() );
	if ( function_exists( 'wte_get_default_settings_tab' ) ) {
		$wp_travel_engine_option_settings['trip_tabs'] = wte_get_default_settings_tab();
	}
	$key = max( array_keys( $wp_travel_engine_option_settings['trip_tabs']['id'] ) );
	$key++;
	$default_tabs = array(
		'trip_tabs' =>
		  array(
			  'name'  => array(
				  $key => 'Review',
			  ),

			  'field' => array(
				  $key => 'review',
			  ),
			  'id'    => array(
				  $key => $key,
			  ),
		  ),
	);

	if ( ! in_array( 'review', $wp_travel_engine_option_settings['trip_tabs']['field'] ) ) {
		if ( isset( $wp_travel_engine_option_settings['trip_tabs'] ) ) {
			$default_tab_settings = array_replace_recursive( $wp_travel_engine_option_settings, $default_tabs );
			update_option( 'wp_travel_engine_settings', $default_tab_settings );
		}
	}

	// Save email from author url field to comment meta

	$flag = get_option( '_wte_trip_review_flag', false );

	if ( '1' == $flag && version_compare( WTE_TRIP_REVIEW_VERSION, '1.0.5', '>=' ) ) {
		return;
	} else {
		$arg   = array(
			'post_type'      => 'trip',
			'posts_per_page' => -1,
		);
		$query = new WP_Query( $arg );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$args     = array(
					'post_id' => get_the_ID(), // use post_id, not post_ID
				);
				$comments = get_comments( $args );
				foreach ( $comments as $comment ) :
					if ( isset( $comment->comment_author_url ) && $comment->comment_author_url != '' ) {
						$img_id     = str_replace( 'http://', '', $comment->comment_author_url );
						$attachment = wp_get_attachment_image( $img_id, 'thumbnail' );

						if ( isset( $attachment ) && $attachment != '' ) :
							$photo = absint( $img_id );
							update_comment_meta( $comment->comment_ID, 'photo', $photo );

							$commentarr                       = array();
							$commentarr['comment_ID']         = $comment->comment_ID;
							$commentarr['comment_author_url'] = '';

							wp_update_comment( $commentarr );
						endif;
					}
				endforeach;
			}
			wp_reset_postdata();
			update_option( '_wte_trip_review_flag', 1 );
		}
	}
}

function wpte_review_trip_post_select( $select_id, $post_type, $selected = 0 ) {
	$post_type_object = get_post_type_object( $post_type );
	$label            = $post_type_object->label;
	echo '<label class="wpte-field-label">Select ' . esc_html( $label ) . ':<span class="required">*</span></label>';
	$posts = get_posts(
		array(
			'post_type'        => $post_type,
			'post_status'      => 'publish',
			'suppress_filters' => false,
			'posts_per_page'   => -1,
		)
	);
	echo '<select class="wpte-enhanced-select" name="' . esc_attr( $select_id ) . '" id="' . esc_attr( $select_id ) . '">';
	echo '<option value="">Select ' . esc_html( $label ) . ' </option>';
	foreach ( $posts as $post ) {
		echo '<option value="', (int) $post->ID, '"', ( $selected == $post->ID ) ? ' selected="selected"' : '', '>', esc_html( $post->post_title ), '</option>';
	}
	echo '</select>';
}

/**
 * Plugin runs on plugins loaded.
 */
add_action(
	'plugins_loaded',
	function() {
		// If requirements matched.
		if ( ! defined( 'WP_TRAVEL_ENGINE_VERSION' ) || version_compare( WP_TRAVEL_ENGINE_VERSION, WTE_TRIP_REVIEW_REQUIRES_AT_LEAST, '<' ) ) {
			add_action(
				'admin_notices',
				function() {
					echo wp_kses_post(
						sprintf(
							'<div class="error"><p>'
							// translators: 1. WTE Extension Name 2. Link to WTE Plugin.
							. sprintf( __( '%1$s requires the %2$s plugin to work. Please install and activate the latest <strong>%2$s</strong> plugin first.', 'wte-trip-review' ), '<strong>WP Travel Engine - Trip Reviews</strong>', '<a href="https://wordpress.org/plugins/wp-travel-engine" target="__blank">WP Travel Engine</a>' )
							. '</p></div>'
						)
					);
				}
			);
		} else {
			add_action( 'init', 'wte_trip_review_load_textdomain' );

			// Loading libraries before init
			require WTE_TRIP_REVIEW_BASE_PATH . '/includes/class-library.php';

			// Loading libraries before init
			require WTE_TRIP_REVIEW_BASE_PATH . '/includes/helper-functions.php';

			require WTE_TRIP_REVIEW_BASE_PATH . '/updater/wte-trip-review-updater.php';

			// loading main file of the plugin.
			require WTE_TRIP_REVIEW_BASE_PATH . '/init.php';
		}
	}
);
