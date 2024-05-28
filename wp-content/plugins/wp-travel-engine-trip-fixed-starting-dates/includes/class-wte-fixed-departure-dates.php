<?php
use RRule\RSet;
use RRule\RRule;
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wptravelengine.com/
 * @since      1.0.0
 *
 * @package    WTE_Fixed_Starting_Dates
 * @subpackage WTE_Fixed_Starting_Dates/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WTE_Fixed_Starting_Dates
 * @subpackage WTE_Fixed_Starting_Dates/includes
 * @author     wptravelengine <test@test.com>
 */
class WTE_Fixed_Starting_Dates {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WTE_Fixed_Starting_Dates_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'wte-fixed-departure-dates';
		$this->version     = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->init_shortcodes();

		$this->init_hooks();
	}

	private function init_hooks() {
		/**
		 * Rest field for dates.
		 */
		add_filter(
			'wte_rest_fields__trip-packages',
			function( $fields ) {
				$fields['package-dates'] = array(
					'type'         => 'array',
					'schema'       => array(
						'items' => array(
							'type'       => 'object',
							'properties' => array(
								'dtstart'           => array(
									'type' => 'string',
								),
								'dtend'             => array(
									'type' => 'string',
								),
								'seats'             => array(
									'type' => 'string',
								),
								'availability_type' => array(
									'type' => 'string',
								),
								'rrule'             => array(
									'type'       => 'object',
									'properties' => array(
										'enable'      => array(
											'type' => 'boolean',
										),
										'r_dtstart'   => array(
											'type' => 'string',
										),
										'r_frequency' => array(
											'type' => 'string',
										),
										'r_weekdays'  => array(
											'type'   => 'array',
											'schema' => array(
												'items' => array(
													'type' => 'string',
												),
											),
										),
										'r_until'     => array(
											'type'    => 'string',
											'default' => '',
										),
										'r_months'    => array(
											'type'    => 'array',
											'schema'  => array(
												'items' => array(
													'type' => 'number',
												),
											),
											'default' => array(),
										),
										'r_count'     => array(
											'type' => 'number',
										),
									),
								),
							),
						),
					),
					'get_callback' => function( $prepared, $field ) {
						if ( defined( 'WTE_FIXED_DEPARTURE_FILE_PATH' ) && file_exists( WTE_FIXED_DEPARTURE_FILE_PATH ) ) {
							$dates = get_post_meta( $prepared['id'], $field, true );
							$response = array();
							if ( is_array( $dates ) ) {
								foreach ( $dates as $key => $date ) {
									$date['is_recurring'] = isset( $date['is_recurring'] ) && $date['is_recurring'];
									$response[ $key ] = $date;
								}
							}
							return $response;
						}
						return array();
					},
				);
				return $fields;
			}
		);

		add_filter(
			'wte_locate_template',
			function( $template, $template_name ) {
				if ( 'script-templates/trip-edit/tab-pricing/dates/tmpl-wte-package-dates.php' === $template_name ) {
					$template = plugin_dir_path( WTE_FIXED_DEPARTURE_FILE_PATH ) . 'templates/script-templates/trip-edit/tab-pricing/tmpl-wte-package-dates.php';
				}
				return $template;
			},
			11,
			2
		);

		add_filter(
			'trip_edit_tab_pricing_and_dates_tab_content',
			function( array $tab_contents ) {
				$tab_contents[15] = array(
					'content_id'       => 'date-settings',
					'content_callback' => function() {
						require_once plugin_dir_path( WTE_FIXED_DEPARTURE_FILE_PATH ) . 'admin/trip/edit/tab-pricing/tab-pricing__settings.php';
					},
				);
				return $tab_contents;
			}
		);

		// Save Package dates.
		// add_action( 'save_trip_package', array( $this, 'save_trip_package' ), 10, 3 );

		add_filter(
			'trip_card_fixed_departure_dates',
			function( $trip_id ) {
				$settings = get_option( 'wp_travel_engine_settings', array() );
				if ( isset( $settings['departure']['hide_availability_section'] ) && 'yes' === $settings['departure']['hide_availability_section'] ) {
					return false;
				}
				$trip_version = get_post_meta( $trip_id, 'trip_version', 'true' );
				if ( version_compare( $trip_version, '2.0.0', '>=' ) && version_compare( WP_TRAVEL_ENGINE_VERSION, '5.0.0', '>=' ) ) {
					return WTE_Fixed_Starting_Dates_Functions::get_fsds_by_trip_id( $trip_id );
				}
				return wp_travel_engine_get_fixed_departure_dates( $trip_id );
			}
		);

		add_action(
			'trip_card_fixed_departure_dates_content',
			function( $fsds, $trip_id, $dates_layout = 'months_list' ) {
				echo '<div class="category-trip-aval-time">';
				switch ( $dates_layout ) {
					case 'months_list':
						$available_months = array_map(
							function( $fsd ) {
								return date_i18n( 'n', strtotime( $fsd['start_date'] ) );
							},
							$fsds
						);
						$available_months = array_flip( $available_months );

						if ( empty( $available_months ) ) {
							echo '<ul class="category-available-months">';
							foreach ( range( 1, 12 ) as $month_number ) :
								echo '<li>' . date_i18n( 'n-M', strtotime( "2021-{$month_number}-01" ) ) . '</li>';
							endforeach;
							echo '</ul>';
							break;
						}

						$availability_txt     = ! empty( $available_months ) && is_array( $available_months ) ? __( 'Available in the following months:', 'wte-fixed-departure-dates' ) : __( 'Available through out the year:', 'wte-fixed-departure-dates' );
						$available_throughout = apply_filters( 'wte_available_throughout_txt', $availability_txt );

						echo '<div class="category-trip-avl-tip-inner-wrap">';
						echo '<span class="category-available-trip-text"> ' . esc_html( $available_throughout ) . '</span>';
						$months_list = '';
						echo '<ul class="category-available-months">';
						foreach ( range( 1, 12 ) as $month_number ) {
							isset( $available_months[ $month_number ] ) ? printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( get_the_permalink( $trip_id ) ) . '?month=' . esc_html( $available_months[ $month_number ] ) . '#wte-fixed-departure-dates', date_i18n( 'M', strtotime( "2021-{$month_number}-01" ) ) ) : printf( '<li><a href="#" class="disabled">%1$s</a></li>', date_i18n( 'M', strtotime( "2021-{$month_number}-01" ) ) );
						}
						echo '</ul>';
						echo '</div>';
						break;
					case 'dates_list':
						$settings = get_option( 'wp_travel_engine_settings', true );

						$list_count = isset( $settings['trip_dates']['number'] ) ? (int) $settings['trip_dates']['number'] : 3;
						$icon       = '<i><svg xmlns="http://www.w3.org/2000/svg" width="17.332" height="15.61" viewBox="0 0 17.332 15.61"><g id="Group_773" data-name="Group 773" transform="translate(283.072 34.13)"><path id="Path_23383" data-name="Path 23383" d="M-283.057-26.176h.1c.466,0,.931,0,1.4,0,.084,0,.108-.024.1-.106-.006-.156,0-.313,0-.469a5.348,5.348,0,0,1,.066-.675,5.726,5.726,0,0,1,.162-.812,5.1,5.1,0,0,1,.17-.57,9.17,9.17,0,0,1,.383-.946,10.522,10.522,0,0,1,.573-.96c.109-.169.267-.307.371-.479a3.517,3.517,0,0,1,.5-.564,6.869,6.869,0,0,1,1.136-.97,9.538,9.538,0,0,1,.933-.557,7.427,7.427,0,0,1,1.631-.608c.284-.074.577-.11.867-.162a7.583,7.583,0,0,1,1.49-.072c.178,0,.356.053.534.062a2.673,2.673,0,0,1,.523.083c.147.038.3.056.445.1.255.07.511.138.759.228a6.434,6.434,0,0,1,1.22.569c.288.179.571.366.851.556a2.341,2.341,0,0,1,.319.259c.3.291.589.592.888.882a4.993,4.993,0,0,1,.64.85,6.611,6.611,0,0,1,.71,1.367c.065.175.121.352.178.53s.118.348.158.526c.054.242.09.487.133.731.024.14.045.281.067.422a.69.69,0,0,1,.008.1c0,.244.005.488,0,.731s-.015.5-.04.745a4.775,4.775,0,0,1-.095.5c-.04.191-.072.385-.128.572-.094.312-.191.625-.313.926a7.445,7.445,0,0,1-.43.9c-.173.3-.38.584-.579.87a8.045,8.045,0,0,1-1.2,1.26,5.842,5.842,0,0,1-.975.687,8.607,8.607,0,0,1-1.083.552,11.214,11.214,0,0,1-1.087.36c-.19.058-.386.1-.58.137-.121.025-.245.037-.368.052a12.316,12.316,0,0,1-1.57.034,3.994,3.994,0,0,1-.553-.065c-.166-.024-.33-.053-.5-.082a1.745,1.745,0,0,1-.21-.043c-.339-.1-.684-.189-1.013-.317a7,7,0,0,1-1.335-.673c-.2-.136-.417-.263-.609-.415a6.9,6.9,0,0,1-.566-.517.488.488,0,0,1-.128-.331.935.935,0,0,1,.1-.457.465.465,0,0,1,.3-.223.987.987,0,0,1,.478-.059.318.318,0,0,1,.139.073c.239.185.469.381.713.559a5.9,5.9,0,0,0,1.444.766,5.073,5.073,0,0,0,.484.169c.24.062.485.1.727.154a1.805,1.805,0,0,0,.2.037c.173.015.346.033.52.036.3.006.6.01.9,0a3.421,3.421,0,0,0,.562-.068c.337-.069.676-.139,1-.239a6.571,6.571,0,0,0,.783-.32,5.854,5.854,0,0,0,1.08-.663,5.389,5.389,0,0,0,.588-.533,8.013,8.013,0,0,0,.675-.738,5.518,5.518,0,0,0,.749-1.274,9.733,9.733,0,0,0,.366-1.107,4.926,4.926,0,0,0,.142-.833c.025-.269.008-.542.014-.814a4.716,4.716,0,0,0-.07-.815,5.8,5.8,0,0,0-.281-1.12,5.311,5.311,0,0,0-.548-1.147,9.019,9.019,0,0,0-.645-.914,9.267,9.267,0,0,0-.824-.788,3.354,3.354,0,0,0-.425-.321,5.664,5.664,0,0,0-1.048-.581c-.244-.093-.484-.2-.732-.275a6.877,6.877,0,0,0-.688-.161c-.212-.043-.427-.074-.641-.109a.528.528,0,0,0-.084,0c-.169,0-.338,0-.506,0a5.882,5.882,0,0,0-1.177.1,6.79,6.79,0,0,0-1.016.274,6.575,6.575,0,0,0-1.627.856,6.252,6.252,0,0,0-1.032.948,6.855,6.855,0,0,0-.644.847,4.657,4.657,0,0,0-.519,1.017c-.112.323-.227.647-.307.979a3.45,3.45,0,0,0-.13.91,4.4,4.4,0,0,1-.036.529c-.008.086.026.1.106.1.463,0,.925,0,1.388,0a.122.122,0,0,1,.08.028c.009.009-.005.051-.019.072q-.28.415-.563.827c-.162.236-.33.468-.489.705-.118.175-.222.359-.339.535-.1.144-.2.281-.3.423-.142.2-.282.41-.423.615-.016.023-.031.047-.048.069-.062.084-.086.083-.142,0-.166-.249-.332-.5-.5-.746-.3-.44-.6-.878-.9-1.318q-.358-.525-.714-1.051c-.031-.045-.063-.09-.094-.134Z" transform="translate(0 0)"/><path id="Path_23384" data-name="Path 23384" d="M150.612,112.52c0,.655,0,1.31,0,1.966a.216.216,0,0,0,.087.178,4.484,4.484,0,0,1,.358.346.227.227,0,0,0,.186.087q1.616,0,3.233,0a.659.659,0,0,1,.622.4.743.743,0,0,1-.516,1.074,1.361,1.361,0,0,1-.323.038q-1.507,0-3.013,0a.248.248,0,0,0-.216.109,1.509,1.509,0,0,1-.765.511,1.444,1.444,0,0,1-1.256-2.555.218.218,0,0,0,.09-.207q0-1.916,0-3.831a.784.784,0,0,1,.741-.732.742.742,0,0,1,.761.544.489.489,0,0,1,.015.127Q150.612,111.547,150.612,112.52Z" transform="translate(-423.686 -141.471)"/></g></svg></i>';
						echo '<div class="next-trip-info">';
						printf( '<div class="fsd-title">%1$s</div>', esc_html__( 'Next Departure', 'wte-fixed-departure-dates' ) );
						echo '<ul class="next-departure-list">';
						foreach ( $fsds as $fsd ) {
							if ( $list_count < 1 ) {
								break;
							}
							printf( '<li><span class="left">%1$s %2$s</span><span class="right">%3$s</span></li>', $icon, wte_get_formated_date( $fsd['start_date'] ), sprintf( __( '%s Available', 'wte-fixed-departure-dates' ), $fsd['seats_left'] ) );
							$list_count--;
						}
						echo '</ul>';
						echo '</div>';
						break;
					default:
						break;
				}
				echo '</div>';
			},
			15,
			3
		);

		add_filter(
			'wptravelengine_search_filter_date',
			function() {
				return array( __CLASS__, 'search_filter_date' );
			}
		);

		add_filter(
			'wte_register_block_types',
			function( $blocks ) {
				if ( isset( $blocks['trip-search']['attributes']['searchFilters']['default'] ) ) {
					$blocks['trip-search']['attributes']['searchFilters']['default']['date'] = array(
						'label'   => __( 'Date', 'wte-fixed-departure-dates' ),
						'default' => __( 'Date', 'wte-fixed-departure-dates' ),
						'show'    => true,
						'order'   => 5,
						'icon'    => 'calendar',
					);
				}
				return $blocks;
			}
		);

		add_action( 'wpte_save_and_continue_additional_meta_data', array( __CLASS__, 'wte_update_trip_packages' ), 10, 2 );
		add_action(
			'save_post_' . WP_TRAVEL_ENGINE_POST_TYPE,
			function( $post_ID, $post, $update = false ) {
				self::wte_update_trip_packages( $post_ID, $_POST );
			},
			10,
			3
		);

		add_action( 'admin_init', array( 'WTE_Fixed_Starting_Dates_Functions', 'set_min_and_max_date' ) );

		add_action( 'rest_prepare_' . WP_TRAVEL_ENGINE_POST_TYPE, array( __CLASS__, 'filter_rest_data_trip' ), 15, 3 );

		add_filter( 'block_type_metadata', array( $this, 'block_type_metadata' ) );

		add_action(
			'wp_head',
			function () {
				if ( is_front_page() ) {
					?>
				<style type="text/css">
					.trip-search form .custom-select ul.list .option:first-of-type{
						display: none;
					}
					.trip-search form .wte-advanced-search-wrapper-nice-select .nice-select.trip-date-select {
						display: inline-block;
					}
					.trip-search form .wte-advanced-search-wrapper-nice-select .advanced-search-field .custom-select{
						font-size: 0px;
					}
				</style>
					<?php
				}
			}
		);

	}

	public function block_type_metadata( $metadata ) {

		if ( isset( $metadata['name'] ) && 'wptravelengine/trips' === $metadata['name'] ) {
			$metadata['attributes']['datesLayout'] = array(
				'type'    => 'string',
				'default' => 'months_list',
			);
			$metadata['attributes']['datesCount']  = array(
				'type'    => 'number',
				'default' => 3,
			);
		}
		return $metadata;
	}

	public static function filter_rest_data_trip( $response, $post, $request ) {
		// $settings = get_option( 'wp_travel_engine_settings', array() );

		$dates = apply_filters( 'trip_card_fixed_departure_dates', get_the_ID() );
		if ( is_array( $dates ) ) {
			$dates = array_slice( $dates, 0, 10 );
		} else {
			$dates = array();
		}

		$months = get_post_meta( $post->ID, 'trip_available_months', true );
		$months = explode( ',', $months );

		$months = \WTE_Fixed_Starting_Dates_Functions::generator( $months );

		$_months = array();
		foreach ( $months as $month ) {
			if ( $month instanceof \DateTime ) {
				$_months[] = (int) $month->format( 'm' );
			}
		}

		$response->data['available_times'] = array(
			'dates'  => array_column( $dates, 'start_date' ),
			'months' => array_keys( array_flip( $_months ) ),
		);

		// $response->data->{'available_times'}['type'] = apply_filters( 'trip_card_fixed_departure_dates', get_the_ID() );
		return $response;
	}

	public static function search_filter_date( $args ) {
		// wp_enqueue_style( 'wte-fpickr' );
		// wp_enqueue_script( 'wte-fpickr' );
		// return;
		$id    = wte_uniqid();
		$dates = \WTE_Fixed_Starting_Dates_Functions::get_trips_available_year_months();

		?>
		<div class="wpte-trip__adv-field wpte__select-field">
			<span class="icon">
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 9.5C8.14834 9.5 8.29334 9.45601 8.41668 9.3736C8.54002 9.29119 8.63614 9.17406 8.69291 9.03701C8.74968 8.89997 8.76453 8.74917 8.73559 8.60368C8.70665 8.4582 8.63522 8.32456 8.53033 8.21967C8.42544 8.11478 8.2918 8.04335 8.14632 8.01441C8.00083 7.98547 7.85003 8.00032 7.71299 8.05709C7.57594 8.11386 7.45881 8.20999 7.3764 8.33332C7.29399 8.45666 7.25 8.60166 7.25 8.75C7.25 8.94891 7.32902 9.13968 7.46967 9.28033C7.61032 9.42098 7.80109 9.5 8 9.5ZM11.75 9.5C11.8983 9.5 12.0433 9.45601 12.1667 9.3736C12.29 9.29119 12.3861 9.17406 12.4429 9.03701C12.4997 8.89997 12.5145 8.74917 12.4856 8.60368C12.4566 8.4582 12.3852 8.32456 12.2803 8.21967C12.1754 8.11478 12.0418 8.04335 11.8963 8.01441C11.7508 7.98547 11.6 8.00032 11.463 8.05709C11.3259 8.11386 11.2088 8.20999 11.1264 8.33332C11.044 8.45666 11 8.60166 11 8.75C11 8.94891 11.079 9.13968 11.2197 9.28033C11.3603 9.42098 11.5511 9.5 11.75 9.5ZM8 12.5C8.14834 12.5 8.29334 12.456 8.41668 12.3736C8.54002 12.2912 8.63614 12.1741 8.69291 12.037C8.74968 11.9 8.76453 11.7492 8.73559 11.6037C8.70665 11.4582 8.63522 11.3246 8.53033 11.2197C8.42544 11.1148 8.2918 11.0434 8.14632 11.0144C8.00083 10.9855 7.85003 11.0003 7.71299 11.0571C7.57594 11.1139 7.45881 11.21 7.3764 11.3333C7.29399 11.4567 7.25 11.6017 7.25 11.75C7.25 11.9489 7.32902 12.1397 7.46967 12.2803C7.61032 12.421 7.80109 12.5 8 12.5ZM11.75 12.5C11.8983 12.5 12.0433 12.456 12.1667 12.3736C12.29 12.2912 12.3861 12.1741 12.4429 12.037C12.4997 11.9 12.5145 11.7492 12.4856 11.6037C12.4566 11.4582 12.3852 11.3246 12.2803 11.2197C12.1754 11.1148 12.0418 11.0434 11.8963 11.0144C11.7508 10.9855 11.6 11.0003 11.463 11.0571C11.3259 11.1139 11.2088 11.21 11.1264 11.3333C11.044 11.4567 11 11.6017 11 11.75C11 11.9489 11.079 12.1397 11.2197 12.2803C11.3603 12.421 11.5511 12.5 11.75 12.5ZM4.25 9.5C4.39834 9.5 4.54334 9.45601 4.66668 9.3736C4.79001 9.29119 4.88614 9.17406 4.94291 9.03701C4.99968 8.89997 5.01453 8.74917 4.98559 8.60368C4.95665 8.4582 4.88522 8.32456 4.78033 8.21967C4.67544 8.11478 4.5418 8.04335 4.39632 8.01441C4.25083 7.98547 4.10003 8.00032 3.96299 8.05709C3.82594 8.11386 3.70881 8.20999 3.6264 8.33332C3.54399 8.45666 3.5 8.60166 3.5 8.75C3.5 8.94891 3.57902 9.13968 3.71967 9.28033C3.86032 9.42098 4.05109 9.5 4.25 9.5ZM13.25 2H12.5V1.25C12.5 1.05109 12.421 0.860322 12.2803 0.71967C12.1397 0.579018 11.9489 0.5 11.75 0.5C11.5511 0.5 11.3603 0.579018 11.2197 0.71967C11.079 0.860322 11 1.05109 11 1.25V2H5V1.25C5 1.05109 4.92098 0.860322 4.78033 0.71967C4.63968 0.579018 4.44891 0.5 4.25 0.5C4.05109 0.5 3.86032 0.579018 3.71967 0.71967C3.57902 0.860322 3.5 1.05109 3.5 1.25V2H2.75C2.15326 2 1.58097 2.23705 1.15901 2.65901C0.737053 3.08097 0.5 3.65326 0.5 4.25V13.25C0.5 13.8467 0.737053 14.419 1.15901 14.841C1.58097 15.2629 2.15326 15.5 2.75 15.5H13.25C13.8467 15.5 14.419 15.2629 14.841 14.841C15.2629 14.419 15.5 13.8467 15.5 13.25V4.25C15.5 3.65326 15.2629 3.08097 14.841 2.65901C14.419 2.23705 13.8467 2 13.25 2ZM14 13.25C14 13.4489 13.921 13.6397 13.7803 13.7803C13.6397 13.921 13.4489 14 13.25 14H2.75C2.55109 14 2.36032 13.921 2.21967 13.7803C2.07902 13.6397 2 13.4489 2 13.25V6.5H14V13.25ZM14 5H2V4.25C2 4.05109 2.07902 3.86032 2.21967 3.71967C2.36032 3.57902 2.55109 3.5 2.75 3.5H13.25C13.4489 3.5 13.6397 3.57902 13.7803 3.71967C13.921 3.86032 14 4.05109 14 4.25V5ZM4.25 12.5C4.39834 12.5 4.54334 12.456 4.66668 12.3736C4.79001 12.2912 4.88614 12.1741 4.94291 12.037C4.99968 11.9 5.01453 11.7492 4.98559 11.6037C4.95665 11.4582 4.88522 11.3246 4.78033 11.2197C4.67544 11.1148 4.5418 11.0434 4.39632 11.0144C4.25083 10.9855 4.10003 11.0003 3.96299 11.0571C3.82594 11.1139 3.70881 11.21 3.6264 11.3333C3.54399 11.4567 3.5 11.6017 3.5 11.75C3.5 11.9489 3.57902 12.1397 3.71967 12.2803C3.86032 12.421 4.05109 12.5 4.25 12.5Z" fill="#2183DF" /></svg>
			</span>
			<input type="text" class="wpte__input" placeholder="<?php echo esc_attr__( 'Choose Date', 'wte-fixed-departure-dates' ); ?>" value="<?php echo esc_attr( $args['label'] ); ?>" id="<?php echo esc_attr( $id ); ?>">
			<input type="hidden" class="wpte__input-value" name="trip-date-select" value="" id="<?php echo esc_attr( $id ); ?>">
			<div class="wpte__select-options">
				<ul>
				<?php
				foreach ( $dates as $date ) :
					if ( new \DateTime() > $date ) {
						continue;
					}
					printf( '<li data-value="%1$s" data-label="%2$s"><span>%2$s</span></li>', $date->format( 'Y-m' ), $date->format( 'F, Y' ) );
					endforeach;
				?>
				</ul>
			</div>
		</div>
		<?php
	}

	/**
	 *
	 * Save package Dates.
	 *
	 * @since 2.3.1
	 */
	public static function wte_update_trip_packages( $trip_id, $posted_data ) {

		$packages_ids = isset( $posted_data['packages_ids'] ) ? $posted_data['packages_ids'] : array();

		if ( empty( $packages_ids ) ) {
			return;
		}

		$packages_rrule = array();
		$rset           = new RSet();
		foreach ( $packages_ids as $index => $package_id ) {
			$dates = isset( $posted_data['dates'] ) ? (array) $posted_data['dates'] : array();

			$_package_dates = array();
			if ( isset( $dates[ $package_id ] ) ) {
				$package_dates   = $dates[ $package_id ];
				$available_dates = array();
				foreach ( $package_dates as $date_key => $date_args ) {
					$date_args = (object) $date_args;
					if ( isset( $date_args->dtstart ) && empty( trim( $date_args->dtstart ) ) ) {
						continue;
					}

					try {
						$date_start        = new \DateTime( $date_args->dtstart );
						$available_dates[] = (int) $date_start->format( 'Ymd' );
						if ( isset( $date_args->is_recurring ) && 1 === (int) $date_args->is_recurring ) {
							$freq    = isset( $date_args->rrule['r_frequency'] ) ? $date_args->rrule['r_frequency'] : 'DAILY';
							$count   = isset( $date_args->rrule['r_count'] ) ? (int) $date_args->rrule['r_count'] : 10;
							$dtstart = $date_args->dtstart;

							$rrule_args = array(
								'FREQ'    => $freq,
								'DTSTART' => $dtstart,
								'COUNT'   => $count,
							);

							if ( ! empty( $date_args->rrule['r_until'] ) ) {
								unset( $rrule_args['COUNT'] );
								$rrule_args['UNTIL'] = $date_args->rrule['r_until'];
							}

							if ( 'MONTHLY' === $freq && ! empty( $date_args->rrule['r_months'] ) ) {
								$rrule_args['BYMONTH'] = array_map(
									function( $month ) {
										return (int) $month;
									},
									$date_args->rrule['r_months']
								);
							}

							if ( 'WEEKLY' === $freq && ! empty( $date_args->rrule['r_weekdays'] ) ) {
								$rrule_args['BYDAY'] = array_values( $date_args->rrule['r_weekdays'] );
							}

							$rset->addRRule( $rrule_args );
						} else {
							$rset->addDate( $date_args->dtstart );
						}
						$_package_dates[ $date_start->format( 'Ymd' ) ] = (array) $date_args;
					} catch ( \Exception $e ) {
						if ( wp_doing_ajax() ) {
							wp_send_json_error( new WP_Error( 'WTE_INVALID_DATA', $e->getMessage() ) );
						} else {
							wp_die( new WP_Error( 'WTE_INVALID_DATA', $e->getMessage() ) );
						}
						die;
						error_log( print_r( $e, true ) ); // phpcs:ignore
					}
				}

				update_post_meta( (int) $package_id, 'package-dates', $_package_dates );
			} else {
				update_post_meta( (int) $package_id, 'package-dates', array() );
			} // id dates has package id.
		} // End packages_ids Loop.

		$today = new \DateTime();
		// $occurrences = $rset->getOccurrencesBetween( $today->format( 'Y-m-d' ), null );
		$available_months = array();
		foreach ( $rset as $occurrence ) {
			if ( $occurrence < $today ) {
				continue;
			}
			$key                      = $occurrence->format( 'ym' );
			$available_months[ $key ] = $key;
		}
		update_post_meta( $trip_id, 'trip_available_months', implode( ',', $available_months ) );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WTE_Fixed_Starting_Dates_Loader. Orchestrates the hooks of the plugin.
	 * - WTE_Fixed_Starting_Dates_i18n. Defines internationalization functionality.
	 * - WTE_Fixed_Starting_Dates_Admin. Defines all hooks for the admin area.
	 * - WTE_Fixed_Starting_Dates_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wte-fixed-departure-dates-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wte-fixed-departure-dates-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wte-fixed-departure-dates-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wte-fixed-departure-dates-public.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wte-fixed-departure-dates-meta-tabs.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wte-fixed-departure-dates-meta.php';

		/**
		 * The class responsible for defining functions.
		 */
		require WTE_FIXED_DEPARTURE_BASE_PATH . '/includes/class-wte-fixed-departure-dates-functions.php';

		/**
		 * The class responsible for updating the add-on from EDD.
		 */
		require WTE_FIXED_DEPARTURE_BASE_PATH . '/updater/wte-fixed-departure-dates-updater.php';

		$this->loader = new WTE_Fixed_Starting_Dates_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WTE_Fixed_Starting_Dates_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WTE_Fixed_Starting_Dates_i18n();

		$this->loader->add_action( 'init', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new WTE_Fixed_Starting_Dates_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_notices', $plugin_admin, 'check_dependency' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts', 9999999 );
		$this->loader->add_action( 'admin_head', $plugin_admin, 'fixed_departure_dates_html_templates' );
		$this->loader->add_action( 'wte_fixed_departure_dates_settings', $plugin_admin, 'fixed_departure_dates_settings' );
		$this->loader->add_action( 'wp_travel_engine_starting_dates_form', $plugin_admin, 'wp_travel_engine_starting_dates_form' );
		$this->loader->add_filter( 'wp_travel_engine_admin_trip_meta_tabs', $plugin_admin, 'add_fsd_availability_tab' );
		$this->loader->add_action( 'wpte_save_and_continue_additional_meta_data', $plugin_admin, 'save_fsd_meta_data', 10, 2 );
		$this->loader->add_action( 'save_post_booking', $plugin_admin, 'save_wte_fixed_starting_dates_meta', 11, 3 );
		$this->loader->add_action( 'save_post_booking', $plugin_admin, 'WTE_Fixed_Starting_Dates_alter_seat_available', 11, 3 );
		// Global Settings array
		$this->loader->add_action( 'wpte_get_global_extensions_tab', $plugin_admin, 'wte_fsd_extension_settings' );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'wte_fsd_refresh_fsd_dts' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new WTE_Fixed_Starting_Dates_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wte_departure_date_dropdown', $plugin_public, 'wte_departure_date_dropdown' );

		$this->loader->add_action( 'wp_travel_engine_after_booking_process_completed', $plugin_public, 'update_fsd_space_left_count' );

		$this->loader->add_action( 'wp_travel_engine_before_trip_add_to_cart', $plugin_public, 'cart_pricing_check_seats_available', 10, 6 );

		$this->loader->add_filter( 'wte_is_fsd_active_available', $plugin_public, 'wte_is_fsd_active_available_callback', 10, 2 );

		$this->loader->add_action( 'wte_single_trip_tab_content_dates', $plugin_public, 'display_fsd_tab_content', 10, 4 );

	}

	/**
	 * Init shortcodes.
	 *
	 * @since    1.0.0
	 */
	public function init_shortcodes() {
		/**
		 * The class responsible for defining shortcode for .
		 */
		require WTE_FIXED_DEPARTURE_BASE_PATH . '/includes/class-wte-fixed-departure-dates-shortcodes.php';

		$plugin_shortcode = new WTE_Fixed_Starting_Dates_Shortcodes();
		$plugin_shortcode->init();
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WTE_Fixed_Starting_Dates_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
