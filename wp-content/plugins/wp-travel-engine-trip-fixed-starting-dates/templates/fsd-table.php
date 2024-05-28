<?php
/**
 * Trip FSD table template.
 */
$trip_id        = isset( $args['post_id'] ) ? $args['post_id'] : false;
$is_tab_content = isset( $args['is_tab_conent'] ) && $args['is_tab_conent'] ? '-tab-content' : '';

if ( ! $trip_id ) {
	return;
}
$fsd_functions = new WTE_Fixed_Starting_Dates_Functions();
$sorted_fsd    = call_user_func(
	array( new WTE_Fixed_Starting_Dates_Shortcodes(), 'generate_fsds' ),
	$trip_id,
	array(
		'year'  => '',
		'month' => '',
	)
);

if ( empty( $sorted_fsd ) ) {
	return;
}
wp_enqueue_script( 'wte-select2' );
wp_enqueue_style( 'wte-select2' );
?>
<div id="wte-fixed-departure-dates<?php echo esc_attr( $is_tab_content ); ?>" class="fixed-starting dates wte-fsd-list-container" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wte-fsd' ) ); ?>">
	<div class="wte-fsd-list-header">
		<?php
			$WTE_Fixed_Starting_Dates_option_setting = wp_travel_engine_get_settings();
			$WTE_Fixed_Starting_Dates_setting        = get_post_meta( $args['post_id'], 'WTE_Fixed_Starting_Dates_setting', true );
			$section_title                           = isset( $WTE_Fixed_Starting_Dates_option_setting['departure']['section_title'] ) && '' != $WTE_Fixed_Starting_Dates_option_setting['departure']['section_title'] ? $WTE_Fixed_Starting_Dates_option_setting['departure']['section_title'] : __( 'Join Our Fixed Trip Starting Date', 'wte-fixed-departure-dates' );

			$section_title = isset( $WTE_Fixed_Starting_Dates_setting['availability_title'] ) && '' != $WTE_Fixed_Starting_Dates_setting['availability_title'] ? $WTE_Fixed_Starting_Dates_setting['availability_title'] : $section_title;

			echo '<h2>' . esc_attr( $section_title ) . '</h2>';
		?>

		<!-- FILTER -->
		<div class="wte-user-input">
			<input type = 'hidden' class="hidden-id" value="<?php echo $trip_id; ?>">
			<select class="date-select wpte-enhanced-select" name="date-select" data-placeholder="<?php esc_attr_e( 'Choose a date&hellip;', 'wte-fixed-departure-dates' ); ?>" class="wc-enhanced-select">
				<option value=" "><?php _e( 'Choose a date&hellip;', 'wte-fixed-departure-dates' ); ?></option>
				<?php
				$monts_arr = array_unique(
					array_map(
						function( $fsd ) {
							return date( 'Y-m', strtotime( $fsd['start_date'] ) );
						},
						$sorted_fsd
					)
				);
				foreach ( $monts_arr as $key => $val ) {
					echo '<option data-month="' . date_i18n( 'm', strtotime( $val ) ) . '" value="' . $val . '">' . date_i18n( 'F, Y', strtotime( $val ) ) . '</option>';
				}
				?>
			</select>
		</div>
	</div>

	<div class="wte-fsd-frontend-holder-dd dd" id="nestable1">
		<div class="dd-list outer">
		<?php wte_fsd_get_template( 'table-inner.php', $sorted_fsd ); ?>
		</div>
	</div>
</div>
<?php
