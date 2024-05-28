<?php
/**
 * Table inner.
 */

global $wpdb;

$sorted_fsd = array();

ob_start();

$currentyear  = gmdate( 'Y' );
$currentmonth = gmdate( 'n' );

// wp_enqueue_script( 'wte-fsd-listing' );
wp_enqueue_script( 'wte-fixed-departure-dates' );
wp_enqueue_style( 'wte-fixed-departure-dates' );
wp_enqueue_script( 'wp-util' );
?>
<div id="wte-trips-fixed-departure-dates" class="fixed-starting dates" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wte-fsd' ) ); ?>">
	<!-- FILTER -->
	<div class="wte-user-input">
		<span class="wte-user-input-filters-icon" title="Filters"><?php esc_html_e( 'Filter By:', 'wte-fixed-departure-dates' ); ?></span>
		<div class="wte-user-input-fields-wrap">
			<div class="wte-user-input-field wet-user-input-date wte-user-input-year">
				<label for="wte-fsd-filter-year"><?php esc_html_e( 'By Year', 'wte-fixed-departure-dates' ); ?></label>
				<select id="wte-fsd-filter-year" class="wte-fsd-filter wpte-enhanced-select wte-fsd-date-selector" name="year"
					data-placeholder="<?php esc_attr_e( 'By all years', 'wte-fixed-departure-dates' ); ?>"
					class="wc-enhanced-select"
					data-filter-by="fsdDate">
					<option value="any"><?php echo _x( 'All available years', 'Default FSD filter option', 'wte-fixed-departure-dates' ); ?></option>
					<?php
					for ( $i = 0; $i <= 5; $i++ ) {
						echo '<option data-year="' . ( (int) $currentyear + $i ) . '" value="' . ( (int) $currentyear + $i ) . '">' . ( (int) $currentyear + $i ) . '</option>';
					}
					?>
				</select>
			</div>
			<div class="wte-user-input-field wet-user-input-date wte-user-input-month">
				<label for="wte-fsd-filter-month"><?php esc_html_e( 'By Month', 'wte-fixed-departure-dates' ); ?></label>
				<select id="wte-fsd-filter-month"  class="wte-fsd-filter wpte-enhanced-select wte-fsd-date-selector" name="month"
					data-placeholder="<?php esc_attr_e( 'All available months', 'wte-fixed-departure-dates' ); ?>"
					class="wc-enhanced-select"
					data-filter-by="fsdDate">
					<option value="any"><?php echo _x( 'All available months', 'Default FSD filter option', 'wte-fixed-departure-dates' ); ?></option>
					<?php
					foreach ( range( 1, 12 ) as $month ) {
						echo '<option data-month="' . ( $month ) . '" value="' . ( $month ) . '">' . date_i18n( 'F', mktime( 0, 0, 0, $month ) ) . '</option>';
					}
					?>
				</select>
			</div>
		</div>
	</div>
	<div class="">
		<div class="wpte-trips-fsd-table-wrapper" style="position:relative" id="wte-fsd-all-table"></div>
	</div>
</div>
<?php
wte_fsd_get_template( 'script-templates/tmpl-wte-fsd-all.php' );
wte_fsd_get_template( 'script-templates/tmpl-wte-fsd-all-row.php' );
