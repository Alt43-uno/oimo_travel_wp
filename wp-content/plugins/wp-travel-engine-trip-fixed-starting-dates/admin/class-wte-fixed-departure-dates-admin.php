<?php
use RRule\RRule;
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wptravelengine.com/
 * @since      1.0.0
 *
 * @package    WTE_Fixed_Starting_Dates
 * @subpackage WTE_Fixed_Starting_Dates/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WTE_Fixed_Starting_Dates
 * @subpackage WTE_Fixed_Starting_Dates/admin
 * @author     wptravelengine <test@test.com>
 */
class WTE_Fixed_Starting_Dates_Admin {

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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->init_hooks();
	}

	/**
	 * Init Hooks
	 *
	 * @since 2.3.3
	 */
	private function init_hooks() {
		add_action(
			'save_post_' . WP_TRAVEL_ENGINE_POST_TYPE,
			function( $post_ID, $post ) {
				if ( isset( $_POST['WTE_Fixed_Starting_Dates_setting'] ) ) {
					$previous_value = get_post_meta( $post_ID, 'WTE_Fixed_Starting_Dates_setting', true );
					$meta_value     = wp_unslash( $_POST['WTE_Fixed_Starting_Dates_setting'] );
					if ( isset( $meta_value['departure_dates']['section'] ) && 'no' == $meta_value['departure_dates']['section'] ) {
						unset( $meta_value['departure_dates']['section'] );
					}
					if ( is_array( $previous_value ) ) {
						$meta_value = wp_parse_args( $meta_value, $previous_value );
					}
					update_post_meta( $post_ID, 'WTE_Fixed_Starting_Dates_setting', $meta_value );
				}
			},
			11,
			2
		);
	}

	/**
	 * Register the stylesheets for the admin area.
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
		$screen = get_current_screen();
		if ( $screen->post_type == 'trip' || $screen->id == 'trip' ) {
			// wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wte-fixed-departure-dates-admin.css', array(), $this->version, 'all' );

			wp_enqueue_style( 'air-datepicker', plugin_dir_url( __FILE__ ) . 'css/air-datepicker.min.css' );

			wp_enqueue_style( 'magnific-popup', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css' );
			// wp_enqueue_style( 'jquery-nestable', plugin_dir_url( __FILE__ ) . 'css/jquery.nestable.css', array(), $this->version, 'all' );

			wp_enqueue_style( 'datepicker-style', plugin_dir_url( __FILE__ ) . 'css/datepicker-style.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
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
		$screen = get_current_screen();
		if ( $screen->post_type == 'trip' || $screen->id == 'trip' ) {
			wp_enqueue_script( 'jquery-datepicker-lib', plugin_dir_url( __FILE__ ) . 'js/air-datepicker-min.js', array( 'jquery' ), '2.2.3', true );
			wp_enqueue_script( 'jquery-datepicker-lang', plugin_dir_url( __FILE__ ) . 'js/air.datepicker.en.min.js', array( 'jquery' ), '2.2.3', true );

			wp_enqueue_script( 'magnific-popup', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js', array( 'jquery' ), '2.2.3', true );

			wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wte-fixed-departure-dates-admin.js', array( 'jquery', 'jquery-datepicker-lib', 'jquery-datepicker-lang' ), $this->version, true );

			$extra_array = array(
				'lang' => array(
					'are_you' => __( 'Are you sure?', 'wte-fixed-departure-dates' ),
				),
			);
			wp_localize_script( $this->plugin_name, 'WPTE_OBJ', $extra_array );
			wp_enqueue_script( $this->plugin_name );
		}
	}

	/**
	 * Function consists of html template for cloning.
	 *
	 * @since    1.0.0
	 */
	function fixed_departure_dates_html_templates() {
		$screen = get_current_screen();

		// Check if WYSIWYG is enabled
		if ( isset( $screen->post_type ) && 'trip' === $screen->post_type ) {

			$wp_travel_engine_setting = get_post_meta( get_the_ID(), 'wp_travel_engine_setting', true );

			$cost      = isset( $wp_travel_engine_setting['trip_price'] ) ? $wp_travel_engine_setting['trip_price'] : '';
			$prev_cost = isset( $wp_travel_engine_setting['trip_prev_price'] ) ? $wp_travel_engine_setting['trip_prev_price'] : '';

			if ( $cost != '' && isset( $wp_travel_engine_setting['sale'] ) ) {
				$price = $cost;
			} else {

				$price = isset( $wp_travel_engine_setting['trip_prev_price'] ) ? $wp_travel_engine_setting['trip_prev_price'] : '';
			}
			$availability_options = WTE_Fixed_Starting_Dates_Functions::availability();
			?>
			<div id="template-wrap1" class="template-wrapper clearfix" style="display: none;">
				<li class="dd-item dd3-item clearfix" data-id="{{index}}" id="">
				<div class="dd-handle dd3-handle"></div>
				<i class="dashicons dashicons-no-alt delete-dates delete-icon" data-id="{{index}}"></i>
					<a class="accordion-toggle" name="accordion-toggle" href="javascript:void(0);"><span class="dashicons dashicons-arrow-down custom-toggle-dates"></span></a>
					<div class="accordion-content clearfix" name="{{index}}">
						<div class="accordion-sdate">
							<label for="WTE_Fixed_Starting_Dates_setting[departure_dates][sdate][{{index}}]"><?php _e( 'Trip Starting Date:', 'wte-fixed-departure-dates' ); ?></label>
							<input type="text" class="datepicker" id="WTE_Fixed_Starting_Dates_setting[departure_dates][sdate][{{index}}]" name="WTE_Fixed_Starting_Dates_setting[departure_dates][sdate][{{index}}]" required/>
						</div>
						<div class="accordion-edate">
							<label for="WTE_Fixed_Starting_Dates_setting[departure_dates][edate][{{index}}]"><?php _e( 'Trip End Date:', 'wte-fixed-departure-dates' ); ?></label>
							<input type="text" class="datepicker" id="WTE_Fixed_Starting_Dates_setting[departure_dates][edate][{{index}}]" name="WTE_Fixed_Starting_Dates_setting[departure_dates][edate][{{index}}]" required/>
						</div>
						<div class="accordion-seats-available">
							<label for="WTE_Fixed_Starting_Dates_setting[departure_dates][cost][{{index}}]"><?php _e( 'Trip Cost:', 'wte-fixed-departure-dates' ); ?></label>
							<input type="number" min="0" name="WTE_Fixed_Starting_Dates_setting[departure_dates][cost][{{index}}]" id="WTE_Fixed_Starting_Dates_setting[departure_dates][cost][{{index}}]" value="<?php echo $price; ?>">
						</div>
						<div class="accordion-seats-availability">
							<label for="WTE_Fixed_Starting_Dates_setting[departure_dates][availability_type][{{index}}]"><?php _e( 'Trip Availability:', 'wte-fixed-departure-dates' ); ?></label>
							<select name="WTE_Fixed_Starting_Dates_setting[departure_dates][availability_type][{{index}}]" id="WTE_Fixed_Starting_Dates_setting[departure_dates][availability_type][{{index}}]">
								<?php
								foreach ( $availability_options as $key => $value ) {
									?>
									<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value ); ?></option>
									<?php
								}
								?>
							</select>
						</div>
						<div class="accordion-seats-available">
							<label for="WTE_Fixed_Starting_Dates_setting[departure_dates][seats_available][{{index}}]"><?php _e( 'Number of Available Spot:', 'wte-fixed-departure-dates' ); ?></label>
							<input type="number" class="seats-available-{{index}}" name="WTE_Fixed_Starting_Dates_setting[departure_dates][seats_available][{{index}}]" id="WTE_Fixed_Starting_Dates_setting[departure_dates][seats_available][{{index}}]">
						</div>
					</div>
				</li>
			</div>
			<?php
		}
	}

	public function fixed_departure_dates_settings() {
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings' );
		if ( isset( $wp_travel_engine_settings['departure']['section'] ) && is_array( $wp_travel_engine_settings['departure']['section'] ) ) {
			$wp_travel_engine_settings['departure']['section'] = end( $wp_travel_engine_settings['departure']['section'] );
		}
		$option       = isset( $wp_travel_engine_settings['departure']['section'] ) ? $wp_travel_engine_settings['departure']['section'] : '';
		$title        = isset( $wp_travel_engine_settings['departure']['section_title'] ) ? esc_attr( $wp_travel_engine_settings['departure']['section_title'] ) : 'Join Our Fixed Trip Starting Date';
		$dates_layout = isset( $wp_travel_engine_settings['fsd_dates_layout'] ) && '' != $wp_travel_engine_settings['fsd_dates_layout'] ? $wp_travel_engine_settings['fsd_dates_layout'] : 'dates_list';
		?>
			<div class="departure-status-options">
				<h3 class="status-options"><?php _e( 'Fixed Starting Dates Settings', 'wte-fixed-departure-dates' ); ?></h3>
				<div class="departure-dates-options">
					<label for="wp_travel_engine_settings[departure][section]"><?php _e( 'Show Fixed Trip Starts Dates section:', 'wte-fixed-departure-dates' ); ?><span class="tooltip" title="<?php _e( 'Check this if you want to enable fixed trip starting dates section between featured image/slider and trip content sections.', 'wte-fixed-departure-dates' ); ?>"><i class="fas fa-question-circle"></i></span></label>
					<input type="checkbox" name="wp_travel_engine_settings[departure][section]" class="disable_notif" id="wp_travel_engine_settings[departure][section]" value="1" <?php echo checked( '1', $option ); ?>/>
					<label for="wp_travel_engine_settings[departure][section]" class="checkbox-label"></label>
				</div>
				<div class="departure-dates-options">
					<label for="wp_travel_engine_settings[departure][section_title]"><?php _e( 'Fixed Starting Dates Section Title:', 'wte-fixed-departure-dates' ); ?><span class="tooltip" title="<?php _e( 'Title for Fixed Starting Dates of the trip.', 'wte-fixed-departure-dates' ); ?>"><i class="fas fa-question-circle"></i></span></label>
					<input type="text" name="wp_travel_engine_settings[departure][section_title]" class="disable_notif" id="wp_travel_engine_settings[departure][section_title]" value="<?php echo esc_attr( $title ); ?>"/>

				</div>
				<div class="wp-travel-engine-settings">
					<label for="wp_travel_engine_settings[fsd_dates_layout]"><?php _e( 'Select the dates layout : ', 'wte-fixed-departure-dates' ); ?> <span class="tooltip" title="<?php _e( 'Choose a dates list or months layout to display in taxonomy pages.', 'wte-fixed-departure-dates' ); ?>"><i class="fas fa-question-circle"></i></span></label>

					<div class="wte-dates-layout-holder">
						<div class="wte-dates-layout">
							<h4><?php esc_html_e( '1. Show dates list', 'wte-fixed-departure-dates' ); ?></h4>
							<label for="wte_fsd_dates_list_layout">
								<figure>
									<input value="dates_list" id="wte_fsd_dates_list_layout" name="wp_travel_engine_settings[fsd_dates_layout]" type="radio" hidden="hidden" <?php checked( 'dates_list', $dates_layout, true ); ?>>
									<img src="<?php echo WTE_FIXED_DEPARTURE_FILE_URL . '/admin/css/images/dates-list.png'; ?>" alt="dates list layout">
								</figure>
							</label>
						</div>
						<div class="wte-dates-layout">
							<h4><?php esc_html_e( '2. Show months list', 'wte-fixed-departure-dates' ); ?></h4>
							<label for="wte_fsd_months_list_layout">
								<figure>
									<input id="wte_fsd_months_list_layout" value="months_list" name="wp_travel_engine_settings[fsd_dates_layout]" type="radio" hidden="hidden" <?php checked( 'months_list', $dates_layout, true ); ?>>
									<img src="<?php echo WTE_FIXED_DEPARTURE_FILE_URL . '/admin/css/images/months-list.png'; ?>" alt="months list layout">
								</figure>
							</label>
						</div>
					</div>
				</div>
			</div>
		<?php
	}

	function wp_travel_engine_starting_dates_form() {
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
		?>
		<div class="wpte-field wpte-checkbox advance-checkbox">
			<label class="wpte-field-label" for="wp_travel_engine_settings[trip_search][dates]"><?php _e( 'Hide Fixed Starting Dates', 'wte-fixed-departure-dates' ); ?></label>
			<div class="wpte-checkbox-wrap">
				<input type="checkbox" id="wp_travel_engine_settings[trip_search][dates]" name="wp_travel_engine_settings[trip_search][dates]" value="1"
				<?php
				if ( isset( $wp_travel_engine_settings['trip_search']['dates'] ) ) {
					checked( $wp_travel_engine_settings['trip_search']['dates'], 1 );}
				?>
				>
				<label for="wp_travel_engine_settings[trip_search][dates]"></label>
			</div>
			<span class="wpte-tooltip"><?php esc_html_e( 'Check the above checkbox to hide Dates field in the Trip Search Form.', 'wte-fixed-departure-dates' ); ?></span>
		</div>
		<?php
	}

	/**
	 * This will uninstall this plugin if parent WP Travel plugin not found.
	 *
	 * @since 1.0.0
	 */
	public function check_dependency() {

		if ( ! class_exists( 'Wp_Travel_Engine' ) || ! $this->meets_requirements() ) {
			echo '<div class="error">';
			echo wp_kses_post(
				'
				<p>
					<strong>
						WP Travel Engine - Fixed Starting Dates
					</strong>
					requires the <a href="https://wptravelengine.com" target="__blank">WP Travel Engine</a>.
						Please install and activate the latest WP Travel Engine plugin first.
						<b>WP Travel Engine - Fixed Starting Dates will be deactivated now.</b>
				</p>'
			);
			echo '</div>';

			// Deactivate Plugins.
			deactivate_plugins( plugin_basename( WTE_FIXED_DEPARTURE_FILE_PATH ) );
		}
	}

	/**
	 * Check if all plugin requirements are met.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if requirements are met, otherwise false.
	 */
	private function meets_requirements() {
		return ( class_exists( 'WP_Travel_Engine' ) && defined( 'WP_TRAVEL_ENGINE_VERSION' ) && version_compare( WP_TRAVEL_ENGINE_VERSION, '4.1.1', '>=' ) );
	}


	/**
	 * This will add a Availability tab in trip posts.
	 *
	 * @since 2.0.0
	 */
	public function add_fsd_availability_tab( $trip_meta_tabs ) {

		unset( $trip_meta_tabs['wpte-availability-upsell'] );

		$trip_meta_tabs['wpte-availability'] =
		array(
			'tab_label'         => __( 'Dates', 'wte-fixed-departure-dates' ),
			'tab_heading'       => __( 'Dates', 'wte-fixed-departure-dates' ),
			'content_path'      => plugin_dir_path( __FILE__ ) . '/partials/wte-fixed-departure-dates-admin-display.php',
			'callback_function' => 'wpte_edit_trip_tab_availability',
			'content_key'       => 'wpte-availability',
			'current'           => false,
			'content_loaded'    => false,
			'priority'          => 60,
		);
		return $trip_meta_tabs;
	}

	/**
	 * Save Availability tab meta data in trip posts.
	 *
	 * @since 2.0.0
	 */
	public function save_fsd_meta_data( $post_id, $meta ) {

		if ( isset( $meta['WTE_Fixed_Starting_Dates_setting']['departure_dates']['section'] ) && 'no' === $meta['WTE_Fixed_Starting_Dates_setting']['departure_dates']['section'] ) {
			unset( $meta['WTE_Fixed_Starting_Dates_setting']['departure_dates']['section'] );
		}
		if ( isset( $meta['WTE_Fixed_Starting_Dates_setting'] ) ) {
			$array_settings  = $meta['WTE_Fixed_Starting_Dates_setting'];
			$list_serialized = array();

			if ( isset( $meta['WTE_Fixed_Starting_Dates_setting']['departure_dates']['sdate'] ) ) {
				$arr_keys = array_keys( $array_settings['departure_dates']['sdate'] );

				foreach ( $arr_keys as $key => $value ) {

					if ( isset( $array_settings['departure_dates']['cost'][ $value ] ) && $array_settings['departure_dates']['cost'][ $value ] != '' && ! absint( $array_settings['departure_dates']['cost'][ $value ] ) ) {
						wp_send_json_error( array( 'message' => __( 'For all fixed departures, please insert valid cost.', 'wte-fixed-departure-dates' ) ) );
					}
					$list_serialized[]['id'] = $value;
				}

				$list_serialized = json_encode( $list_serialized );
			}

			$fsd_saved = get_post_meta( $post_id, 'WTE_Fixed_Starting_Dates_setting', true );

			if ( empty( $fsd_saved ) ) {
				$fsd_saved = array();
			}

			$meta_to_save = isset( $meta['WTE_Fixed_Starting_Dates_setting'] ) ? $meta['WTE_Fixed_Starting_Dates_setting'] : array();

			// Merge data.
			$metadata_merged_with_saved = array_merge( $fsd_saved, $meta_to_save );

			$checkboxes_array = array(
				'recurring_enable',
				'enable_multiple_pricing_fsd',
			);

			$trip_meta_checkboxes = apply_filters( 'fsd_trip_meta_checkboxes', $checkboxes_array );

			foreach ( $trip_meta_checkboxes as $checkbox ) {
				if ( isset( $metadata_merged_with_saved[ $checkbox ] ) && ! isset( $meta_to_save[ $checkbox ] ) ) {
					unset( $metadata_merged_with_saved[ $checkbox ] );
				}
			}

			$obj      = new Wp_Travel_Engine_Functions();
			$settings = $obj->wte_sanitize_array( $metadata_merged_with_saved );

			update_post_meta( $post_id, 'WTE_Fixed_Starting_Dates_setting', $settings );
			update_post_meta( $post_id, 'list_serialized', $list_serialized );

			$posted_fsd = isset( $_POST['WTE_Fixed_Starting_Dates_setting']['departure_dates']['sdate'] ) && ! empty( $_POST['WTE_Fixed_Starting_Dates_setting']['departure_dates']['sdate'] ) ? $_POST['WTE_Fixed_Starting_Dates_setting']['departure_dates']['sdate'] : array();

			if ( ! empty( $posted_fsd ) ) {
				$fsd_dts_array = array();
				foreach ( $posted_fsd as $key => $sdate ) {
					$recur_enable = isset( $_POST['WTE_Fixed_Starting_Dates_setting']['departure_dates'][ $key ]['recurring']['enable'] ) && 'true' === $_POST['WTE_Fixed_Starting_Dates_setting']['departure_dates'][ $key ]['recurring']['enable'] ? true : false;

					if ( $recur_enable ) {
						$recursion       = $_POST['WTE_Fixed_Starting_Dates_setting']['departure_dates'][ $key ]['recurring'];
						$recursion_type  = isset( $recursion['type'] ) ? $recursion['type'] : 'DAILY';
						$recurring_limit = isset( $recursion['limit'] ) ? $recursion['limit'] : 10;
						$months_recur    = isset( $recursion['months'] ) ? $recursion['months'] : array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12' );
						$weekdays_recur  = isset( $recursion['week_days'] ) ? $recursion['week_days'] : array( 'MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU' );

						$recurr_args = array(
							'FREQ'     => $recursion_type,
							'INTERVAL' => 1,
							'DTSTART'  => $sdate,
							'COUNT'    => $recurring_limit,
						);

						if ( 'MONTHLY' === $recursion_type ) {
							$recurr_args['BYMONTH'] = array_values( $months_recur );
						}

						if ( 'WEEKLY' === $recursion_type ) {
							$recurr_args['BYDAY'] = array_values( $weekdays_recur );
						}
						$rrule = new RRule( $recurr_args );

						foreach ( $rrule as $occurrence ) {
							$start_recur     = $occurrence->format( 'Y-m-d' );
							$fsd_dts_array[] = $start_recur;
						}
					} else {
						$fsd_dts_array[] = $sdate;
					}
				}
				update_post_meta( $post_id, 'wte_fsd_dates_starts', $fsd_dts_array );
			}
		}

	}

	/**
	 * Refresh dates FSD for search.
	 */
	function wte_fsd_refresh_fsd_dts() {

		$fsd_dates_updted = get_option( 'wpte_fsd_dates_updted_for_filter', false );

		if ( $fsd_dates_updted ) {
			return false;
		}

		$wte_trp_args         = array(
			'post_type'      => 'trip',
			'posts_per_page' => -1,
			'order'          => 'ASC',
		);
		$wte_doc_tax_post_qry = new WP_Query( $wte_trp_args );

		if ( $wte_doc_tax_post_qry->have_posts() ) :
			while ( $wte_doc_tax_post_qry->have_posts() ) :
				$wte_doc_tax_post_qry->the_post();

				$fsd_functions = new WTE_Fixed_Starting_Dates_Functions();
				$sorted_fsd    = $fsd_functions->get_formated_fsd_dates( get_the_ID() );

				if ( ! empty( $sorted_fsd ) ) {

					$fsd_dts_array = array();
					foreach ( $sorted_fsd as $key => $sdate ) {
						$fsd_dts_array[] = $sdate['start_date'];
					}

					update_post_meta( get_the_ID(), 'wte_fsd_dates_starts', $fsd_dts_array );
				}
		endwhile;
			wp_reset_postdata();
	endif;
		wp_reset_query();

		// Update filter.
		update_option( 'wpte_fsd_dates_updted_for_filter', true );

		return;

	}

	/**
	 * Save Availability tab meta data in trip posts.
	 *
	 * @since 2.0.0
	 */
	function save_wte_fixed_starting_dates_meta( $post_id, $post, $update = false ) {

		if ( isset( $_POST['WTE_Fixed_Starting_Dates_setting'] ) ) {
			$array_settings  = $_POST['WTE_Fixed_Starting_Dates_setting'];
			$list_serialized = array();

			if ( isset( $_POST['WTE_Fixed_Starting_Dates_setting']['departure_dates']['sdate'] ) ) {
				$arr_keys = array_keys( $array_settings['departure_dates']['sdate'] );

				foreach ( $arr_keys as $key => $value ) {

					if ( isset( $array_settings['departure_dates']['cost'][ $value ] ) && $array_settings['departure_dates']['cost'][ $value ] != '' && ! absint( $array_settings['departure_dates']['cost'][ $value ] ) ) {
						wp_send_json_error( array( 'message' => __( 'For all fixed departures, please insert valid cost.', 'wte-fixed-departure-dates' ) ) );
					}
					$list_serialized[]['id'] = $value;
				}

				$list_serialized = json_encode( $list_serialized );
			}

			$fsd_saved = get_post_meta( $post_id, 'WTE_Fixed_Starting_Dates_setting', true );

			if ( empty( $fsd_saved ) ) {
				$fsd_saved = array();
			}

			$meta_to_save = isset( $_POST['WTE_Fixed_Starting_Dates_setting'] ) ? $_POST['WTE_Fixed_Starting_Dates_setting'] : array();

			// Merge data.
			$metadata_merged_with_saved = array_merge( $fsd_saved, $meta_to_save );

			$checkboxes_array = array(
				'recurring_enable',
				'enable_multiple_pricing_fsd',
			);

			$trip_meta_checkboxes = apply_filters( 'fsd_trip_meta_checkboxes', $checkboxes_array );

			foreach ( $trip_meta_checkboxes as $checkbox ) {
				if ( isset( $metadata_merged_with_saved[ $checkbox ] ) && ! isset( $meta_to_save[ $checkbox ] ) ) {
					unset( $metadata_merged_with_saved[ $checkbox ] );
				}
			}

			$obj      = new Wp_Travel_Engine_Functions();
			$settings = $obj->wte_sanitize_array( $metadata_merged_with_saved );

			update_post_meta( $post_id, 'WTE_Fixed_Starting_Dates_setting', $settings );
			update_post_meta( $post_id, 'list_serialized', $list_serialized );

			// Recurring dates meta support.
			$posted_fsd = isset( $_POST['WTE_Fixed_Starting_Dates_setting']['departure_dates']['sdate'] ) && ! empty( $_POST['WTE_Fixed_Starting_Dates_setting']['departure_dates']['sdate'] ) ? $_POST['WTE_Fixed_Starting_Dates_setting']['departure_dates']['sdate'] : array();

			if ( ! empty( $posted_fsd ) ) {
				$fsd_dts_array = array();
				foreach ( $posted_fsd as $key => $sdate ) {
					$recur_enable = isset( $_POST['WTE_Fixed_Starting_Dates_setting']['departure_dates'][ $key ]['recurring']['enable'] ) && 'true' === $_POST['WTE_Fixed_Starting_Dates_setting']['departure_dates'][ $key ]['recurring']['enable'] ? true : false;

					if ( $recur_enable ) {
						$recursion       = $_POST['WTE_Fixed_Starting_Dates_setting']['departure_dates'][ $key ]['recurring'];
						$recursion_type  = isset( $recursion['type'] ) ? $recursion['type'] : 'DAILY';
						$recurring_limit = isset( $recursion['limit'] ) ? $recursion['limit'] : 10;
						$months_recur    = isset( $recursion['months'] ) ? $recursion['months'] : array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12' );
						$weekdays_recur  = isset( $recursion['week_days'] ) ? $recursion['week_days'] : array( 'MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU' );

						$recurr_args = array(
							'FREQ'     => $recursion_type,
							'INTERVAL' => 1,
							'DTSTART'  => $sdate,
							'COUNT'    => $recurring_limit,
						);

						if ( 'MONTHLY' === $recursion_type ) {
							$recurr_args['BYMONTH'] = array_values( $months_recur );
						}

						if ( 'WEEKLY' === $recursion_type ) {
							$recurr_args['BYDAY'] = array_values( $weekdays_recur );
						}
						$rrule = new RRule( $recurr_args );

						foreach ( $rrule as $occurrence ) {
							$start_recur     = $occurrence->format( 'Y-m-d' );
							$fsd_dts_array[] = $start_recur;
						}
					} else {
						$fsd_dts_array[] = $sdate;
					}
				}
				update_post_meta( $post_id, 'wte_fsd_dates_starts', $fsd_dts_array );
			}
		}

	}

	function WTE_Fixed_Starting_Dates_alter_seat_available( $post_ID, $post, $update = false ) {
		// global $post;

		if ( ! is_admin() ) {
			return;
		}

		// if ( ! is_object( $post ) || ! isset( $post->post_type ) ) {
		// return;
		// }

		// if ( $post->post_type != 'booking' ) {
		// return;
		// }

		// if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		// return;
		// }

		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return;
		}

		$payment_status                     = isset( $_POST['wp_travel_engine_booking_status'] ) ? sanitize_text_field( $_POST['wp_travel_engine_booking_status'] ) : 'booked';
		$wp_travel_engine_postmeta_settings = get_post_meta( $post->ID, 'wp_travel_engine_booking_setting', true );
		$wpte_booking_reset_seat_flag       = get_post_meta( $post->ID, 'wpte_booking_reset_seat_flag', true );

		if ( isset( $payment_status ) ) {
			$options              = function_exists( 'wp_travel_engine_get_booking_status' ) ? wp_travel_engine_get_booking_status() : array();
			$options_keys         = array_keys( $options );
			$option_excluded_keys = array( 'pending', 'booked' );
			if ( in_array( $payment_status, $options_keys ) && ! in_array( $payment_status, $option_excluded_keys ) ) {

				$tid                              = isset( $wp_travel_engine_postmeta_settings['place_order']['tid'] ) ? $wp_travel_engine_postmeta_settings['place_order']['tid'] : '';
				$wpte_booking_flag                = 'true';
				$fsd_functions                    = new WTE_Fixed_Starting_Dates_Functions();
				$WTE_Fixed_Starting_Dates_setting = get_post_meta( $tid, 'WTE_Fixed_Starting_Dates_setting', true );
				$sortable_settings                = get_post_meta( $tid, 'list_serialized', true );
				$trip_booked_seats                = get_post_meta( $tid, 'wte_fsd_booked_seats', true );
				$pax_by_cart_id                   = get_post_meta( $tid, 'wte_pax_by_cart_id', true );

				if ( ! is_array( $pax_by_cart_id ) ) {
					$pax_by_cart_id = array();
				}
				if ( ! is_array( $trip_booked_seats ) ) {
					$trip_booked_seats = array();
				}

				if ( isset( $post->order_trips ) && is_array( $post->order_trips ) ) {
					foreach ( $post->order_trips as $cart_id => $order_trip ) {
						$unbook_pax = 0;
						try {
							if ( isset( $_POST['order_trips'][ $cart_id ]['datetime'] ) ) {
								$booked_date_as_key = new \DateTime( $_POST['order_trips'][ $cart_id ]['datetime'] );
								$booked_date_as_key = $booked_date_as_key->getTimestamp();
							} else {
								$booked_date_as_key = new \DateTime( $order_trip['datetime'] );
								$booked_date_as_key = $booked_date_as_key->getTimestamp();
							}
						} catch ( \Exception $e ) {
							$booked_date_as_key = -1;
						}
						if ( isset( $_POST['order_trips'][ $cart_id ]['pax'] ) ) {
							$unbook_pax = is_array( $_POST['order_trips'][ $cart_id ]['pax'] ) ? (int) array_sum( $_POST['order_trips'][ $cart_id ]['pax'] ) : 0;
						} else {
							$unbook_pax = is_array( $order_trip['pax'] ) ? (int) array_sum( $order_trip['pax'] ) : 0;
						}

						if ( ! isset( $pax_by_cart_id[ $cart_id ] ) ) {
							$pax_by_cart_id[ $cart_id ] = $unbook_pax;
						}

						if ( $booked_date_as_key > 0 ) {

							if ( ! isset( $trip_booked_seats[ $booked_date_as_key ]['booked'] ) ) {
								$trip_booked_seats[ $booked_date_as_key ]['booked'] = $unbook_pax;
							}

							$booked_pax = (int) $trip_booked_seats[ $booked_date_as_key ]['booked'];
							if ( $pax_by_cart_id[ $cart_id ] - $unbook_pax >= 0 ) {
								$pax_by_cart_id[ $cart_id ] = $pax_by_cart_id[ $cart_id ] - $unbook_pax;
								// $booked_pax = $trip_booked_seats[ $booked_date_as_key ]['booked']
								$booked_pax = (int) $trip_booked_seats[ $booked_date_as_key ]['booked'] - $unbook_pax;
								$trip_booked_seats[ $booked_date_as_key ]['booked'] = $booked_pax;
							}
						}
						// $pax_by_cart_id[ $cart_id ] = $unbook_pax;
					}
				} elseif ( isset( $wp_travel_engine_postmeta_settings['place_order']['datetime'] ) ) {
					$booked_date     = new DateTime( $wp_travel_engine_postmeta_settings['place_order']['datetime'], new DateTimeZone( 'utc' ) );
					$booked_date_key = $booked_date->getTimestamp();
					if ( $trip_booked_seats[ $booked_date_key ] ) {
						$total_booked                                    = (int) $trip_booked_seats[ $booked_date_key ]['booked'];
						$canceled_booking                                = (int) $wp_travel_engine_postmeta_settings['place_order']['traveler'];
						$trip_booked_seats[ $booked_date_key ]['booked'] = $total_booked - $canceled_booking < 0 ? 0 : $total_booked - $canceled_booking;
					}
				}

				update_post_meta( $tid, 'wte_pax_by_cart_id', $pax_by_cart_id );
				update_post_meta( $tid, 'wte_fsd_booked_seats', $trip_booked_seats );
				if ( $wpte_booking_flag === 'true' ) {
					update_post_meta( $post->ID, 'wpte_booking_reset_seat_flag', 'true' );
				}
				return;

				/**
				 *
				 * @todo remove the code below in future releases(> 2.1.1) since Implemented new way to update seats. left it for future reference.
				 */
				if ( ! is_array( $sortable_settings ) ) {
					$sortable_settings = json_decode( $sortable_settings );
				}
				$sorted_fsd = $fsd_functions->get_formated_fsd_dates( $tid );
				if ( ( isset( $sorted_fsd ) && ! empty( $sorted_fsd ) ) && ( isset( $trip_booked_seats ) && ! empty( $trip_booked_seats ) ) && $wpte_booking_reset_seat_flag == false ) {
					foreach ( $sortable_settings as $content ) {
						$recurring_enable = isset( $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['enable'] );
						$start_date       = isset( $WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'][ $content->id ] ) ? $WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'][ $content->id ] : '';
						$end_date         = isset( $WTE_Fixed_Starting_Dates_setting['departure_dates']['edate'][ $content->id ] ) ? $WTE_Fixed_Starting_Dates_setting['departure_dates']['edate'][ $content->id ] : '';
						$traveler         = isset( $wp_travel_engine_postmeta_settings['place_order']['traveler'] ) ? $wp_travel_engine_postmeta_settings['place_order']['traveler'] : 1;
						if ( $recurring_enable ) {
							$recursion_type  = isset( $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['type'] ) ? $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['type'] : 'DAILY';
							$recurring_limit = isset( $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['limit'] ) ? $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['limit'] : 10;
							$months_recur    = isset( $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['months'] ) && ! empty( $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['months'] ) ? $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['months'] : array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12' );
							$weekdays_recur  = isset( $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['week_days'] ) && ! empty( $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['week_days'] ) ? $WTE_Fixed_Starting_Dates_setting['departure_dates'][ $content->id ]['recurring']['week_days'] : array( 'MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU' );
							$recurr_args     = array(
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
							$rrule = new RRule( $recurr_args );
							foreach ( $rrule as $occurrence ) {
								$start_recur = $occurrence->format( 'Y-m-d' );
								if ( $dur_days ) {
									$date = new DateTime( $start_recur );
									$date->add( new DateInterval( 'P' . $dur_days . 'D' ) );
									$end_recur = $date->format( 'Y-m-d' );
								} else {
									$end_recur = date_i18n( 'Y-m-d', strtotime( $start_recur ) );
								}
								$date_key        = strtotime( $start_recur );
								$trip_start_date = isset( $wp_travel_engine_postmeta_settings['place_order']['datetime'] ) ? strtotime( $wp_travel_engine_postmeta_settings['place_order']['datetime'] ) : '';
								$booked          = isset( $trip_booked_seats[ $date_key ]['booked'] ) ? $trip_booked_seats [ $date_key ]['booked'] : 0;
								if ( ! empty( $trip_start_date ) && $date_key == $trip_start_date ) {
									$updated_booked_seat = ( isset( $booked ) && $booked > 0 ) && $traveler <= $booked ? $booked - $traveler : $booked;
									if ( $booked == $updated_booked_seat ) {
										$wpte_booking_flag = 'false';
									}
									$trip_booked_seats[ $trip_start_date ]['booked'] = $updated_booked_seat;
								}
							}
						} else {
							foreach ( $sorted_fsd as $sfsd_k => $sfsd_v ) {
								foreach ( $trip_booked_seats as $tbs_k => $tbs_v ) {
									if ( $tbs_v['datestr'] == $sfsd_v['content_id'] ) {
										$sfsd_content_id     = $sfsd_v['content_id'];
										$updated_booked_seat = ( isset( $tbs_v['booked'] ) && $tbs_v['booked'] > 0 ) ? $tbs_v['booked'] - $traveler : $tbs_v['booked'];
										if ( $updated_booked_seat >= $sfsd_v['seats_left'] ) {
											if ( $tbs_v['booked'] == $updated_booked_seat ) {
												$wpte_booking_flag = 'false';
											}
											$trip_booked_seats[ $sfsd_content_id ]['booked'] = $updated_booked_seat;
										}
									}
								}
							}
						}
					}
					update_post_meta( $tid, 'wte_fsd_booked_seats', $trip_booked_seats );
					/** To avoid re-processing of booking seat, if the non payed option is selected */
					if ( $wpte_booking_flag === 'true' ) {
						update_post_meta( $post->ID, 'wpte_booking_reset_seat_flag', 'true' );
					}
				}
			}
		}
	}

	/**
	 * Global settings array register.
	 *
	 * @return void
	 */
	function wte_fsd_extension_settings( $extension_settings ) {
		$extension_settings['wte_fsd'] = array(
			'label'        => __( 'Fixed Starting Dates', 'wte-fixed-departure-dates' ),
			'content_path' => plugin_dir_path( WTE_FIXED_DEPARTURE_FILE_PATH ) . 'includes/backend/settings/global-settings.php',
			'current'      => true,
		);
		return $extension_settings;
	}
}
