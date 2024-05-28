<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wptravelengine.com/
 * @since      1.0.0
 *
 * @package    WTE_Fixed_Starting_Dates
 * @subpackage WTE_Fixed_Starting_Dates/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WTE_Fixed_Starting_Dates
 * @subpackage WTE_Fixed_Starting_Dates/public
 * @author     wptravelengine <test@test.com>
 */
class WTE_Fixed_Starting_Dates_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		if ( defined( 'WTE_FIXED_DEPARTURE_VERSION' ) ) {
			$this->version = WTE_FIXED_DEPARTURE_VERSION;
		} else {
			$this->version = $version;
		}

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WTE_Fixed_Starting_Dates_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WTE_Fixed_Starting_Dates_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$asset_script_path = '/min/';
		$version_prefix    = '-' . WTE_FIXED_DEPARTURE_VERSION;

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$asset_script_path = '/';
			$version_prefix    = '';
		}
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css' . $asset_script_path . 'wte-fixed-departure-dates-public' . $version_prefix . '.css', array( 'wte-select2' ), $this->version, 'all' );

		if ( is_singular( 'trip' ) ) {
			wp_enqueue_style( $this->plugin_name );
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WTE_Fixed_Starting_Dates_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WTE_Fixed_Starting_Dates_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$asset_script_path = '/min/';
		$version_prefix    = '-' . WTE_FIXED_DEPARTURE_VERSION;

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$asset_script_path = '/';
			$version_prefix    = '';
		}

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wte-fixed-departure-dates-public.js', array( 'jquery', 'wte-select2', 'moment' ), $this->version, true );

		global $post;

		if ( is_object( $post ) ) {

			$WTE_Fixed_Starting_Dates_setting = get_post_meta( $post->ID, 'WTE_Fixed_Starting_Dates_setting', true );
			$wte_fsd_booked_seats             = get_post_meta( $post->ID, 'wte_fsd_booked_seats', true );

			// $fsd_functions = new WTE_Fixed_Starting_Dates_Functions();

			// $sorted_fsd = $fsd_functions->get_formated_fsd_dates( $post->ID );

			// foreach ( $sorted_fsd as $key => $fsd ) {

			// $start_fsd_date  = isset( $fsd['start_date'] ) ? $fsd['start_date'] : '';
			// $content_id      = isset( $fsd['content_id'] ) ? $fsd['content_id'] : '';
			// $available_seats = isset( $fsd['seats_left'] ) ? $fsd['seats_left'] : 0;
			// $fsd_cost        = isset( $fsd['fsd_cost'] ) ? $fsd['fsd_cost'] : '';

			// $arr[] = array( $start_fsd_date => $fsd_cost );

			// Added seats available array to localization.
			// $seats_arr[] = array( $start_fsd_date => $available_seats );

			// $ids[ $start_fsd_date ] = $content_id;
			// }

			$use_multi_data = ! empty( $WTE_Fixed_Starting_Dates_setting['enable_multiple_pricing_fsd'] );

			// if ( isset( $arr ) && is_array( $arr ) ) {
				wp_localize_script(
					$this->plugin_name,
					'wte_fix_date',
					array(
						'ajaxurl'          => admin_url( 'admin-ajax.php' ),
						'cost'             => array(),
						'seats_available'  => array(),
						'enabled'          => $this->is_enabled(),
						'fdd_ids'          => array(),
						'use_multi_prices' => $use_multi_data,
						'departureDates'   => isset( $WTE_Fixed_Starting_Dates_setting['departure_dates'] ) ? $WTE_Fixed_Starting_Dates_setting['departure_dates'] : array(),
						'bookedSeats'      => $wte_fsd_booked_seats,
					)
				);
			// }
		}

		$globals_settings = wp_travel_engine_get_settings();

		wp_localize_script(
			$this->plugin_name,
			'fsd_settings',
			array(
				'pagination_number' => isset( $globals_settings['trip_dates']['pagination_number'] ) && ! empty( $globals_settings['trip_dates']['pagination_number'] ) ? $globals_settings['trip_dates']['pagination_number'] : 10,
			)
		);

		if ( is_singular( 'trip' ) ) {
			wp_enqueue_script( $this->plugin_name );
		}
	}

	function list_available_months( $radio = false ) {
		$dates  = __( 'Fixed Starting Dates', 'wte-fixed-departure-dates' );
		$months = \WTE_Fixed_Starting_Dates_Functions::get_trips_available_year_months();
		$today  = new \DateTime();

		echo '<div class="advanced-search-field trip-dates">'
		. '<h3 class="trip-fsd-title filter-section-title">' . apply_filters( 'wte-fixed-departure-dates-title', $dates ) . '</h3>';
		echo '<div class="filter-section-content">';
		if ( $radio ) {
			echo '<ul class="wte-fsd-list wte-terms-list">';
			foreach ( $months as $index => $date ) {
				if ( $today > $date ) {
					continue;
				}
				$checked = ! empty( $_REQUEST['trip-date-select'] ) && $date->format( 'Y-m' ) === $_REQUEST['trip-date-select'];
				echo '<li><label for="fsd_option_' . $index . '"><input id="fsd_option_' . $index . '" type="radio" name="trip-chosen-date" value="' . $date->format( 'Y-m' ) . '"' . checked( $checked, true, false ) . ' /><span>' . date_i18n( 'F, Y', $date->getTimestamp() ) . '</span></label></li>';
			}
			echo '</ul>';
		} else {
			echo '<div class="custom-select">';
			echo '<select class="trip-date-select" name="trip-date-select" data-placeholder="' . esc_attr_e( 'Choose a date&hellip;', 'wte-fixed-departure-dates' ) . '" class="wc-enhanced-select">';
			echo '<option value="">' . __( 'Choose a date', 'wte-fixed-departure-dates' ) . '</option>';
			foreach ( $months as $index => $date ) {
				if ( ( $today > $date ) ) {
					continue;
				}
				$selected = ! empty( $_REQUEST['trip-date-select'] ) && $date->format( 'Y-m' ) === $_REQUEST['trip-date-select'];
				echo '<option value="' . $date->format( 'Y-m' ) . '" ' . selected( $selected, true, false ) . '>' . date_i18n( 'F, Y', $date->getTimestamp() ) . '</option>';
			}
			echo '</select>';
			echo '</div>';
		}
		echo '</div>';
		echo '</div>';
	}

	function wte_departure_date_dropdown( $radio = false ) {

		if ( version_compare( WP_TRAVEL_ENGINE_VERSION, '5.0.0', '>=' ) ) {
			$this->list_available_months( $radio );
			return;
		}

		$dates = __( 'Fixed Starting Dates', 'wte-fixed-departure-dates' );

		global $post;
		$wte_doc_tax_post_args = array(
			'post_type'      => 'trip',   // Your Post type Name that You Registered
			'posts_per_page' => -1,
			'order'          => 'ASC',
		);
		$wte_doc_tax_post_qry  = new WP_Query( $wte_doc_tax_post_args );
		$max_cost              = 0;
		$max_duration          = 0;
		$start_date            = array();
		if ( $wte_doc_tax_post_qry->have_posts() ) :
			while ( $wte_doc_tax_post_qry->have_posts() ) :
				$wte_doc_tax_post_qry->the_post();

				$fsd_functions = new WTE_Fixed_Starting_Dates_Functions();
				$sorted_fsd    = $fsd_functions->get_formated_fsd_dates( get_the_ID() );
				// $sorted_fsd = WTE_Fixed_Starting_Dates_Functions::get_fsds_by_trip_id( get_the_ID() );
				if ( ! empty( $sorted_fsd ) ) {
					foreach ( $sorted_fsd as $key => $date ) {
						$year = substr( $date['start_date'], 0, 4 );
						preg_match( '~-(.*?)-~', $date['start_date'], $month );
						$cdate = $year . '-' . $month[1];
						if ( ! in_array( $cdate, $start_date ) ) {
							$start_date[] = $cdate;
						}
					}
				}
			endwhile;
		endif;

		if ( ! empty( $start_date ) ) :
			?>
			<div class="advanced-search-field trip-dates">
				<h3 class="trip-fsd-title"><?php echo apply_filters( 'wte-fixed-departure-dates-title', $dates ); ?></h3>
			<?php

			if ( $radio ) :
				?>
				<ul class="wte-fsd-list wte-terms-list">
					<?php
						$object = new WTE_Fixed_Starting_Dates_Shortcodes();
						usort( $start_date, array( $object, 'wte_date_sort' ) );
					foreach ( $start_date as $key => $value ) {
						$date = date_i18n( 'F, Y', strtotime( $value ) );
							echo '<li><label for="fsd_option_' . $key . '"><input id="fsd_option_' . $key . '" type="radio" name="trip-chosen-date" value="' . $value . '" /><span>' . esc_html( $date ) . '</span></label></li>';
					}
					?>
				</ul>
			<?php else : ?>
			<div class="custom-select">
				<select class="trip-date-select" name="trip-date-select" data-placeholder="<?php esc_attr_e( 'Choose a date&hellip;', 'wte-fixed-departure-dates' ); ?>" class="wc-enhanced-select">
					<option value=""><?php _e( 'Choose a date&hellip;', 'wte-fixed-departure-dates' ); ?></option>
					<?php
					$object = new WTE_Fixed_Starting_Dates_Shortcodes();
					usort( $start_date, array( $object, 'wte_date_sort' ) );
					foreach ( $start_date as $key => $value ) {
						$date = date_i18n( 'F, Y', strtotime( $value ) );
						if ( isset( $_GET['trip-date-select'] ) && $_GET['trip-date-select'] != '' ) {
							echo '<option value="' . $value . '"' . selected( $_GET['trip-date-select'], $value ) . '>' . esc_html( $date ) . '</option>';
						} else {
							echo '<option value="' . $value . '">' . esc_html( $date ) . '</option>';
						}
					}
					?>
				</select>
			</div>
			<?php endif; ?>
		</div>
			<?php
			endif;
	}

	private function is_enabled() {
		$settings = get_option( 'wp_travel_engine_settings', array() );

		return isset( $settings['departure']['section'] );
	}

	/**
	 * Update FSD Space left count.
	 *
	 * @return void
	 */
	public function update_fsd_space_left_count( $booking_id ) {
		if ( ! $this->is_enabled() ) {
			return;
		}

		global $wte_cart;

		$cart_items = $wte_cart->getItems();

		if ( ! empty( $cart_items ) ) :
			foreach ( $cart_items as $key => $item ) :
				$trip_id    = isset( $item['trip_id'] ) && ! empty( $item['trip_id'] ) ? $item['trip_id'] : false;
				$travellers = array_sum( $item['pax'] );
				$trip_date  = isset( $item['trip_date'] ) && ! empty( $item['trip_date'] ) ? $item['trip_date'] : false;

				$trip_date = ! empty( $item['trip_time'] ) ? $item['trip_time'] : $trip_date;

				if ( $trip_id && $trip_date ) :

					$trip_booked_seats = ! empty( get_post_meta( $trip_id, 'wte_fsd_booked_seats', true ) ) ? get_post_meta( $trip_id, 'wte_fsd_booked_seats', true ) : array();

					// $date_key = strtotime( $trip_date );
					try {
						$trip_date_obj = new \DateTime( $trip_date, new DateTimeZone( 'utc' ) );
					} catch ( \Exception $e ) {
						$trip_date_obj = new \DateTime();
					}
					$date_key = $trip_date_obj->getTimestamp();

					if ( is_array( $trip_booked_seats ) && isset( $trip_booked_seats[ $date_key ] ) ) {
						$trip_booked_seats[ $date_key ]['booked'] = $trip_booked_seats[ $date_key ]['booked'] + $travellers;
					} else {
						$trip_booked_seats[ $date_key ]['booked']  = $travellers;
						$trip_booked_seats[ $date_key ]['datestr'] = $date_key;
					}

					update_post_meta( $trip_id, 'wte_fsd_booked_seats', $trip_booked_seats );

					$wp_travel_engine_departure_settings = get_post_meta( $trip_id, 'WTE_Fixed_Starting_Dates_setting', true );

					if ( isset( $wp_travel_engine_departure_settings['departure_dates']['sdate'] ) && ! empty( $wp_travel_engine_departure_settings['departure_dates']['sdate'] ) ) :

						$start_dates = $wp_travel_engine_departure_settings['departure_dates']['sdate'];

						$start_dates = array_map(
							function( $date ) {
								return strtotime( $date );
							},
							$start_dates
						);

						$fdd_id = array_search( strtotime( $trip_date ), $start_dates );

						if ( $fdd_id ) :

							$seats_left = $wp_travel_engine_departure_settings['departure_dates']['seats_available'][ $fdd_id ] - $travellers;

							$wp_travel_engine_departure_settings['departure_dates']['seats_available'][ $fdd_id ] = $seats_left;

							// update_post_meta( $trip_id, 'WTE_Fixed_Starting_Dates_setting', $wp_travel_engine_departure_settings );

							// WPML Support added for space left.
							$wpml_active_langs = apply_filters( 'wpml_active_languages', null );

							if ( ! empty( $wpml_active_langs ) && is_array( $wpml_active_langs ) ) :

								foreach ( $wpml_active_langs as $lang_key => $lang_code ) :

									$translation_id = apply_filters( 'wpml_object_id', $trip_id, 'trip', false, $lang_key );

									if ( ! empty( $translation_id ) ) :

										// update_post_meta( $translation_id, 'WTE_Fixed_Starting_Dates_setting', $wp_travel_engine_departure_settings );

									endif;

								endforeach;

							endif;

						endif;

					endif;

				endif;

			endforeach;

		endif;
	}

			/**
			 * Checks and linit seats before adding to cart.
			 */
	function cart_pricing_check_seats_available( $trip_id, $trip_price, $trip_price_partial, $pax, $price_key, $attrs ) {
		if ( ! $this->is_enabled() ) {
			return;
		}

		$travellers = absint( array_sum( $pax ) );
		$trip_date  = $attrs['trip_date'];

		$fsd_functions = new WTE_Fixed_Starting_Dates_Functions();
		$sorted_fsd    = $fsd_functions->get_formated_fsd_dates( $trip_id );

		$selected_date = array_filter(
			$sorted_fsd,
			function( $stdate ) use ( $trip_date ) {
				return strtotime( $stdate['start_date'] ) === strtotime( $trip_date );
			}
		);

		if ( ! empty( $selected_date ) ) {
			foreach ( $selected_date as $key => $seld ) {
				$seats_left = absint( $seld['seats_left'] );
				if ( $seats_left < $travellers && class_exists( 'WTE_Notices' ) ) :
					WTE()->notices->add( sprintf( __( 'Selected seats(%1$s) unavailable for this date. Maximum number of pax allowed is : %2$s.', 'wte-fixed-departure-dates' ), $travellers, $seats_left ), 'error' );
				endif;
			}
		}

		// if ( isset( $wp_travel_engine_departure_settings['departure_dates']['sdate'] ) && ! empty( $wp_travel_engine_departure_settings['departure_dates']['sdate'] ) ) :

		// $start_dates = $wp_travel_engine_departure_settings['departure_dates']['sdate'];

		// $start_dates = array_map( function( $date ) {
		// return strtotime( $date );
		// }, $start_dates );

		// $fdd_id = array_search( strtotime( $trip_date ), $start_dates );

		// if ( $fdd_id ) :
		// $seats_left = absint( $wp_travel_engine_departure_settings['departure_dates']['seats_available'][$fdd_id] );
		// if ( $seats_left < $travellers && class_exists( 'WTE_Notices' ) ) :
		// WTE()->notices->add( sprintf( __( 'Selected seats(%1$s) unavailable for this date. Maximum number of pax allowed is : %2$s.', 'wte-fixed-departure-dates' ), $travellers, $seats_left ), 'error' );
		// endif;
		// endif;
		// endif;
	}

	/**
	 * Map Tab content.
	 */
	public function display_fsd_tab_content( $id, $field, $name, $icon ) {
		global $post;
		// $post_meta = get_post_meta($post->ID, 'wp_travel_engine_setting', true);

		$data = array(
			'post_id' => $post->ID,
		);

		do_action( 'wte_before_fsd_content' );
		?>

		<div class="post-data">
			<?php
				/**
				 * Hook - Display tab content title, left for themes.
				 */
				do_action( 'wte_fsd_tab_title' );
			?>
			<div class="content">
				<?php do_action( 'Wte_Fixed_Starting_Dates_Action', true ); ?>
			</div>
		</div>

		<?php
		do_action( 'wte_after_fsd_content' );
	}

	/**
	 * If FSD active and available.
	 *
	 * @return void
	 */
	function wte_is_fsd_active_available_callback( $active, $trip_id ) {
		if ( ! $this->is_enabled() ) {
			return false;
		}

		if ( function_exists( 'wte_get_trip' ) ) {
			$trip = wte_get_trip( $trip_id );

			$trip_available_months = get_post_meta( $trip_id, 'trip_available_months', true );
			return $trip_available_months !== '';
		}

		$wp_travel_engine_departure_settings = get_post_meta( $trip_id, 'WTE_Fixed_Starting_Dates_setting', true );

		if ( isset( $wp_travel_engine_departure_settings['departure_dates']['sdate'] ) && ! empty( $wp_travel_engine_departure_settings['departure_dates']['sdate'] ) ) {
			return true;
		}

		return false;
	}
}
