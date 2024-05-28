<?php
/**
 * Basic functions for the plugin.
 *
 * Maintain a list of functions that are used in the plugin for basic purposes
 *
 * @package    WTE_Fixed_Starting_Dates
 * @subpackage WTE_Fixed_Starting_Dates/includes
 * @author
 */
/**
 * Recurring options.
 */
use RRule\RRule;
use RRule\RSet;
class WTE_Fixed_Starting_Dates_Functions {

	/**
	 * Set min and max trip date.
	 */
	private static function set_trip_minmax_dates( $trip ) {
		global $wpdb;

		$package_ids = get_post_meta( $trip->ID, 'packages_ids', true );

		if ( ! is_array( $package_ids ) || empty( $package_ids ) ) {
			return;
		}

		$results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE post_id IN (" . implode( ',', $package_ids ) . ") AND  meta_key = 'package-dates' AND meta_value != 'a:0:{}' " );

		$rset = new \RRule\RSet();
		foreach ( $results as $result ) {
			$package_dates = maybe_unserialize( $result->meta_value );

			update_post_meta( $result->post_id, 'trip_ID', $trip->ID );

			if ( ! is_array( $package_dates ) ) {
				continue;
			}
			foreach ( $package_dates as $date_key => $date_args ) {
				$date_args = (object) $date_args;
				if ( isset( $date_args->dtstart ) && empty( trim( $date_args->dtstart ) ) ) {
					continue;
				}

				try {
					$date_start = new DateTime( $date_args->dtstart );
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
				} catch ( \Exception $e ) {
					continue;
				}
			}
		}

		$today            = new \DateTime();
		$available_months = array();
		foreach ( $rset as $occurrence ) {
			if ( $occurrence < $today ) {
				continue;
			}
			$key                      = $occurrence->format( 'ym' );
			$available_months[ $key ] = $key;
		}
		update_post_meta( $trip->ID, 'trip_available_months', implode( ',', $available_months ) );
	}

