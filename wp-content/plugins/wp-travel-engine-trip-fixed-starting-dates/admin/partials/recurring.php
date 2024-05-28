<?php
/**
 * Recurring options.
 */
use RRule\RRule;

$enable_recurr_dates     = isset( $fsd_setting['departure_dates'][ $content->id ]['recurring']['enable'] ) ? true : false;
$recursion_type          = isset( $fsd_setting['departure_dates'][ $content->id ]['recurring']['type'] ) ? $fsd_setting['departure_dates'][ $content->id ]['recurring']['type'] : 'DAILY';
$recurring_limit         = isset( $fsd_setting['departure_dates'][ $content->id ]['recurring']['limit'] ) ? $fsd_setting['departure_dates'][ $content->id ]['recurring']['limit'] : 10;
$recurring_until         = isset( $fsd_setting['departure_dates'][ $content->id ]['recurring']['until'] ) ? $fsd_setting['departure_dates'][ $content->id ]['recurring']['until'] : '';
$months_recur            = isset( $fsd_setting['departure_dates'][ $content->id ]['recurring']['months'] ) && ! empty( $fsd_setting['departure_dates'][ $content->id ]['recurring']['months'] ) ? $fsd_setting['departure_dates'][ $content->id ]['recurring']['months'] : array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12' );
$weekdays_recur          = isset( $fsd_setting['departure_dates'][ $content->id ]['recurring']['week_days'] ) && ! empty( $fsd_setting['departure_dates'][ $content->id ]['recurring']['week_days'] ) ? $fsd_setting['departure_dates'][ $content->id ]['recurring']['week_days'] : array( 'MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU' );
$months_selection_disp   = 'MONTHLY' === $recursion_type ? '' : 'style="display:none;"';
$weekdays_selection_disp = 'WEEKLY' === $recursion_type ? '' : 'style="display:none;"';

$all_months_array = array(
	'1'  => __( 'Jan', 'wte-fixed-departure-dates' ),
	'2'  => __( 'Feb', 'wte-fixed-departure-dates' ),
	'3'  => __( 'Mar', 'wte-fixed-departure-dates' ),
	'4'  => __( 'Apr', 'wte-fixed-departure-dates' ),
	'5'  => __( 'May', 'wte-fixed-departure-dates' ),
	'6'  => __( 'Jun', 'wte-fixed-departure-dates' ),
	'7'  => __( 'Jul', 'wte-fixed-departure-dates' ),
	'8'  => __( 'Aug', 'wte-fixed-departure-dates' ),
	'9'  => __( 'Sep', 'wte-fixed-departure-dates' ),
	'10' => __( 'Oct', 'wte-fixed-departure-dates' ),
	'11' => __( 'Nov', 'wte-fixed-departure-dates' ),
	'12' => __( 'Dec', 'wte-fixed-departure-dates' ),
);

$all_weekdays_array = array(
	'MO' => __( 'Mon', 'wte-fixed-departure-dates' ),
	'TU' => __( 'Tue', 'wte-fixed-departure-dates' ),
	'WE' => __( 'Wed', 'wte-fixed-departure-dates' ),
	'TH' => __( 'Thu', 'wte-fixed-departure-dates' ),
	'FR' => __( 'Fri', 'wte-fixed-departure-dates' ),
	'SA' => __( 'Sat', 'wte-fixed-departure-dates' ),
	'SU' => __( 'Sun', 'wte-fixed-departure-dates' ),
);

