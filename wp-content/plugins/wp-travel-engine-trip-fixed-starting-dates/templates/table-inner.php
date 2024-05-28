<?php
/**
 * table inner.
 */
?>
<div class="dd-list outer">
	<table>
		<thead>
			<tr>
				<th><?php echo esc_html__( 'TRIP DATES', 'wte-fixed-departure-dates' ); ?></th>
				<th><?php echo esc_html__( 'AVAILABILITY', 'wte-fixed-departure-dates' ); ?></th>
				<th><?php echo esc_html__( 'PRICE', 'wte-fixed-departure-dates' ); ?></th>
				<th><?php echo esc_html__( 'SPACE LEFT', 'wte-fixed-departure-dates' ); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$today = date( 'Y-m-d' );
			if ( ! empty( $args ) ) {
				$trip_duration_unit = null;
				foreach ( $args as $key => $fsd ) {
					if ( is_null( $trip_duration_unit ) ) {
						$trip_settings = get_post_meta( $fsd['trip_id'], 'wp_travel_engine_setting', true );
						$trip_duration_unit = ! empty( $trip_settings['trip_duration_unit'] ) ? $trip_settings['trip_duration_unit'] : 'days';
					}
					if ( strtotime( $today ) <= strtotime( $fsd['start_date'] ) ) {
						$fsd['trip_duration_unit'] = $trip_duration_unit;
						wte_fsd_get_template( 'table-row.php', $fsd );
					}
				}
			} else {
				?>
				<tr style="display: table-row;">
					<td colspan="5"><?php echo esc_html__( 'No Fixed Departure Dates available.', 'wte-fixed-departure-dates' ); ?></td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<?php
	$globals_settings = wp_travel_engine_get_settings();

	$pagination_num = isset( $globals_settings['trip_dates']['pagination_number'] ) && ! empty( $globals_settings['trip_dates']['pagination_number'] ) ? $globals_settings['trip_dates']['pagination_number'] : 10;

	$count = count( $args );

	if ( $count > $pagination_num ) :
		?>
			<button class="loadMore"><?php esc_html_e( 'Load More', 'wte-fixed-departure-dates' ); ?></button>
			<button style="display:none;" class="showLess" ><?php esc_html_e( 'Show Less', 'wte-fixed-departure-dates' ); ?></button>
		<?php
		endif;
	?>
	<div id="loader" style="display: none">
		<div class="table">
			<div class="table-row">
				<div class="table-cell">
					<i class="fa fa-spinner fa-spin"></i>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
