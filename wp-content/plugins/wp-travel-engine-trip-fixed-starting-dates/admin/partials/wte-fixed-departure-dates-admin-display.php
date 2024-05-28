<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wptravelengine.com/
 * @since      1.0.0
 *
 * @package    WTE_Fixed_Starting_Dates
 * @subpackage WTE_Fixed_Starting_Dates/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php

// Get global post.
global $post;

// Get post ID.
if ( ! is_object( $post ) && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	$post_id  = $_POST['post_id'];
	$next_tab = $_POST['next_tab'];
} else {
	$post_id = $post->ID;
}

// Get settings meta.
$fsd_setting       = get_post_meta( $post_id, 'WTE_Fixed_Starting_Dates_setting', true );
$trip_settings     = get_post_meta( $post_id, 'wp_travel_engine_setting', true );
$sortable_settings = get_post_meta( $post_id, 'list_serialized', true );

// Get DB values.
$availability_title  = isset( $fsd_setting['availability_title'] ) ? $fsd_setting['availability_title'] : '';

$availability_options = WTE_Fixed_Starting_Dates_Functions::availability();
$availability         = isset( $fsd_setting['departure_dates']['availability'] ) ? $fsd_setting['departure_dates']['availability']: '';

$price = wp_travel_engine_get_actual_trip_price( $post_id, true );
$duration = isset( $trip_settings['trip_duration'] ) && ! empty( $trip_settings['trip_duration'] )
? $trip_settings['trip_duration']: false;
$duration_unit = isset( $trip_settings['trip_duration_unit'] ) ? $trip_settings['trip_duration_unit'] : 'days';

$enable_multiple_pricing_fsd = isset( $fsd_setting['enable_multiple_pricing_fsd'] ) && '1' === $fsd_setting['enable_multiple_pricing_fsd'] ? true : false;

$hide_fsd_section = isset($fsd_setting['departure_dates']['section']) ? esc_attr($fsd_setting['departure_dates']['section']) : '';