$start_date = isset( $fsd_setting['departure_dates']['sdate'][ $content->id ] ) && ! empty( $start_date  = $fsd_setting['departure_dates']['sdate'][ $content->id ] ) ? $start_date  = $fsd_setting['departure_dates']['sdate'][ $content->id ] : false;
?>
<div class="wpte-multi-fields">
	<label for="" class="wpte-field-label wpte-field-title-lb"><?php esc_html_e( 'Select Recursion Type', 'wte-fixed-departure-dates' ); ?></label>
	<div class="wpte-recurr-main-wrap">
		<div class="wpte-recurr-indiv-wrap">
			<label for="wte_fsd_recur_daily"><input id="wte_fsd_recur_daily" value="DAILY" <?php checked( $recursion_type, 'DAILY' ); ?> name="WTE_Fixed_Starting_Dates_setting[departure_dates][<?php echo esc_attr( $content->id ); ?>][recurring][type]" type="radio" class="wpte-recurr-type-sel"><?php esc_html_e( 'Daily', 'wte-fixed-departure-dates' ); ?></label>
		</div>
		<div class="wpte-recurr-indiv-wrap wpte-recurr-indiv-toggle-wrap">
			<label for="wte_fsd_recur_weekly" class="wpte-recurr-extra-spc <?php echo 'WEEKLY' === $recursion_type ? 'toggleon' : ''; ?>"><input value="WEEKLY" <?php checked( $recursion_type, 'WEEKLY' ); ?> name="WTE_Fixed_Starting_Dates_setting[departure_dates][<?php echo esc_attr( $content->id ); ?>][recurring][type]" id="wte_fsd_recur_weekly" type="radio" class="wpte-recurr-type-sel"><?php esc_html_e( 'Weekly', 'wte-fixed-departure-dates' ); ?></label>
			<div class="wpte-recurr-indiv-toggle-content">
				<label for="" class="wpte-recurr-inn-title"><?php esc_html_e( 'Select Weekdays:', 'wte-fixed-departure-dates' ); ?></label>
				<div id="wpte-fsd-recurr-weekdays" class="wpte-multi-checboxes">
				<?php
				$weekrecurindex = 0;
				foreach ( $all_weekdays_array as $key => $weekday ) :
					?>
					<label>
						<input <?php echo in_array( $key, $weekdays_recur ) ? 'checked' : ''; ?> name="WTE_Fixed_Starting_Dates_setting[departure_dates][<?php echo esc_attr( $content->id ); ?>][recurring][week_days][<?php echo esc_attr( $weekrecurindex ); ?>]" type="checkbox" value="<?php echo esc_attr( $key ); ?>"> <?php echo esc_html( $weekday ); ?>
					</label>
					<?php
					$weekrecurindex++;
				endforeach;
				?>
				</div>
			</div>
		</div>
		<div class="wpte-recurr-indiv-wrap wpte-recurr-indiv-toggle-wrap">
			<label for="wte_fsd_recur_monthly" class="wpte-recurr-extra-spc <?php echo 'MONTHLY' === $recursion_type ? 'toggleon' : ''; ?>"><input value="MONTHLY" <?php checked( $recursion_type, 'MONTHLY' ); ?> name="WTE_Fixed_Starting_Dates_setting[departure_dates][<?php echo esc_attr( $content->id ); ?>][recurring][type]" type="radio" class="wpte-recurr-type-sel" id="wte_fsd_recur_monthly"><?php esc_html_e( 'Monthly', 'wte-fixed-departure-dates' ); ?></label>
			<div class="wpte-recurr-indiv-toggle-content">
				<label for="" class="wpte-recurr-inn-title"><?php esc_html_e( 'Select Months:', 'wte-fixed-departure-dates' ); ?></label>
				<div id="wpte-fsd-recurr-weekdays" class="wpte-multi-checboxes">
				<?php
					$recurindex = 0;
				foreach ( $all_months_array as $key => $month ) :
					?>
						<label>
							<input <?php echo in_array( $key, $months_recur ) ? 'checked' : ''; ?> name="WTE_Fixed_Starting_Dates_setting[departure_dates][<?php echo esc_attr( $content->id ); ?>][recurring][months][<?php echo esc_attr( $recurindex ); ?>]" type="checkbox" value="<?php echo esc_attr( $key ); ?>"> <?php echo esc_html( $month ); ?>
						</label>
					<?php
					$recurindex++;
					endforeach;
				?>
				</div>
			</div>
		</div>
		<div class="wpte-recurr-indiv-wrap">
			<label for="wte_fsd_recur_yearly" ><input id="wte_fsd_recur_yearly" value="YEARLY" <?php checked( $recursion_type, 'YEARLY' ); ?> name="WTE_Fixed_Starting_Dates_setting[departure_dates][<?php echo esc_attr( $content->id ); ?>][recurring][type]" type="radio" class="wpte-recurr-type-sel"><?php esc_html_e( 'Yearly', 'wte-fixed-departure-dates' ); ?></label>
		</div>
		<div class="wpte-recurr-indiv-wrap wpte-recurr-limit wpte-number">
			<div class="wpte-recurr-limit-iner-wrap">
				<label class="wpte-field-label"><?php esc_html_e( 'Recursion Limit', 'wte-fixed-departure-dates' ); ?></label>
				<input type="text" name="WTE_Fixed_Starting_Dates_setting[departure_dates][<?php echo esc_attr( $content->id ); ?>][recurring][limit]" value="<?php echo esc_attr( $recurring_limit ); ?>">
			</div>
				<?php
				if ( $start_date ) :
						$recurr_args = array(
							'FREQ'     => $recursion_type,
							'INTERVAL' => 1,
							'DTSTART'  => $start_date,
							'COUNT'    => $recurring_limit,
							// 'UNTIL'    => $recurring_until,
						);

						if ( 'MONTHLY' === $recursion_type ) {
							$recurr_args['BYMONTH'] = array_values( $months_recur );
						}

						if ( 'WEEKLY' === $recursion_type ) {
							$recurr_args['BYDAY'] = array_values( $weekdays_recur );
						}

						$rrule = new RRule( $recurr_args );

						?>
							<div class="wpte-recurr-limit-text">
								<strong><?php esc_html_e( 'Recursion Summary: ', 'wte-fixed-departure-dates' ); ?></strong><?php echo esc_html( ucfirst( $rrule->humanReadable() ) ); ?>
							</div>
						<?php
				endif;
				?>
		</div>
	</div>
</div>
<?php
