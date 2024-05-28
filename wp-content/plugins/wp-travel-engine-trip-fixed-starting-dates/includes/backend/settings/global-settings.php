<?php
/**
 * Global Settings
 */
$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings' );
$dates_layout              = isset( $wp_travel_engine_settings['fsd_dates_layout'] ) && '' != $wp_travel_engine_settings['fsd_dates_layout'] ? $wp_travel_engine_settings['fsd_dates_layout'] : 'dates_list';
$hide_dates_layout         = isset( $wp_travel_engine_settings['departure']['hide_availability_section'] ) && 'yes' === $wp_travel_engine_settings['departure']['hide_availability_section'];
?>
<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" for="wp_travel_engine_settings[departure][section]"><?php _e( 'Show Fixed Trip Starts Dates section', 'wte-fixed-departure-dates' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="checkbox" id="wp_travel_engine_settings[departure][section]" name="wp_travel_engine_settings[departure][section]" value="1"
		<?php
		if ( isset( $wp_travel_engine_settings['departure']['section'] ) && $wp_travel_engine_settings['departure']['section'] != '' ) {
			echo 'checked';}
		?>
		>
		<label for="wp_travel_engine_settings[departure][section]"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'Check this if you want to enable fixed trip starting dates section between featured image/slider and trip content sections.', 'wte-fixed-departure-dates' ); ?></span>
</div>

<div class="wpte-field wpte-text wpte-floated">
	<label for="wp_travel_engine_settings[departure][section_title]" class="wpte-field-label"><?php _e( 'Fixed Starting Dates Section Title', 'wte-fixed-departure-dates' ); ?></label>
	<input type="text" id="wp_travel_engine_settings[departure][section_title]" name="wp_travel_engine_settings[departure][section_title]" value="<?php echo isset( $wp_travel_engine_settings['departure']['section_title'] ) ? esc_attr( $wp_travel_engine_settings['departure']['section_title'] ) : ''; ?>">
	<span class="wpte-tooltip"><?php esc_html_e( 'Title for Fixed Starting Dates of the trip.', 'wte-fixed-departure-dates' ); ?></span>
</div>

<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" for="wp_travel_engine_settings_hide_availability_section_show"><?php _e( 'Hide dates layout from trip cards', 'wte-fixed-departure-dates' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="checkbox" id="wp_travel_engine_settings_hide_availability_section_hide" name="wp_travel_engine_settings[departure][hide_availability_section]" value="" checked/>
		<input type="checkbox" id="wp_travel_engine_settings_hide_availability_section_show" name="wp_travel_engine_settings[departure][hide_availability_section]" value="yes" <?php checked( true, $hide_dates_layout ); ?>/>
		<label for="wp_travel_engine_settings_hide_availability_section_show"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'Check this if you want to hide the availability section from the trip cards in homepage and archive pages.', 'wte-fixed-departure-dates' ); ?></span>
</div>

<div class="wpte-field ">
	<label class="wpte-field-label" for="wp_travel_engine_settings[fsd_dates_layout]">
		<?php _e( 'Select the dates layout', 'wte-fixed-departure-dates' ); ?>
	</label>

	<div class="wte-dates-layout-holder wpte-floated">
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
	<span class="wpte-tooltip"><?php esc_html_e( 'Choose a dates list or months layout to display in taxonomy pages.', 'wte-fixed-departure-dates' ); ?></span>
</div>

<div class="wpte-field wpte-number wpte-floated">
	<label class="wpte-field-label" for="wp_travel_engine_settings[trip_dates][number]"><?php _e( 'Number of Trip Dates', 'wte-fixed-departure-dates' ); ?></label>
	<input type="number" id="wp_travel_engine_settings[trip_dates][number]" name="wp_travel_engine_settings[trip_dates][number]" min= "0" value="<?php echo isset( $wp_travel_engine_settings['trip_dates']['number'] ) && ! empty( $wp_travel_engine_settings['trip_dates']['number'] ) ? $wp_travel_engine_settings['trip_dates']['number'] : 3; ?>">
	<span class="wpte-tooltip"><?php esc_html_e( 'Use this option to set number of trip fixed starting dates to show in the homepage sections.', 'wte-fixed-departure-dates' ); ?></span>
</div>

<div class="wpte-field wpte-number wpte-floated">
	<label class="wpte-field-label" for="wp_travel_engine_settings[trip_dates][pagination_number]"><?php _e( 'Pagination Number', 'wte-fixed-departure-dates' ); ?></label>
	<input type="number" id="wp_travel_engine_settings[trip_dates][pagination_number]" name="wp_travel_engine_settings[trip_dates][pagination_number]" min= "1" step="1" max="99" value="<?php echo isset( $wp_travel_engine_settings['trip_dates']['pagination_number'] ) && ! empty( $wp_travel_engine_settings['trip_dates']['pagination_number'] ) ? $wp_travel_engine_settings['trip_dates']['pagination_number'] : 10; ?>">
	<span class="wpte-tooltip"><?php esc_html_e( 'Use this option to set number of trip fixed starting dates to show per page in the date listings through shortcode and tabs.', 'wte-fixed-departure-dates' ); ?></span>
</div>

<?php $page_shortcode = '[WTE_TRIPS_FIXED_STARTING_DATES]'; ?>
<div class="wpte-info-block">
	<p>
		<?php
			echo sprintf( __( 'Need to list all the Fixed Starting Dates? You can use this shortcode <b>%1$s</b> on a page/post/tab to display Fixed Starting Dates from all of your trips sorted by Months.', 'wte-fixed-departure-dates' ), $page_shortcode );
		?>
	</p>
</div>
<?php