	public static function set_min_and_max_date() {
		$already_set = get_option( '_wte_trips_available_months_set', false );
		$is_migrated = get_option( 'wte_migrated_to_multiple_pricing', false ) === 'done';

		if ( ! $is_migrated || $already_set ) {
			return;
		}

		global $wpdb;

		$where  = $wpdb->prepare( "{$wpdb->posts}.post_type = %s", WP_TRAVEL_ENGINE_POST_TYPE );
		$where .= " AND {$wpdb->posts}.post_status IN ( 'publish','draft' )";

		$trip_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE {$where}" );

		if ( $trip_ids ) {
			global $wp_query;
			$wp_query->in_the_lopp = true;
			while ( $next_trips = array_splice( $trip_ids, 0, 20 ) ) { // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition
				$where = 'WHERE ID IN (' . join( ',', $next_trips ) . ')';
				$trips = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} $where" ); // phpcs:ignore
				foreach ( $trips as $trip ) {
					// $has_min_date = get_post_meta( $trip->ID, 'trip_available_months', true );
					// if ( ! empty( $has_min_date ) ) {
					// continue; // Already set.
					// }

					self::set_trip_minmax_dates( $trip );
				}
			}
		}
		update_option( '_wte_trips_available_months_set', 'done' );
	}

	public static function generator( $year_months ) {
		for ( $i = 0; $i < count( array_keys( $year_months ) ); $i++ ) {
			if ( empty( $year_months[ $i ] ) ) {
				continue;
			}
			$yearmonth = '20' . $year_months[ $i ];
			$year      = substr( $yearmonth, 0, 4 );
			$month     = substr( $yearmonth, 4 );
			$now       = new \DateTime();
			try {
				$now->setDate( (int) $year, (int) $month, date('t', mktime( 0, 0, 0, (int) $month, 1, (int) $year ) ) );
				yield $i => $now;
			} catch ( \Exception $e ) {
				yield $i => $now;
			}
		}
	}

	public static function get_trips_available_year_months() {
		global $wpdb;

		$results     = $wpdb->get_results( "SELECT GROUP_CONCAT( meta_value ) as 'year_months' FROM {$wpdb->postmeta} WHERE meta_key = 'trip_available_months' ORDER BY meta_value ASC" );
		$year_months = array();
		if ( isset( $results[0]->year_months ) ) {
			$year_months = explode( ',', $results[0]->year_months );
			$year_months = array_flip( $year_months );
			ksort( $year_months, SORT_NUMERIC );
			$year_months = array_keys( $year_months );
		}
		return self::generator( $year_months );
	}

	public static function to_utc( string $date ) {
		$utc = new \DateTime( $date, new \DateTimeZone( 'UTC' ) );
		$utc->setTime( 0, 0, 0 );
		return $utc;
	}

	public static function generate_fsds( $pid, $rrule_filter = array() ) {

		// For single trip.
		$trip_settings    = get_post_meta( $pid, 'wp_travel_engine_setting', true );
		$fsd_booked_seats = WPTravelEngine\Packages\get_booked_seats_number_by_date( $pid );
		$duration         = isset( $trip_settings['trip_duration'] ) && ! empty( $trip_settings['trip_duration'] ) ? absint( $trip_settings['trip_duration'] - 1 ) : false;
		$duration_unit    = isset( $trip_settings['trip_duration_unit'] ) ? $trip_settings['trip_duration_unit'] : 'days';

		$dur_days = isset( $duration ) && 'days' === $duration_unit ? $duration : false;
		$fsds     = array();
		$fsds     = get_post_meta( $pid, 'WTE_Fixed_Starting_Dates_setting', true );

		// Get Trip package Ids.
		$packages = WPTravelEngine\Packages\get_packages_by_trip_id( $pid );

		$fsds      = array();
		$fsd_dates = array();

		// Cutoff Vars.
		$cutoff_enabled    = ! empty( $trip_settings['trip_cutoff_enable'] ) && 'true' === $trip_settings['trip_cutoff_enable'];
		$trip_cut_off_time = isset( $trip_settings['trip_cut_off_time'] ) && ! empty( trim( $trip_settings['trip_cut_off_time'] ) ) ? $trip_settings['trip_cut_off_time'] : 0;
		$cut_off_unit      = ! empty( $trip_settings['trip_cut_off_unit'] ) ? $trip_settings['trip_cut_off_unit'] : 'days';

		$today = new DateTime( 'now', new DateTimeZone( 'UTC' ) );

		foreach ( $packages as $package ) {
			$package_dates = $package->{'package-dates'};

			if ( ! is_array( $package_dates ) ) {
				continue;
			}

			$package_lowest_cost = WPTravelEngine\Packages\get_trip_lowest_price_by_package_id( $package->ID );

			$rset = new RSet();
			foreach ( $package_dates as $dateindex => $pdate ) {
				$pdate          = (object) $pdate;
				$dtstart_object = new DateTime( $pdate->dtstart );
				$timestamp      = $dtstart_object->getTimestamp();

				$start_date = self::to_utc( $pdate->dtstart );

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

				if ( isset( $pdate->is_recurring ) && '1' == $pdate->is_recurring ) {
					$args = array();

					$freq  = 'DAILY';
					$rrule = (object) $pdate->rrule;

					$freq = isset( $rrule->r_frequency ) ? $rrule->r_frequency : $freq;
					switch ( $freq ) {
						case 'WEEKLY':
							$byday         = isset( $rrule->{'r_weekdays'} ) ? array_values( $rrule->{'r_weekdays'} ) : array( 'SU', 'MO', 'TU', 'WE', 'TH', 'FR', 'SA' );
							$args['BYDAY'] = $byday;
							break;
						case 'MONTHLY':
							$bymonth         = isset( $rrule->{'r_months'} ) ? array_map(
								function( $value ) {
									return absint( $value );
								},
								$rrule->{'r_months'}
							) : range( 1, 12 );
							$args['bymonth'] = $bymonth;
							break;
					}

					$args['freq']    = $freq;
					$args['dtstart'] = $pdate->dtstart;
					$count           = ! empty( $rrule->{'r_count'} ) ? absint( $rrule->{'r_count'} ) : 10;
					$args['count']   = $count;

					if ( ! empty( $rrule->r_until ) ) {
						$until = new DateTime( $rrule->r_until, new DateTimeZone( 'UTC' ) );
					} else {
						$until = new DateTime( $pdate->dtstart, new DateTimeZone( 'UTC' ) );
						$until->add( new DateInterval( 'P2Y' ) );
					}

					// Snapshot.
					$temp_rset = new RSet();
					$snapshot  = $temp_rset->addRRule( $args );
					if ( $snapshot && isset( $snapshot[ $count - 1 ] ) ) {
						$last_recurred_date = $snapshot[ $count - 1 ];
						if ( $last_recurred_date > $until ) {
							unset( $args['count'] );
							$args['until'] = $until->format( 'Y-m-d' );
						}
					}

					$rset->addRRule( $args );

					if ( $rset ) {

						$valid_departure_date = self::to_utc( 'now' );
						$valid_departure_date->setTime( 0, 0, 0, 0 );
						if ( $cutoff_enabled ) {

							if ( $cut_off_unit === 'days' && ! empty( $trip_cut_off_time ) ) {
								$valid_departure_date->add( new DateInterval( "P{$trip_cut_off_time}D" ) );
							}
						}

						if ( $start_date >= $valid_departure_date ) {
							$rset->addDate( $start_date );
						}

						foreach ( $rset as $occurrence ) {
							// $start_date   = self::to_utc( $pdate->dtstart );
							$end_date     = '';
							$availability = isset( $pdate->availability_label ) ? $pdate->availability_label : '';
							$seats_left   = isset( $pdate->seats ) && '' !== $pdate->seats ? absint( $pdate->seats ) : '';
							$booked       = isset( $fsd_booked_seats[ $occurrence->getTimeStamp() ]['booked'] ) ? $fsd_booked_seats[ $occurrence->getTimeStamp() ]['booked'] : 0;

							if ( $occurrence < $valid_departure_date ) {
								continue;
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
								'seats_left'   => '' === $seats_left ? $seats_left : absint( $seats_left ) - absint( $booked ),
								// 'fsd_cost'     => ! empty( $fsds['departure_dates']['cost'][ $key ] ) && ! $enable_multi_pricing ? apply_filters( 'wte_fsd_price', $fsds['departure_dates']['cost'][ $key ], $pid ) : wp_travel_engine_get_actual_trip_price( $pid ),
								'fsd_cost'     => $package_lowest_cost,

							);
						}
					}
				} else { // if not recurring.
					if ( $start_date < $today ) {
						continue;
					}

					if ( $cutoff_enabled && $trip_cut_off_time ) {
						$departure_date = new DateTime( 'now', new DateTimeZone( 'UTC' ) );
						$departure_date->setTime( 0, 0, 0, 0 );

						if ( $cut_off_unit === 'days' ) {
							$departure_date = $departure_date->add( new DateInterval( "P{$trip_cut_off_time}D" ) );
						}
						if ( $start_date < $departure_date ) {
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

					$availability = isset( $pdate->availability_label ) ? $pdate->availability_label : '';
					$booked       = isset( $fsd_booked_seats[ $start_date->getTimeStamp() ]['booked'] ) ? $fsd_booked_seats[ $start_date->getTimeStamp() ]['booked'] : 0;
					$seats_left   = isset( $pdate->{'seats'} ) && '' !== $pdate->seats ? absint( $pdate->{'seats'} ) : '';

					$fsd_dates[ $start_date->getTimestamp() ] = array(
						'trip_id'      => $pid,
						'content_id'   => $start_date->getTimestamp(),
						'start_date'   => $start_date->format( 'Y-m-d' ),
						'end_date'     => $duration ? $start_date->add( new DateInterval( "P{$duration}D" ) )->format( 'Y-m-d' ) : $start_date->format( 'Y-m-d' ),
						'availability' => $availability,
						'seats_left'   => '' === trim( $seats_left ) ? $seats_left : absint( $seats_left ) - absint( $booked ),
						// 'fsd_cost'     => ! empty( $fsds['departure_dates']['cost'][ $key ] ) && ! $enable_multi_pricing ? apply_filters( 'wte_fsd_price', $fsds['departure_dates']['cost'][ $key ], $pid ) : wp_travel_engine_get_actual_trip_price( $pid ),
						'fsd_cost'     => $package_lowest_cost,
					);
				}
			}
		}
		ksort( $fsd_dates );
		return $fsd_dates;

	}

	public static function get_fsds_by_trip_id( $trip_id ) {
		return self::generate_fsds( $trip_id );
	}

	public static function availability() {
		 $options = array(
			 'guaranteed' => __( 'Guaranteed', 'wte-fixed-departure-dates' ),
			 'available'  => __( 'Available', 'wte-fixed-departure-dates' ),
			 'limited'    => __( 'Limited', 'wte-fixed-departure-dates' ),
		 );

		 $options = apply_filters( 'wte_availability_options', $options );

		 return $options;
	}

	/**
	 * Get difficulty Label
	 */
	public static function get_availability_label( $availability ) {
		$options = self::availability();

		$availability = ( $availability ) ? $options[ $availability ] : $availability;

		return $availability;
	}

	/**
	 * Get settings.
	 *
	 * @since 1.2.4
	 * @static
	 * @access public
	 *
	 * @param int $post_ID Trip ID.
	 * @return array Settings.
	 */
	public static function get_settings( $post_ID ) {
		$WTE_Fixed_Starting_Dates_setting = get_post_meta( $post_ID, 'WTE_Fixed_Starting_Dates_setting', true );

		if ( isset( $WTE_Fixed_Starting_Dates_setting['departure_dates']['cost'] ) ) {
			$costs = $WTE_Fixed_Starting_Dates_setting['departure_dates']['cost'];
			$costs = apply_filters( 'wte_fsd_price', $costs, $post_ID );
			$WTE_Fixed_Starting_Dates_setting['departure_dates']['cost'] = $costs;
		}

		return $WTE_Fixed_Starting_Dates_setting;
	}

	/**
	 * Gets Recurring Date by Args.
	 *
	 * @return array Recurring Dates.
	 */
	public function get_recurring_dates_by_rrule( array $args, $set = false ) {
		if ( $set ) {
			$rset = new RSet();
			foreach ( $args as $arg ) {
				$rset->addRRule( $arg );
			}
			return $rset;
		}
		return new RRule( $args );
	}

	/**
	 * Get formated FSD dates with recurring support
	 *
	 * @param [type] $post_id
	 * @return void
	 */
	public function get_formated_fsd_dates( $post_id = null, $filters = array() ) {
		$fsd_formated_array = array();
		if ( ! $post_id || 'trip' !== get_post( $post_id )->post_type ) {
			return $fsd_formated_array;
		}

		$trip_settings = get_post_meta( $post_id, 'wp_travel_engine_setting', true );

		$duration      = isset( $trip_settings['trip_duration'] ) && ! empty( $trip_settings['trip_duration'] ) ? absint( $trip_settings['trip_duration'] - 1 ) : false;
		$duration_unit = isset( $trip_settings['trip_duration_unit'] ) ? $trip_settings['trip_duration_unit'] : 'days';

		$dur_days = isset( $duration ) && 'days' == $duration_unit ? $duration : false;

		$WTE_Fixed_Starting_Dates_setting = get_post_meta( $post_id, 'WTE_Fixed_Starting_Dates_setting', true );

		$sortable_settings = get_post_meta( $post_id, 'list_serialized', true );
		if ( ! is_array( $sortable_settings ) ) {
			$sortable_settings = json_decode( $sortable_settings );
		}

		if ( isset( $WTE_Fixed_Starting_Dates_setting ) && $WTE_Fixed_Starting_Dates_setting != '' && isset( $sortable_settings ) && sizeof( $sortable_settings ) > 0 ) {

			$today    = strtotime( date( 'Y-m-d' ) ) * 1000;
			$fsd_indx = 0;

			// Cutoff Vars.
			$cutoff_enabled    = ! empty( $trip_settings['trip_cutoff_enable'] ) && 'true' === $trip_settings['trip_cutoff_enable'];
			$trip_cut_off_time = isset( $trip_settings['trip_cut_off_time'] ) && ! empty( $trip_settings['trip_cut_off_time'] ) ? $trip_settings['trip_cut_off_time'] : 0;
			$cut_off_unit      = isset( $trip_settings['trip_cut_off_unit'] ) ? $trip_settings['trip_cut_off_unit'] : 'days';

			foreach ( $sortable_settings as $content ) {
				if ( isset( $WTE_Fixed_Starting_Dates_setting['departure_dates']['edate'][ $content->id ] ) && $WTE_Fixed_Starting_Dates_setting['departure_dates']['edate'][ $content->id ] != '' ) {

					$recurring_enable = isset( $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['enable'] ) ? true : false;

					if ( $today <= strtotime( $WTE_Fixed_Starting_Dates_setting['departure_dates']['edate'][ $content->id ] ) * 1000 || $recurring_enable ) {

						if ( isset( $WTE_Fixed_Starting_Dates_setting['departure_dates']['cost'][ $content->id ] ) && $WTE_Fixed_Starting_Dates_setting['departure_dates']['cost'][ $content->id ] != '' ) {
							$fsd_cost = $WTE_Fixed_Starting_Dates_setting['departure_dates']['cost'][ $content->id ];
						} else {
							$fsd_cost = wp_travel_engine_get_actual_trip_price( $post_id );
						}

						// $fsd_cost = apply_filters( 'wte_fsd_price', $fsd_cost, $post_id );

						$start_date = isset( $WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'][ $content->id ] ) ? $WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'][ $content->id ] : '';

						$end_date = isset( $WTE_Fixed_Starting_Dates_setting['departure_dates']['edate'][ $content->id ] ) ? $WTE_Fixed_Starting_Dates_setting['departure_dates']['edate'][ $content->id ] : '';

						$availability = isset( $WTE_Fixed_Starting_Dates_setting['departure_dates']['availability_type'][ $content->id ] ) ? $WTE_Fixed_Starting_Dates_setting['departure_dates']['availability_type'][ $content->id ] : 'guaranteed';

						$seats_left = isset( $WTE_Fixed_Starting_Dates_setting['departure_dates']['seats_available'][ $content->id ] ) ? $WTE_Fixed_Starting_Dates_setting['departure_dates']['seats_available'][ $content->id ] : '';

						$trip_booked_seats = get_post_meta( $post_id, 'wte_fsd_booked_seats', true );

						if ( $recurring_enable ) {
							$recursion_type  = isset( $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['type'] ) ? $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['type'] : 'DAILY';
							$recurring_limit = isset( $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['limit'] ) ? $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['limit'] : 10;
							$months_recur    = isset( $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['months'] ) && ! empty( $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['months'] ) ? $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['months'] : array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12' );

							$weekdays_recur = isset( $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['week_days'] ) && ! empty( $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['week_days'] ) ? $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['week_days'] : array( 'MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU' );

							$recurr_args = array(
								'FREQ'     => $recursion_type,
								'INTERVAL' => 1,
								'DTSTART'  => $start_date,
								'COUNT'    => $recurring_limit,
							);

							if ( 'MONTHLY' === $recursion_type ) {
								$recurr_args['BYMONTH'] = array_values( $months_recur );
							}

							if ( 'WEEKLY' === $recursion_type ) {
								$recurr_args['BYDAY'] = array_values( $weekdays_recur );
							}

							$rrule = $this->get_recurring_dates_by_rrule( $recurr_args );

							foreach ( $rrule as $occurrence ) {
								$start_recur = $occurrence->format( 'Y-m-d' );
								if ( isset( $filters['year'] ) && $filters['year'] !== $occurrence->format( 'Y' ) ) {
									continue;
								}
								if ( isset( $filters['month'] ) && $filters['month'] !== $occurrence->format( 'm' ) ) {
									continue;
								}
								if ( $dur_days ) {
									$date = new DateTime( $start_recur );
									$date->add( new DateInterval( 'P' . $dur_days . 'D' ) );
									$end_recur = $date->format( 'Y-m-d' );
								} else {
									$end_recur = date_i18n( 'Y-m-d', strtotime( $start_recur ) );
								}

								if ( $today >= strtotime( $start_recur ) * 1000 ) {
									continue;
								}

								if ( $cutoff_enabled ) {
									$departure_date = strtotime( $start_recur );
									$departure_date = ! $departure_date ? $today : $departure_date + 24 * 60 * 60;

									$cut_off_time = $trip_cut_off_time * 60 * 60;
									$cut_off_time = 'days' === $cut_off_unit ? $cut_off_time * 24 : $cut_off_time;

									$valid_departure_date = strtotime( 'now' ) + $cut_off_time;

									if ( $valid_departure_date > $departure_date ) {
										continue;
									}
								}

								$date_key = strtotime( $start_recur );

								$booked = isset( $trip_booked_seats[ $date_key ]['booked'] ) ? $trip_booked_seats[ $date_key ]['booked'] : 0;

								$fsd_formated_array[] = array(
									'trip_id'      => $post_id,
									'content_id'   => strtotime( $start_recur ),
									'start_date'   => $start_recur,
									'end_date'     => $end_recur,
									'availability' => $availability,
									'seats_left'   => '' === trim( $seats_left ) ? $seats_left : absint( $seats_left ) - absint( $booked ),
									'fsd_cost'     => $fsd_cost,
								);

							}
						} else { // Non recurring.
							$date_key = strtotime( $start_date );
							// Filter Date.
							/**
							 * @since 2.1.0
							 */
							if ( $cutoff_enabled ) {

								$departure_date = $date_key;
								$departure_date = ! $departure_date ? $today : $departure_date + 24 * 60 * 60;

								$cut_off_time = $trip_cut_off_time * 60 * 60;
								$cut_off_time = 'days' === $cut_off_unit ? $cut_off_time * 24 : $cut_off_time;

								$valid_departure_date = strtotime( 'now' ) + $cut_off_time;

								if ( $valid_departure_date > $departure_date ) {
									continue;
								}
							}
							// Filter Date Ends.

							$booked               = isset( $trip_booked_seats[ $date_key ]['booked'] ) ? $trip_booked_seats[ $date_key ]['booked'] : 0;
							$fsd_formated_array[] = array(
								'trip_id'      => $post_id,
								'content_id'   => strtotime( $start_date ),
								'start_date'   => $start_date,
								'end_date'     => $end_date,
								'availability' => $availability,
								'seats_left'   => '' === trim( $seats_left ) ? $seats_left : absint( $seats_left ) - absint( $booked ),
								'fsd_cost'     => $fsd_cost,
							);
						}
					}
				}
				$fsd_indx++;
			}
		}

		array_multisort( array_map( 'strtotime', array_column( $fsd_formated_array, 'start_date' ) ), SORT_ASC, $fsd_formated_array );

		return $fsd_formated_array;

	}
}
new WTE_Fixed_Starting_Dates_Functions();