?>
	<div class="wpte-field wpte-text wpte-floated">
		<label class="wpte-field-label"><?php _e( 'Section Title', 'wte-fixed-departure-dates' );?></label>
		<input type="text" name="WTE_Fixed_Starting_Dates_setting[availability_title]" value="<?php echo esc_attr( $availability_title ); ?>" placeholder="<?php _e( 'Enter Here', 'wte-fixed-departure-dates' ); ?>">
		<span class="wpte-tooltip"><?php _e( 'Enter title for the Availability section.', 'wte-fixed-departure-dates' ); ?></span>
	</div>

	<div class="wpte-form-block-wrap">
		<div class="wpte-form-block">
			<div class="wpte-title-wrap">
				<h2 class="wpte-title"><?php _e( 'Fixed Departure Dates', 'wte-fixed-departure-dates' ); ?></h2>
			</div>
			<input type="hidden" name="WTE_Fixed_Starting_Dates_setting[departure_dates][flag]" value="1">
			<div class="wpte-form-content">
				<div class="wpte-field wpte-number wpte-floated">
					<label class="wpte-field-label"><?php _e( 'Available Spots', 'wte-fixed-departure-dates' ); ?></label>
					<input type="number" name="WTE_Fixed_Starting_Dates_setting[departure_dates][availability]" value="<?php echo esc_attr( $availability );?>" placeholder="e.g. 5">
					<span class="wpte-tooltip"><?php _e( 'Set the default number of available spot to be filled automatically in each of the fixed dates.', 'wte-fixed-departure-dates' ); ?></span>
				</div>
				<div class="wpte-field wpte-date wpte-floated">
					<label class="wpte-field-label"><?php _e( 'Select Dates', 'wte-fixed-departure-dates' ); ?></label>
					<div class="wpte-date-field-wrap">
						<input readonly type="text" id="fsd-departure-dates" value="" placeholder="<?php _e( 'Pick Fixed Starting Dates', 'wte-fixed-departure-dates' ); ?>">
						<span class="wpte-date-icon">
							<i class="far fa-calendar-alt"></i>
						</span>
					</div>
					<button class="wpte-add-btn wpte-add-dates"><?php esc_html_e( 'Add Dates', 'wte-fixed-departure-dates' ); ?></button>
				</div>
				<input type="hidden" id="wpte-fsd-duration-meta" value="<?php echo isset( $duration ) && 'days' == $duration_unit ? esc_attr( $duration ) : '';?>">

				<?php
				if (!is_array($sortable_settings)) {
					$sortable_settings = json_decode($sortable_settings);
				}
				?>
				<table class="wpte-table">
					<thead>
						<tr>
							<th><?php _e('Date', 'wte-fixed-departure-dates'); ?></th>
							<th><?php _e('Price', 'wte-fixed-departure-dates'); ?></th>
							<th><?php _e('No. of available spots', 'wte-fixed-departure-dates'); ?></th>
							<th><?php _e('Availability label', 'wte-fixed-departure-dates'); ?></th>
							<th><?php _e('Recurring Time', 'wte-fixed-departure-dates'); ?></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php
					if (isset($fsd_setting) && $fsd_setting!='' && isset($sortable_settings) && $sortable_settings!='') {
						foreach ($sortable_settings as $content) {

							if (isset($fsd_setting['departure_dates']['sdate'][$content->id])) {
								$today = strtotime(date("Y-m-d"))*1000;
								$enable_recurr_dates = isset($fsd_setting['departure_dates'][$content->id]['recurring']['enable']) ? true : false;
								if (($today <= strtotime($fsd_setting['departure_dates']['sdate'][$content->id])*1000) || isset($enable_recurr_dates) && $enable_recurr_dates !== false) {

									$start_date        = isset($fsd_setting['departure_dates']['sdate'][$content->id]) && $fsd_setting['departure_dates']['sdate'][$content->id] !='' ? $fsd_setting['departure_dates']['sdate'][$content->id] : '';
									$end_date          = isset($fsd_setting['departure_dates']['edate'][$content->id]) && $fsd_setting['departure_dates']['edate'][$content->id] !='' ? $fsd_setting['departure_dates']['edate'][$content->id] : '';
									$cost              = isset($fsd_setting['departure_dates']['cost'][$content->id]) ? $fsd_setting['departure_dates']['cost'][$content->id]: $price ;
									$seats             = isset($fsd_setting['departure_dates']['seats_available'][$content->id]) ? $fsd_setting['departure_dates']['seats_available'][$content->id]: '';
									$availability_type = isset($fsd_setting['departure_dates']['availability_type'][$content->id]) ? $fsd_setting['departure_dates']['availability_type'][$content->id]: 'guaranteed' ;

									?>
									<tr class="wpte-fsd-date-row" data-id="<?php echo $content->id;?>" id="<?php echo $start_date;?>">
										<td>
											<div class="wpte-field wpte-number">
												<div class="wpte-floated">
													<input type="text" class="wpte-single-dp" name="WTE_Fixed_Starting_Dates_setting[departure_dates][sdate][<?php echo $content->id;?>]" value="<?php echo esc_attr( $start_date ); ?>" placeholder="<?php _e( 'Start Date', 'wte-fixed-departure-dates' ); ?>" required>

													<input type="hidden" name="WTE_Fixed_Starting_Dates_setting[departure_dates][edate][<?php echo $content->id;?>]" value="<?php echo esc_attr( $end_date ); ?>">
												</div>
											</div>
										</td>
										<td>
											<div class="wpte-field wpte-number">
												<div class="wpte-floated">
													<input type="number" min="0" name="WTE_Fixed_Starting_Dates_setting[departure_dates][cost][<?php echo $content->id;?>]" value="<?php echo esc_attr( $cost ); ?>" placeholder="<?php _e( 'Price here...', 'wte-fixed-departure-dates' ); ?>">
													<span class="wpte-sublabel"><?php echo esc_html( wp_travel_engine_get_currency_code() ); ?></span>
												</div>
											</div>
										</td>
										<td>
											<div class="wpte-field wpte-number">
												<input type="number" name="WTE_Fixed_Starting_Dates_setting[departure_dates][seats_available][<?php echo $content->id;?>]" value="<?php echo esc_attr( $seats );?>" placeholder="e.g. 5">
											</div>
										</td>

										<td>
											<div class="wpte-field wpte-select">
												<select name="WTE_Fixed_Starting_Dates_setting[departure_dates][availability_type][<?php echo $content->id;?>]">
													<?php foreach ($availability_options as $key => $value) { ?>
														<option value="<?php echo esc_attr( $key );?>" <?php echo selected( $availability_type, $key ); ?>><?php echo esc_attr( $value ) ;?></option>
													<?php }?>
												</select>
											</div>
										</td>
										<td>
											<div class="wpte-checkbox advance-checkbox">
												<div class="wpte-checkbox-wrap">
													<input
													class="wte-fsd-enblrecur"
													id="WTE_Fixed_Starting_Dates_setting[departure_dates][<?php echo esc_attr( $content->id ); ?>][recurring][enable]" type="checkbox" <?php checked( $enable_recurr_dates, true ); ?> name="WTE_Fixed_Starting_Dates_setting[departure_dates][<?php echo esc_attr( $content->id ); ?>][recurring][enable]" value="true">
													<label for="WTE_Fixed_Starting_Dates_setting[departure_dates][<?php echo esc_attr( $content->id ); ?>][recurring][enable]" class="checkbox-label"></label>
												</div>
											</div>
											<a <?php echo $enable_recurr_dates ? 'style="display:inline-block;"' : 'style="display:none;"'; ?> title="<?php echo esc_html__( 'Recurring Options', 'wte-fixed-departure-dates' ); ?>" href="#recurr-popup-<?php echo $content->id;?>" class="open-popup-link"><?php esc_html_e( 'Edit', 'wte-fixed-departure-dates' ); ?></a>

											<div class="white-popup mfp-hide" id="recurr-popup-<?php echo $content->id;?>">
												<?php include plugin_dir_path( __FILE__ ) . '/recurring.php'; ?>
											</div>
										</td>
										<td>
											<button class="wpte-delete wpte-delete-fsd"></button>
										</td>
									</tr>
									<?php
								}
							}
						}
					}
					?>
					</tbody>
				</table>
			</div>
		</div> <!-- .wpte-form-block -->
	</div> <!-- .wpte-form-block-wrap -->

	<script type="text/html" id="tmpl-wpte-fsd-block-tmp">
		<tr class="wpte-fsd-date-row" data-id="{{data.key}}" id="{{data.sdate}}">
			<td>
				<div class="wpte-field wpte-number">
					<div class="wpte-floated">
						<input type="text" class="wpte-single-dp" name="WTE_Fixed_Starting_Dates_setting[departure_dates][sdate][{{data.key}}]" value="{{data.sdate}}" placeholder="<?php _e( 'Start Date', 'wte-fixed-departure-dates' ); ?>" required>

						<input type="hidden" name="WTE_Fixed_Starting_Dates_setting[departure_dates][edate][{{data.key}}]" value="{{data.edate}}">
					</div>
				</div>
			</td>
			<td>
				<div class="wpte-field wpte-number">
					<div class="wpte-floated">
						<input type="number" min="0" name="WTE_Fixed_Starting_Dates_setting[departure_dates][cost][{{data.key}}]" value="<?php echo esc_attr( $price ); ?>" placeholder="<?php _e( 'Price here...', 'wte-fixed-departure-dates' ); ?>">
						<span class="wpte-sublabel"><?php echo esc_html( wp_travel_engine_get_currency_code() ); ?></span>
					</div>
				</div>
			</td>
			<td>
				<div class="wpte-field wpte-number">
					<input type="number" name="WTE_Fixed_Starting_Dates_setting[departure_dates][seats_available][{{data.key}}]" value="{{data.seats}}" placeholder="e.g. 5">
				</div>
			</td>

			<td>
				<div class="wpte-field wpte-select">
					<select name="WTE_Fixed_Starting_Dates_setting[departure_dates][availability_type][{{data.key}}]">
						<?php foreach ($availability_options as $key => $value) { ?>
							<option value="<?php echo esc_attr( $key );?>"><?php echo esc_attr( $value ) ;?></option>
						<?php }?>
					</select>
				</div>
			</td>
			<td>
			<div class="wpte-checkbox advance-checkbox">
				<div class="wpte-checkbox-wrap">
					<input
					class="wte-fsd-enblrecur"
					id="WTE_Fixed_Starting_Dates_setting[departure_dates][{{data.key}}][recurring][enable]" type="checkbox" name="WTE_Fixed_Starting_Dates_setting[departure_dates][{{data.key}}][recurring][enable]" value="true">
					<label for="WTE_Fixed_Starting_Dates_setting[departure_dates][{{data.key}}][recurring][enable]" class="checkbox-label"></label>
				</div>
			</div>
			<a style="display:none;" title="<?php echo esc_html__( 'Recurring Options', 'wte-fixed-departure-dates' ); ?>" href="#recurr-popup-{{data.key}}" class="open-popup-link"><?php esc_html_e( 'Edit', 'wte-fixed-departure-dates' ); ?></a>
			<div class="white-popup mfp-hide" id="recurr-popup-{{data.key}}">
				<?php include plugin_dir_path( __FILE__ ) . '/recurring-temp.php'; ?>
			</div>
			</td>
			<td>
				<button class="wpte-delete wpte-delete-fsd"></button>
			</td>
		</tr>
	</script>

	<div class="wpte-field wpte-onoff-block">
		<a href="Javascript:void(0);" class="wpte-onoff-toggle <?php echo $enable_multiple_pricing_fsd ? 'active' : ''; ?>">
			<label for="WTE_Fixed_Starting_Dates_setting[enable_multiple_pricing_fsd]" class="wpte-field-label"><?php _e( 'Apply Multi Pricing', 'wte-fixed-departure-dates' ); ?><span class="wpte-onoff-btn"></span></label>

		</a>
		<input
			type    = "checkbox"
			id      = "WTE_Fixed_Starting_Dates_setting[enable_multiple_pricing_fsd]"
			name    = "WTE_Fixed_Starting_Dates_setting[enable_multiple_pricing_fsd]"
			value   = "1"
			<?php checked( $enable_multiple_pricing_fsd, true ); ?>
		/>
		<span class="wpte-tooltip">
			<?php _e( 'Check this if you want to enable multi pricing for all the fixed starting dates.
			This overrides the prices stated in each date.', 'wte-fixed-departure-dates' ); ?>
		</span>
	</div>

	<div class="wpte-field wpte-onoff-block">
		<a href="Javascript:void(0);" class="wpte-onoff-toggle <?php echo $hide_fsd_section ? 'active' : ''; ?>">
			<label for="WTE_Fixed_Starting_Dates_setting[departure_dates][section]" class="wpte-field-label"><?php _e( 'Hide Fixed Trip Starts Dates section', 'wte-fixed-departure-dates' ); ?><span class="wpte-onoff-btn"></span></label>
		</a>
		<input
			type    = "checkbox"
			id      = "WTE_Fixed_Starting_Dates_setting[departure_dates][section]"
			name    = "WTE_Fixed_Starting_Dates_setting[departure_dates][section]"
			value   = "1"
			<?php checked( 1, $hide_fsd_section ); ?>
		/>
		<span class="wpte-tooltip">
			<?php _e( 'Check this if you want to disable fixed trip starting dates section between featured image/slider and trip content sections.', 'wte-fixed-departure-dates' ); ?>
		</span>
	</div>

	<?php
		// $recurring_time_array = apply_filters( 'wte_fsd_recurring_time', array(
		//     'days'    => __( 'Days', 'wte-fixed-departure-dates' ),
		//     'hours'   => __( 'Hours', 'wte-fixed-departure-dates' ),
		//     'minutes' => __( 'Minutes', 'wte-fixed-departure-dates' )
		// ) );

		// $recurring_time_limit_array = apply_filters( 'wte_fsd_recurring_time_limit', array(
		//     'days'    => __( 'Days', 'wte-fixed-departure-dates' ),
		//     'hours'   => __( 'Hours', 'wte-fixed-departure-dates' ),
		//     'minutes' => __( 'Minutes', 'wte-fixed-departure-dates' )
		// ) );

		// $recurring_enable   = isset( $fsd_setting['recurring_enable'] ) ? true : false;
		// $recurring_time     = isset( $fsd_setting['recurring_time'] ) ? $fsd_setting['recurring_time'] : '';
		// $recurring_val        = isset( $fsd_setting['recurring_val'] ) ? $fsd_setting['recurring_val'] : false;
		// $recurring_time_limit = isset( $fsd_setting['recurring_time_limit'] ) ? $fsd_setting['recurring_time_limit'] : '';

	?>

	<?php
	/*
	<div class="wpte-onoff-block">
		<div class="wpte-floated">
			<a href="Javascript:void(0);" class="wpte-onoff-toggle <?php echo $recurring_enable ? 'active' : ''; ?>">
				<label for="wpte-fsd-enable-recurring" class="wpte-field-label"><?php _e( 'Recurring Time', 'wte-fixed-departure-dates' );?><span class="wpte-sublabel"><?php _e( '(Optional)', 'wte-fixed-departure-dates' ); ?></span><span class="wpte-onoff-btn"></span></label>
			</a>
			<input id="wpte-fsd-enable-recurring" type="checkbox" <?php checked( $recurring_enable, true ); ?> name="WTE_Fixed_Starting_Dates_setting[recurring_enable]" value="true">
			<div class="wpte-field wpte-select">
				<select name="WTE_Fixed_Starting_Dates_setting[recurring_time]">
					<option><?php _e( 'Select Recurring Time', 'wte-fixed-departure-dates' ); ?></option>
					<?php
						foreach( $recurring_time_array as $value => $label ) {
							echo '<option ' . selected( $recurring_time, $value, false ) . ' value="' . esc_attr( $value ) . '">'. esc_html( $label ) .'</option>';
						}
					?>
				</select>
			</div>
			<div <?php echo $recurring_enable ? 'style="display:block;"' : ''; ?> class="wpte-onoff-popup">
				<div class="wpte-field wpte-multi-fields wpte-floated">
					<label class="wpte-field-label"><?php _e( 'Recurring Time Limit', 'wte-fixed-departure-dates' );?></label>
					<div class="wpte-floated">
						<input type="number" min="1" step="1" name="WTE_Fixed_Starting_Dates_setting[recurring_val]" value="<?php echo $recurring_val ? esc_attr( $recurring_val ) : '' ?>" placeholder="<?php _e( 'Add Number', 'wte-fixed-departure-dates' ); ?>">

						<select name="WTE_Fixed_Starting_Dates_setting[recurring_time_limit]">
							<option><?php _e( 'Select Recurring Time Limit', 'wte-fixed-departure-dates' ); ?></option>
							<?php
								foreach( $recurring_time_limit_array as $value => $label ) {
									echo '<option ' . selected( $recurring_time_limit, $value, false ) . ' value="' . esc_attr( $value ) . '">'. esc_html( $label ) .'</option>';
								}
							?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
	*/
	?>

	<?php
		$page_shortcode     = '[WTE_Fixed_Starting_Dates id='."'".$post_id."'".']';
		$template_shortcode = '&lt;?php echo do_shortcode("[WTE_Fixed_Starting_Dates id='.$post_id.']"); ?&gt;';
	?>
	<div class="wpte-shortcode">
		<span class="wpte-tooltip"><?php esc_html_e( 'To display fixed starting dates in page/post use the following ', 'wte-fixed-departure-dates' ); ?><b><?php esc_html_e( 'Shortcode.', 'wte-fixed-departure-dates' ); ?></b></span>
		<div class="wpte-field wpte-field-gray wpte-floated">
			<input id="wpte-copy-fsd-shortcode" readonly type="text" value="<?php echo esc_attr( $page_shortcode ); ?>">
			<button data-copyid="wpte-copy-fsd-shortcode" class="wpte-copy-btn"><?php esc_html_e( 'Copy', 'wte-fixed-departure-dates' ); ?></button>
		</div>
	</div>

	<div class="wpte-shortcode">
		<span class="wpte-tooltip"><?php esc_html_e( 'To display fixed starting dates in theme/template, please use below ', 'wte-fixed-departure-dates' ); ?><b><?php esc_html_e( 'PHP Funtion.', 'wte-fixed-departure-dates' ); ?></b></span>
		<div class="wpte-field wpte-field-gray wpte-floated">
			<input id="wpte-copy-fsd-shortcode-phpfxn" readonly type="text" value="<?php echo esc_attr( $template_shortcode ); ?>">
			<button data-copyid="wpte-copy-fsd-shortcode-phpfxn" class="wpte-copy-btn"><?php esc_html_e( 'Copy', 'wte-fixed-departure-dates' ); ?></button>
		</div>
	</div>

	<?php if ( $next_tab && 'false' != $next_tab ) : ?>
		<div class="wpte-field wpte-submit">
			<input data-tab="availability" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte-trip-tab-save-continue' ) ); ?>" data-next-tab="<?php echo esc_attr( $next_tab['callback_function'] ); ?>" class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue" value="<?php _e( 'Save &amp; Continue', 'wte-fixed-departure-dates' ); ?>">
		</div>
	<?php endif;
