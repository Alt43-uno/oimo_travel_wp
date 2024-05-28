<?php
/**
 * Row template
 */
$start_date   = isset( $args['start_date'] ) ? $args['start_date'] : '';
$end_date     = isset( $args['end_date'] ) ? $args['end_date'] : '';
$availability = isset( $args['availability'] ) ? $args['availability'] : 'guaranteed';
$seats_left   = isset( $args['seats_left'] ) ? $args['seats_left'] : '';
$fsd_cost     = isset( $args['fsd_cost'] ) ? $args['fsd_cost'] : '';

$trip_duration_unit = $args[ 'trip_duration_unit' ];

$trip_id = $args['trip_id'];

$availability_options = WTE_Fixed_Starting_Dates_Functions::availability();
$availability_label   = isset( $availability_options[ $availability ] ) ? $availability_options[ $availability ] : __( 'Guaranteed', 'wte-fixed-departure-dates' );

$date_format = get_option( 'date_format', 'Y-m-d' );

?>
<tr style="display: table-row;">
	<td
		data-label="<?php esc_attr_e( 'TRIP DATES', 'wte-fixed-departure-dates' ); ?>"
		dates=""
		class="accordion-sdate"
		data-id="<?php echo esc_attr( $args['content_id'] ); ?>"
	>
		<?php function_exists( 'wptravelengine_svg_by_fa_icon' ) ? wptravelengine_svg_by_fa_icon( 'far fa-calendar' ) : print('<i class="fa fa-calendar"></i>'); ?>
		<span
			class="start-date"
			data-id="<?php echo esc_attr( $start_date ); ?>"
		>
			<?php echo date_i18n( $date_format, strtotime( $start_date ) ); ?>
		</span>

		<?php if ( 'days' === $trip_duration_unit ) : ?>
			<span
				class="end-date"
				data-id="<?php echo esc_attr( $end_date ); ?>"
			> - <?php echo date_i18n( $date_format, strtotime( $end_date ) ); ?></span>
		<?php endif; ?>
	</td>
	<td
		data-label="<?php esc_attr_e( 'AVAILABILITY', 'wte-fixed-departure-dates' ); ?>"
		class="accordion-availability"
		data-id="<?php echo esc_attr( $availability ); ?>"
	><span class="<?php echo esc_attr( $availability ); ?>"><?php echo esc_html( $availability_label ); ?></span></td>
	<td
		data-label="<?php echo esc_attr__( 'PRICE', 'wte-fixed-departure-dates' ); ?>"
		class="accordion-cost"
	>
		<span class="currency-code">
		<?php function_exists( 'wptravelengine_svg_by_fa_icon' ) ? wptravelengine_svg_by_fa_icon( 'fas fa-tag' ) : print('<i class="fa fa-tag"></i>'); ?>
		</span>
		<strong
			class="trip-cost-holder"><?php echo function_exists( 'wte_get_formated_price' ) ? wte_get_formated_price( $fsd_cost ) : wpte_get_formated_price_with_currency_code_symbol( $fsd_cost ); ?></strong>
	</td>
	<td
		data-label="<?php esc_attr_e( 'SPACE LEFT', 'wte-fixed-departure-dates' ); ?>"
		class="accordion-seats"
		data-id="<?php echo esc_attr( $args['content_id'] ); ?>"
	>
		<div class="seats-available">
			<?php
			if ( ( $seats_left === '' ) || ( (int) $seats_left > 0 ) ) :
				?>
				<?php function_exists( 'wptravelengine_svg_by_fa_icon' ) ? wptravelengine_svg_by_fa_icon( 'fas fa-user' ) : print('<i class="fa fa-user"></i>'); ?>

				<span class="seats"><?php echo sprintf( __( '%1$s Available', 'wte-fixed-departure-dates' ), $seats_left ); ?></span>
				<?php
				else :
					echo '<span class="sold-out">' . __( 'sold out', 'wte-fixed-departure-dates' ) . '</span>';
				endif;
				?>
		</div>
	</td>
	<?php
	global $wtetrip;
	global $post;
	$btn_txt                                 = __( 'Book Now ', 'wte-fixed-departure-dates' );
	$WTE_Fixed_Starting_Dates_option_setting = get_option( 'wp_travel_engine_settings', true );
	$btn_txt                                 = isset( $WTE_Fixed_Starting_Dates_option_setting['book_btn_txt'] ) && ! empty( $WTE_Fixed_Starting_Dates_option_setting['book_btn_txt'] ) ? $WTE_Fixed_Starting_Dates_option_setting['book_btn_txt'] : $btn_txt;

	if ( ( 0 < $seats_left || '' === $seats_left ) && $wtetrip && ! $wtetrip->use_legacy_trip ) :
		?>
		<td
			data-label=""
			data-cost="<?php echo esc_attr( $fsd_cost ); ?>"
			class="accordion-book"
			data-id="<?php echo esc_attr( $args['content_id'] ); ?>"
		>
			<?php if ( WP_TRAVEL_ENGINE_POST_TYPE === $post->post_type ) : ?>
				<button
				disabled
				data-info="<?php echo esc_attr( strtotime( $start_date ) ); ?>"
				class="book-btn wte-fsd-list-booknow-btn btn-loading"
				><?php echo esc_html( $btn_txt ); ?>
				</button>
			<?php else : ?>
				<a href="<?php echo esc_url( get_permalink( $trip_id ) . '?action=fsd_booking&date=' . $start_date ); ?>" class="book-btn wte-fsd-list-booknow-btn"><?php echo esc_html( $btn_txt ); ?></a>
			<?php endif; ?>
		</td>
	<?php elseif ( 0 < $seats_left ) : ?>
		<td data-label="" data-cost="<?php echo esc_attr( $fsd_cost ); ?>" class="accordion-book" data-id="<?php echo esc_attr( $args['content_id'] ); ?>"><a href="#" class="book-btn"><?php echo esc_html( $btn_txt ); ?> </a></td>
	<?php endif; ?>
</tr>
<?php
