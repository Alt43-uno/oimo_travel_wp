<?php
	/**
	 * Settings section of the plugin.
	 *
	 * Maintain a list of functions that are used for settings purposes of the plugin
	 *
	 * @package    WTE_Fixed_Starting_Dates
	 * @subpackage WTE_Fixed_Starting_Dates/includes
	 * @author
	 */

use WPTravelEngine\Packages;
use RRule\RSet;
class WTE_Fixed_Starting_Dates_Shortcodes {

	/**
	 * Get sorted fixed departure dates array.
	 *
	 * @param [type] $trip_id
	 * @return array
	 */
	public function get_sorted_fsdates( $trip_id ) {

		$WTE_Fixed_Starting_Dates_setting = get_post_meta( $trip_id, 'WTE_Fixed_Starting_Dates_setting', true );

		if ( ! isset( $WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'] ) || empty( $WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'] ) ) {
			return;
		}

		$start_dates = $WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'];
		asort( $start_dates );

		$sortable_ids      = array_keys( $start_dates );
		$sortable_settings = array();

		foreach ( $sortable_ids as $sortable_id ) {
			$sortable_obj     = new stdClass();
			$sortable_obj->id = $sortable_id;
			array_push(
				$sortable_settings,
				$sortable_obj
			);
		}

		return $sortable_settings;
	}

	/**
	 * Initialize.
	 *
	 * @since    1.0.0
	 */
	public function init() {
		add_shortcode( 'WTE_Fixed_Starting_Dates', array( $this, 'WTE_Fixed_Starting_Dates_shortcodes_callback' ) );
		add_shortcode( 'WTE_TRIPS_FIXED_STARTING_DATES', array( $this, 'wte_fixed_starting_dates_all' ) );
		add_action( 'wp_ajax_filter_departure_dates', array( $this, 'wte_ajax_callback' ) );
		add_action( 'wp_ajax_nopriv_filter_departure_dates', array( $this, 'wte_ajax_callback' ) );
		add_action( 'Wte_Fixed_Starting_Dates_Action', array( $this, 'Wte_Fixed_Starting_Dates_Action' ) );
		add_action( 'wp_ajax_load_more_dates', array( $this, 'wte_load_more_dates_ajax_callback' ) );
		add_action( 'wp_ajax_nopriv_load_more_dates', array( $this, 'wte_load_more_dates_ajax_callback' ) );
	}

	/**
	 * Ajax callback for filtering.
	 */
	public function wte_ajax_callback() {
		$defaults = array(
			'year'  => gmdate( 'Y' ),
			'month' => gmdate( 'n' ),
			'trip'  => 0,
		);

		if ( ! isset( $_REQUEST['_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_REQUEST['_nonce'] ), 'wte-fsd' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			wp_send_json_error( new WP_Error( 'INVALID_NONCE', __( 'Invalid nonce or noce is not present.', 'wte-fixed-departure-dates' ), array() ), 403 );
			wp_die();
		}
		$request = wp_parse_args( $_REQUEST, $defaults );

		if ( ! isset( $request['type'] ) || 'advanced' !== $request['type'] ) {

			if ( isset( $request['value'] ) && ! empty( trim( $request['value'] ) ) ) {
				$request['trip'] = $request['value'];
			}

			if ( isset( $request['val'] ) && ! empty( trim( $request['val'] ) ) ) {
				$val = explode( '-', trim( $request['val'] ) );

				$request['year']  = $val[0];
				$request['month'] = $val[1];
			} else {
				$request['year']  = date( 'Y' );
				$request['month'] = '';
			}
		}

		if ( ! $request['trip'] ) {
			wp_send_json_error( new WP_Error( 'INVALID_TRIP_ID', __( 'Invalid Trip ID', 'wte-fixed-departure-dates' ), array() ), 403 );
			wp_die();
		}

		$response = '';

		$sorted_fsd = self::generate_fsds(
			$request['trip'],
			array(
				'year'  => $request['year'],
				'month' => $request['month'],
			)
		);

		ob_start();
		if ( ! empty( $request['type'] ) && 'advanced' === $request['type'] ) {
			wte_fsd_get_template(
				'table-inner-advanced.php',
				array(
					'columns'         => self::get_table_columns(),
					'sorted_by_month' => $this->sort_fsd_by_month(
						array(
							$request['trip'] => array(
								'id'    => $request['trip'],
								'dates' => $sorted_fsd,
							),
						)
					),
				)
			);
			wp_send_json_success( array( 'html' => ob_get_clean() ) );
		} else {
			wte_fsd_get_template( 'table-inner.php', $sorted_fsd );
			$sortable_settings = $this->get_sorted_fsdates( $request['trip'] );
			$start_date        = array();
			$response          = apply_filters( 'wte_fixed_departure_dates_table_template', ob_get_clean(), $sortable_settings, $start_date );
			echo $response;
		}
		wp_die();
	}

	function wte_date_sort( $a, $b ) {
		return strtotime( $a ) - strtotime( $b );
	}

	/**
	 * Initialize.
	 *
	 * @since    1.0.0
	 */
	public function WTE_Fixed_Starting_Dates_shortcodes_callback( $atts, $content = '' ) {
		ob_start();
		$atts = shortcode_atts(
			array(
				'id' => '',
			),
			$atts,
			'WTE_Fixed_Starting_Dates'
		);
		global $post;

		$post_id = 0;

		if ( ! empty( $atts['id'] ) && 'trip' === get_post( $atts['id'] )->post_type ) {
			$post_id = $atts['id'];
		} else {
			$post_id = 'trip' === $post->post_type ? $post->ID : $post_id;
		}

		if ( ! $post_id ) {
			return '';
		}

		$args = array(
			'post_id' => $post_id,
		);

		$sortable_settings = $this->get_sorted_fsdates( $post_id );
		$start_date        = array();

		wte_fsd_get_template( 'fsd-table.php', $args );

		$output = ob_get_clean();
		$output = apply_filters( 'wte_fixed_departure_dates_table_template', $output, $sortable_settings, $start_date );

		return $output;
	}

	public static function to_utc( string $date ) {
		$utc = new DateTime( $date, new DateTimeZone( 'UTC' ) );
		return $utc->setTime( 0, 0, 0, 0 );
	}

	public static function generate_fsds( $pid = null, $rrule_filter = array() ) {
		$fsd_functions = new WTE_Fixed_Starting_Dates_Functions();
		if ( ! $pid ) { // generate fsds for all trips.
			$trips = new WP_Query(
				array(
					'post_type'      => 'trip',
					'posts_per_page' => -1,
					'post_status'    => 'publish',
				)
			);

			$sorted_fsd = array();

			while ( $trips->have_posts() ) {
				$trips->the_post();
				$trip_id = get_the_ID();

				$dates = $fsd_functions->get_formated_fsd_dates( $trip_id, $rrule_filter );
				if ( ! empty( $dates ) ) {
					$sorted_fsd[ $trip_id ] = array(
						'id'    => $trip_id,
						'dates' => $dates,
					);
				}
			}
			wp_reset_postdata();

			return $sorted_fsd;
		}

		if ( function_exists( 'wte_get_trip' ) ) {
			$wtetrip = \wte_get_trip( $pid );

			if ( $wtetrip && ! $wtetrip->use_legacy_trip ) {
				// $generated_fsds = \WTE_Fixed_Starting_Dates_Functions::generate_fsds( $pid, $rrule_filter );
				return WTE_Fixed_Starting_Dates_Functions::generate_fsds( $pid, $rrule_filter );
			}
		}

		// For single trip.
		$trip_settings     = get_post_meta( $pid, 'wp_travel_engine_setting', true );
		$booked_seats_meta = get_post_meta( $pid, 'wte_fsd_booked_seats', true );
		$duration          = isset( $trip_settings['trip_duration'] ) && ! empty( $trip_settings['trip_duration'] ) ? absint( $trip_settings['trip_duration'] - 1 ) : false;
		$duration_unit     = isset( $trip_settings['trip_duration_unit'] ) ? $trip_settings['trip_duration_unit'] : 'days';

		$dur_days = isset( $duration ) && 'days' === $duration_unit ? $duration : false;
		$fsds     = array();
		$fsds     = get_post_meta( $pid, 'WTE_Fixed_Starting_Dates_setting', true );

		$enable_multi_pricing = ! empty( $fsds['enable_multiple_pricing_fsd'] );

		if ( empty( $fsds ) ) {
			return;
		}

		$keys = ( isset( $fsds['departure_dates']['sdate'] ) ) ? array_keys( $fsds['departure_dates']['sdate'] ) : array();

		// Booked seats.
		$fsbs      = is_array( $booked_seats_meta ) ? $booked_seats_meta : array();
		$args_set  = array();
		$fsd_dates = array();

		// Cutoff Vars.
		$cutoff_enabled    = ! empty( $trip_settings['trip_cutoff_enable'] ) && 'true' === $trip_settings['trip_cutoff_enable'];
		$trip_cut_off_time = isset( $trip_settings['trip_cut_off_time'] ) && ! empty( trim( $trip_settings['trip_cut_off_time'] ) ) ? $trip_settings['trip_cut_off_time'] : 0;
		$cut_off_unit      = ! empty( $trip_settings['trip_cut_off_unit'] ) ? $trip_settings['trip_cut_off_unit'] : 'days';

		$args = array();

		$rrules_dates = array();

		foreach ( $keys as $key ) {
			$rset           = new RSet();
			$args           = array();
			$start_date_str = $fsds['departure_dates']['sdate'][ $key ];
			$end_date       = isset( $fsds['departure_dates']['edate'][ $key ] ) ? $fsds['departure_dates']['edate'][ $key ] : '';
			$availability   = isset( $fsds['departure_dates']['availability_type'][ $key ] ) ? $fsds['departure_dates']['availability_type'][ $key ] : '';
			$seats_left     = isset( $fsds['departure_dates']['seats_available'][ $key ] ) ? $fsds['departure_dates']['seats_available'][ $key ] : '';
			$booked         = isset( $fsbs[ strtotime( $start_date_str ) ]['booked'] ) ? $fsbs[ strtotime( $start_date_str ) ]['booked'] : 0;

			$today = new DateTime( 'now', new DateTimeZone( 'UTC' ) );
			$today->setTime( 0, 0, 0, 0 );
			$start_date = new DateTime( $start_date_str, new DateTimeZone( 'UTC' ) );

			$year  = $start_date->format( 'Y' );
			$month = $start_date->format( 'n' );
			$day   = $start_date->format( 'j' );

			// Filter Year.
			if ( ! empty( $rrule_filter['year'] ) ) {
				$dtstartyear = $rrule_filter['year'];
				$dtuntilyear = $rrule_filter['year'];
			} else {
				$dtstartyear = $today->format( 'Y' );
				$dtuntilyear = (int) $today->format( 'Y' ) + 2;
			}

			if ( (int) $dtstartyear < $year && $year > $dtuntilyear ) {
				continue;
			}

			// Filter Month.
			if ( isset( $rrule_filter['month'] ) && ! empty( trim( $rrule_filter['month'] ) ) ) {
				$months       = explode( ',', $rrule_filter['month'] );
				$dtstartmonth = min( $months );
				$untilmonth   = max( $months );
			} else {
				$months       = range( 1, 12 );
				$dtstartmonth = $month;
				$untilmonth   = 12;
			}

			// Check if not recurring.
			if ( ! isset( $fsds['departure_dates'][ $key ]['recurring']['enable'] ) ) {
				if ( $start_date < $today ) {
					continue;
				}

				if ( $cutoff_enabled && $trip_cut_off_time ) {
					$departure_date = $today;

					if ( $cut_off_unit === 'days' ) {
						$departure_date = $departure_date->add( new DateInterval( "P{$trip_cut_off_time}D" ) );
					}
					if ( $trip_cut_off_time && ( WTE_Fixed_Starting_Dates_Functions::to_utc( 'now' ) > $departure_date ) ) {
						continue;
					}
				}

				if (
					( ! empty( $rrule_filter['year'] ) && (int) $start_date->format( 'Y' ) !== (int) $dtuntilyear )
				|| ( (int) $start_date->format( 'Y' ) < (int) $dtstartyear )
				) {
					continue;
				}

				if ( ( (int) $start_date->format( 'Y' ) ) > (int) $dtuntilyear ) {
					break;
				}

				if ( ! in_array( (int) $start_date->format( 'n' ), $months ) ) {
					continue;
				}

				$fsd_dates[ $start_date->getTimestamp() ] = array(
					'trip_id'      => $pid,
					'content_id'   => $start_date->getTimestamp(),
					'start_date'   => $start_date->format( 'Y-m-d' ),
					'end_date'     => $duration ? $start_date->add( new DateInterval( "P{$duration}D" ) )->format( 'Y-m-d' ) : $start_date->format( 'Y-m-d' ),
					'availability' => $availability,
					'seats_left'   => '' === trim( $seats_left ) ? $seats_left : absint( $seats_left ) - absint( $booked ),
					'fsd_cost'     => ! empty( $fsds['departure_dates']['cost'][ $key ] ) && ! $enable_multi_pricing ? apply_filters( 'wte_fsd_price', $fsds['departure_dates']['cost'][ $key ], $pid ) : wp_travel_engine_get_actual_trip_price( $pid ),
				);
				continue;
			}

			// Frequency.
			if ( isset( $fsds['departure_dates'][ $key ]['recurring']['type'] ) ) {
				$freq = $fsds['departure_dates'][ $key ]['recurring']['type'];
			} else {
				$freq = 'DAILY';
			}

			switch ( $freq ) {
				case 'WEEKLY':
					$byday         = isset( $fsds['departure_dates'][ $key ]['recurring']['week_days'] ) ? array_values( $fsds['departure_dates'][ $key ]['recurring']['week_days'] ) : array( 'SU', 'MO', 'TU', 'WE', 'TH', 'FR', 'SA' );
					$args['BYDAY'] = $byday;
					break;
				case 'MONTHLY':
					$bymonth         = isset( $fsds['departure_dates'][ $key ]['recurring']['months'] ) ? array_values( $fsds['departure_dates'][ $key ]['recurring']['months'] ) : range( 1, 12 );
					$args['bymonth'] = $bymonth;
					break;
			}

			$args['freq']    = $freq;
			$args['dtstart'] = $start_date;
			$until           = ( new DateTime( $start_date_str, new DateTimeZone( 'UTC' ) ) )->add( new DateInterval( 'P2Y' ) );

			$count         = isset( $fsds['departure_dates'][ $key ]['recurring']['limit'] ) ? $fsds['departure_dates'][ $key ]['recurring']['limit'] : 10;
			$args['count'] = $count;

			// Snapshot.
			$snapshot = ( new RSet() )->addRRule( $args );
			if ( $snapshot && isset( $snapshot[ $count - 1 ] ) ) {
				$last_recurred_date = $snapshot[ $count - 1 ];
				if ( $last_recurred_date > $until ) {
					unset( $args['count'] );
					$args['until'] = $until;
				}
			}

			$rset->addRRule( $args );

			if ( $rset ) {

				foreach ( $rset as $occurrence ) {
					$start_date   = WTE_Fixed_Starting_Dates_Functions::to_utc( $fsds['departure_dates']['sdate'][ $key ] );
					$end_date     = isset( $fsds['departure_dates']['edate'][ $key ] ) ? $fsds['departure_dates']['edate'][ $key ] : '';
					$availability = isset( $fsds['departure_dates']['availability_type'][ $key ] ) ? $fsds['departure_dates']['availability_type'][ $key ] : '';
					$seats_left   = isset( $fsds['departure_dates']['seats_available'][ $key ] ) ? $fsds['departure_dates']['seats_available'][ $key ] : '';
					$booked       = isset( $fsbs[ $occurrence->getTimeStamp() ]['booked'] ) ? $fsbs[ $occurrence->getTimeStamp() ]['booked'] : 0;

					if ( $cutoff_enabled ) {
						$valid_departure_date = WTE_Fixed_Starting_Dates_Functions::to_utc( 'now' );

						if ( $cut_off_unit === 'days' && ! empty( $trip_cut_off_time ) ) {
							$valid_departure_date = ( WTE_Fixed_Starting_Dates_Functions::to_utc( 'now' ) )->add( new DateInterval( "P{$trip_cut_off_time}D" ) );
						}

						if ( WTE_Fixed_Starting_Dates_Functions::to_utc( $valid_departure_date->format( 'y-m-d' ) ) > $occurrence ) {
							continue;
						}
					} else {
						if ( $occurrence < WTE_Fixed_Starting_Dates_Functions::to_utc( 'now' ) ) {
							continue;
						}
					}

					if (
						( ! empty( $rrule_filter['year'] ) && (int) $occurrence->format( 'Y' ) !== (int) $dtuntilyear )
						|| ( (int) $occurrence->format( 'Y' ) < (int) $dtstartyear )
						) {
						continue;
					}

					if ( ( (int) $occurrence->format( 'Y' ) ) > (int) $dtuntilyear ) {
						break;
					}

					if ( ! in_array( (int) $occurrence->format( 'n' ), $months ) ) {
						continue;
					}

					$fsd_dates[ $occurrence->getTimestamp() ] = array(
						'trip_id'      => $pid,
						'content_id'   => $start_date->getTimestamp(),
						'start_date'   => $occurrence->format( 'Y-m-d' ),
						'end_date'     => $duration ? $occurrence->add( new DateInterval( "P{$duration}D" ) )->format( 'Y-m-d' ) : $occurrence->format( 'Y-m-d' ),
						'availability' => $availability,
						'seats_left'   => '' === trim( $seats_left ) ? $seats_left : absint( $seats_left ) - absint( $booked ),
						'fsd_cost'     => ! empty( $fsds['departure_dates']['cost'][ $key ] ) && ! $enable_multi_pricing ? apply_filters( 'wte_fsd_price', $fsds['departure_dates']['cost'][ $key ], $pid ) : wp_travel_engine_get_actual_trip_price( $pid ),
					);
				}
			}
		}
		ksort( $fsd_dates );
		return $fsd_dates;
	}

	public static function get_table_columns() {
		return apply_filters(
			'wte_fixed_starting_dates_listing_table_columns_labels',
			array(
				'start_date'   => __( 'From - To', 'wte-fixed-departure-dates' ),
				'trip_id'      => __( 'Trip', 'wte-fixed-departure-dates' ),
				'duration'     => __( 'Duration', 'wte-fixed-departure-dates' ),
				'fsd_cost'     => __( 'Price', 'wte-fixed-departure-dates' ),
				'availability' => __( 'Availability', 'wte-fixed-departure-dates' ),
				'seats_left'   => __( 'Space left', 'wte-fixed-departure-dates' ),
				'action'       => '',
			)
		);
	}

	public function sort_fsd_by_month( $fsds ) {
		$sorted_by_month = array();
		foreach ( $fsds as $fsd ) {
			foreach ( $fsd['dates'] as $fs ) {
				$key = isset( $fs['start_date'] ) ? explode( '-', $fs['start_date'] ) : array();

				if ( empty( $key ) ) {
					continue;
				}
				$sorted_by_month[ $key[0] . '-' . $key[1] ][] = $fs;
			}
		}
		return $sorted_by_month;
	}

	private static function set_default_localize_data() {
		$l10n = array();

		$l10n['availabilityOptions'] = array(
			'guaranteed' => __( 'Guaranteed', 'wte-fixed-departure-dates' ),
			'available'  => __( 'Available', 'wte-fixed-departure-dates' ),
			'limited'    => __( 'Limited', 'wte-fixed-departure-dates' ),
		);
		$l10n['dateformat']          = apply_filters( 'wte_fsd__fromto_date_format', get_option( 'date_format' ) );
		$l10n['currencyCode']        = wp_travel_engine_get_currency_code();
		$l10n['currencySymbol']      = wp_travel_engine_get_currency_symbol( $l10n['currencyCode'] );
		$l10n['costString']          = array( 'format' => '%CURRENCY_SYMBOL%%FORMATED_AMOUNT%' );
		$l10n['priceFormat']         = '%CURRENCY_SYMBOL%%FORMATED_AMOUNT%';
		$settings                    = get_option( 'wp_travel_engine_settings', array() );
		if ( ! empty( $settings['amount_display_format'] ) ) {
			$l10n['priceFormat'] = $settings['amount_display_format'];
		}
		$l10n['numberFormat'] = array(
			'decimal'            => ! empty( $settings['decimal_digits'] ) ? $settings['decimal_digits'] : 2,
			'decimalSeparator'   => ! empty( $settings['decimal_separator'] ) ? $settings['decimal_separator'] : '.',
			'thousandsSeparator' => ! empty( $settings['thousands_separator'] ) ? $settings['thousands_separator'] : ',',
		);

		$columns = self::get_table_columns();

		$l10n['columns'] = $columns;
		$l10n['l10n']    = array_merge(
			$columns,
			array(
				'book_now' => __( 'Book Now', 'wte-fixed-departure-dates' ),
				'na'       => __( 'N/A', 'wte-fixed-departure-dates' ),
				'nofsd'    => __( 'No Fixed Departure Dates available.', 'wte-fixed-departure-dates' ),
			)
		);

		return $l10n;
	}

	private static function get_trip_data_for_all_fsd_listing( $post, $trip_settings ) {
		$packages = get_post_meta( $post->ID, 'packages_ids', true );

		if ( ! is_array( $packages ) ) {
			return false;
		}

		$duration = isset( $trip_settings ['trip_duration'] ) ? $trip_settings ['trip_duration'] : 0;
		$nights   = isset( $trip_settings ['trip_duration_nights'] ) ? $trip_settings ['trip_duration_nights'] : 0;

		$booked_seats = get_post_meta( $post->ID, 'wte_fsd_booked_seats', true );

		$trip_data = array(
			'title'                  => $post->post_title,
			'link'                   => esc_url( get_permalink( $post->ID ) ),
			'enabledMultiplePricing' => true,
			'defaultCost'            => wp_travel_engine_get_actual_trip_price( $post->ID ),
			// 'departureDates'         => $WTE_Fixed_Starting_Dates_setting['departure_dates'],
			'durationUnit'           => isset( $trip_settings ['trip_duration_unit'] ) ? $trip_settings ['trip_duration_unit'] : 0,
			'duration'               => isset( $trip_settings ['trip_duration'] ) ? $trip_settings ['trip_duration'] : 0,
			'daysLabel'              => _n( 'Day', 'Days', (int) $duration, 'wte-fixed-departure-dates' ),
			'hoursLabel'              => _n( 'Hour', 'Hours', (int) $duration, 'wte-fixed-departure-dates' ),
			'nights'                 => isset( $trip_settings ['trip_duration_nights'] ) ? $trip_settings ['trip_duration_nights'] : 0,
			'nightsLabel'            => _n( 'Night', 'Nights', $nights, 'wte-fixed-departure-dates' ),
			'bookedSeats'            => ! empty( $booked_seats ) && is_array( $booked_seats ) ? $booked_seats : array(),
		);

		$_package_dates = array();
		$index          = 1;
		foreach ( $packages  as $package_id ) {
			$package_dates = get_post_meta( $package_id, 'package-dates', true );
			if ( ! is_array( $package_dates ) || empty( $package_dates ) ) {
				continue;
			}
			$package_cost = packages\get_trip_lowest_price_by_package_id( $package_id );

			foreach ( $package_dates as $package_date ) {
				$_package_dates['sdate'][ $index ]             = $package_date['dtstart'];
				$_package_dates['edate'][ $index ]             = '';
				$_package_dates['cost'][ $index ]              = $package_cost;
				$_package_dates['seats_available'][ $index ]   = (int) $package_date['seats'];
				$_package_dates['availability_type'][ $index ] = $package_date['availability_label'];
				$recurring                                     = array(
					'enable'    => isset( $package_date['is_recurring'] ),
					'type'      => $package_date['rrule']['r_frequency'],
					'week_days' => isset( $package_date['rrule']['r_weekdays'] ) ? $package_date['rrule']['r_weekdays'] : array(),
					'months'    => isset( $package_date['rrule']['r_months'] ) ? $package_date['rrule']['r_months'] : array(),
					'limit'     => isset( $package_date['rrule']['r_count'] ) ? $package_date['rrule']['r_count'] : array(),
					'until'     => isset( $package_date['rrule']['r_until'] ) ? $package_date['rrule']['r_until'] : '',
				);

				$_package_dates[ $index ]['recurring'] = $recurring;

				$index++;
			}
		}

		$trip_data['departureDates'] = $_package_dates;

		return $trip_data;

	}

	public static function localize_data_for_all_fsd_listing() {
		global $wpdb;

		$where  = $wpdb->prepare( "{$wpdb->posts}.post_type = %s", WP_TRAVEL_ENGINE_POST_TYPE );
		$where .= " AND {$wpdb->posts}.post_status = 'publish'";

		// Post ids screenshot.
		$post_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE {$where}" );

		$l10n = array();
		if ( $post_ids ) {
			global $wp_query;
			$wp_query->in_the_lopp = true;

			$l10n = self::set_default_localize_data();

			while ( $next_posts = array_splice( $post_ids, 0, 20 ) ) {
				$where = 'WHERE ID IN (' . join( ',', $next_posts ) . ')';
				$posts = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} $where" );
				foreach ( $posts as $post ) {
					$trip_settings = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );
					$trip_data     = self::get_trip_data_for_all_fsd_listing( $post, $trip_settings );

					if ( $trip_data ) {
						if ( ! empty( $trip_settings['trip_cut_off_time'] ) ) {
							$trip_data['cutOffTime'] = (int) $trip_settings['trip_cut_off_time'];
							$trip_data['cutOffUnit'] = $trip_settings['trip_cut_off_unit'];
						}
						$l10n['trips'][ $post->ID ] = $trip_data;
						continue;
					}
				}
			}
			$wp_query->reset_postdata();

		}

		return $l10n;
	}

	/**
	 * All fixed departure dates from all trips.
	 */
	public function wte_fixed_starting_dates_all() {
		wp_enqueue_script( 'wte-rrule' );

		wte_fsd_get_template( 'table-inner-advanced.php' );

		global $wpdb;

		$columns = self::get_table_columns();

		$l10n = array();
		if ( version_compare( WP_TRAVEL_ENGINE_VERSION, '5.0.0', '>=' ) ) {
			$l10n = self::localize_data_for_all_fsd_listing();
		} else {

			// Query trips.
			$where  = $wpdb->prepare( "{$wpdb->posts}.post_type = %s", WP_TRAVEL_ENGINE_POST_TYPE );
			$where .= " AND {$wpdb->posts}.post_status = 'publish'";

			$join = "INNER JOIN {$wpdb->postmeta} ON ({$wpdb->posts}.ID = {$wpdb->postmeta}.post_id AND {$wpdb->postmeta}.meta_key = 'WTE_Fixed_Starting_Dates_setting')";

			// Post ids screenshot.
			$post_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} {$join} WHERE {$where}" );

			if ( $post_ids ) {
				global $wp_query;
				$wp_query->in_the_lopp       = true;
				$l10n                        = array();
				$l10n['availabilityOptions'] = array(
					'guaranteed' => __( 'Guaranteed', 'wte-fixed-departure-dates' ),
					'available'  => __( 'Available', 'wte-fixed-departure-dates' ),
					'limited'    => __( 'Limited', 'wte-fixed-departure-dates' ),
				);
				$l10n['dateformat']          = get_option( 'date_format' );
				$l10n['currencyCode']        = wp_travel_engine_get_currency_code();
				$l10n['currencySymbol']      = wp_travel_engine_get_currency_symbol( $l10n['currencyCode'] );
				$l10n['costString']          = array( 'format' => '%CURRENCY_SYMBOL%%FORMATED_AMOUNT%' );
				$l10n['priceFormat']         = '%CURRENCY_SYMBOL%%FORMATED_AMOUNT%';
				$settings                    = get_option( 'wp_travel_engine_settings', array() );
				if ( ! empty( $settings['amount_display_format'] ) ) {
					$l10n['priceFormat'] = $settings['amount_display_format'];
				}
				$l10n['numberFormat'] = array(
					'decimal'            => ! empty( $settings['decimal_digits'] ) ? $settings['decimal_digits'] : 0,
					'decimalSeparator'   => ! empty( $settings['decimal_separator'] ) ? $settings['decimal_separator'] : '.',
					'thousandsSeparator' => ! empty( $settings['thousands_separator'] ) ? $settings['thousands_separator'] : ',',
				);

				$l10n['columns'] = $columns;
				$l10n['l10n']    = array_merge(
					$columns,
					array(
						'book_now' => __( 'Book Now', 'wte-fixed-departure-dates' ),
						'na'       => __( 'N/A', 'wte-fixed-departure-dates' ),
						'nofsd'    => __( 'No Fixed Departure Dates available.', 'wte-fixed-departure-dates' ),
					)
				);
				while ( $next_posts = array_splice( $post_ids, 0, 20 ) ) {
					$where = 'WHERE ID IN (' . join( ',', $next_posts ) . ')';
					$posts = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} $where" );

					foreach ( $posts as $post ) {

						$WTE_Fixed_Starting_Dates_setting = get_post_meta( $post->ID, 'WTE_Fixed_Starting_Dates_setting', true );
						$trip_settings                    = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );
						$booked_seats                     = get_post_meta( $post->ID, 'wte_fsd_booked_seats', true );
						if ( ! isset( $WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'] ) || ! is_array( $WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'] ) || count( $WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'] ) <= 0 ) {
							continue;
						}
						$enable_multi_pricing = ! empty( $WTE_Fixed_Starting_Dates_setting['enable_multiple_pricing_fsd'] );

						$duration = isset( $trip_settings ['trip_duration'] ) ? $trip_settings ['trip_duration'] : 0;
						$nights   = isset( $trip_settings ['trip_duration_nights'] ) ? $trip_settings ['trip_duration_nights'] : 0;

						$l10n['trips'][ $post->ID ] = array(
							'title'                  => $post->post_title,
							'link'                   => esc_url( get_permalink( $post->ID ) ),
							'enabledMultiplePricing' => true,
							'defaultCost'            => wp_travel_engine_get_actual_trip_price( $post->ID ),
							'departureDates'         => $WTE_Fixed_Starting_Dates_setting['departure_dates'],
							'durationUnit'           => isset( $trip_settings ['trip_duration_unit'] ) ? $trip_settings ['trip_duration_unit'] : 0,
							'duration'               => isset( $trip_settings ['trip_duration'] ) ? $trip_settings ['trip_duration'] : 0,
							'daysLabel'              => _n( 'Day', 'Days', (int) $duration, 'wte-fixed-departure-dates' ),
							'nights'                 => isset( $trip_settings ['trip_duration_nights'] ) ? $trip_settings ['trip_duration_nights'] : 0,
							'nightsLabel'            => _n( 'Night', 'Nights', $nights, 'wte-fixed-departure-dates' ),
							'bookedSeats'            => ! empty( $booked_seats ) && is_array( $booked_seats ) ? $booked_seats : array(),
						);

						if ( ! empty( $trip_settings['trip_cut_off_time'] ) ) {
							$l10n['trips'][ $post->ID ]['cutOffTime'] = (int) $trip_settings['trip_cut_off_time'];
							$l10n['trips'][ $post->ID ]['cutOffUnit'] = $trip_settings['trip_cut_off_unit'];
						}
					}
				}
				$wp_query->reset_postdata();
			}
		}

		?>
		<script>
			<?php echo 'var wteUtilFsdsL10n = ' . wp_json_encode( $l10n, JSON_HEX_QUOT | JSON_HEX_TAG ) . ';'; ?>
		</script>
		<?php
			return ob_get_clean();
	}

	public function Wte_Fixed_Starting_Dates_Action( $tab_content = false ) {
		ob_start();
		global $post;
		$wp_travel_engine_setting                = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );
		$WTE_Fixed_Starting_Dates_setting        = WTE_Fixed_Starting_Dates_Functions::get_settings( $post->ID );
		$WTE_Fixed_Starting_Dates_option_setting = get_option( 'wp_travel_engine_settings', true );

		$sortable_settings = get_post_meta( $post->ID, 'list_serialized', true );
		$start_date        = array();
		// if ( ! is_array( $sortable_settings ) ) {
		// 	$sortable_settings = json_decode( $sortable_settings );
		// }

		// // override with sorted fsd settings
		// $sortable_settings = $this->get_sorted_fsdates( $post->ID );

		$args = array(
			'post_id'       => $post->ID,
			'is_tab_conent' => $tab_content,
		);

		wte_fsd_get_template( 'fsd-table.php', $args );

		$output = ob_get_clean();
		$output = apply_filters( 'wte_fixed_departure_dates_table_template', $output, $sortable_settings, $start_date );
		echo $output;
	}
}
