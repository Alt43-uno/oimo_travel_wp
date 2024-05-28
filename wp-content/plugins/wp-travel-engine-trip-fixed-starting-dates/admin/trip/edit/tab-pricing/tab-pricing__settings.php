<?php
/**
 * Tab Pricings and Dates sub-content - Settings.
 *
 * @since 5.0.0
 */
// Get global post.
global $post;

// Get post ID.
if ( ! is_object( $post ) && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	$post_id  = $_POST['post_id'];
	$next_tab = $_POST['next_tab'];
} else {
	$post_id = $post->ID;
}

// $fsd_setting        = get_post_meta( $post_id, 'wp_travel_engine_setting', true );
$fsd_setting = get_post_meta( $post_id, 'WTE_Fixed_Starting_Dates_setting', true );
$availability_title = isset( $fsd_setting['availability_title'] ) ? $fsd_setting['availability_title'] : '';
$hide_section       = isset( $fsd_setting['departure_dates']['section'] ) && '1' == $fsd_setting['departure_dates']['section'];
?>
<div class="wpte-form-block">
	<div class="wpte-title-wrap">
		<h2 class="wpte-title"><?php esc_html_e( 'Date Settings', 'wte-fixed-departure-dates' ); ?></h2>
	</div> <!-- .wpte-title-wrap -->
</div>
<div class="wpte-field wpte-text wpte-floated">
	<label class="wpte-field-label"><?php _e( 'Section Title', 'wte-fixed-departure-dates' ); ?></label>
	<input
		type="text"
		name="WTE_Fixed_Starting_Dates_setting[availability_title]"
		value="<?php echo esc_attr( $availability_title ); ?>"
		placeholder="<?php _e( 'Enter Here', 'wte-fixed-departure-dates' ); ?>"
	>
	<span class="wpte-tooltip"><?php _e( 'Enter title for the Availability section.', 'wte-fixed-departure-dates' ); ?></span>
</div>
<div class="wpte-field wpte-onoff-block">
	<input
			type="hidden"
			id="WTE_Fixed_Starting_Dates_settingdeparture_dates-no"
			name="WTE_Fixed_Starting_Dates_setting[departure_dates][section]"
			value="no"/>
	<a
		href="Javascript:void(0);"
		class="wpte-onoff-toggle "
	>
		<label
			for="WTE_Fixed_Starting_Dates_setting[departure_dates][section]"
			class="wpte-field-label"
		><?php esc_html_e( 'Hide Fixed Trip Starts Dates section', 'wte-fixed-departure-dates' ); ?><span
				class="wpte-onoff-btn"></span></label>
	</a>
	<input
		type="checkbox"
		id="WTE_Fixed_Starting_Dates_setting[departure_dates][section]"
		name="WTE_Fixed_Starting_Dates_setting[departure_dates][section]"
		value="1"
		data-parsley-multiple="WTE_Fixed_Starting_Dates_settingdeparturesection"
		data-parsley-id="58"
		<?php checked( $hide_section, true ); ?>

	>
	<span class="wpte-tooltip">
	<?php
	esc_html_e(
		'Check this if you want to disable fixed trip starting dates section between featured image/slider and trip
		content sections.',
		'wte-fixed-departure-dates'
	);
	?>
	</span>
</div>
<div class="wpte-shortcode">
	<span
		class="wpte-tooltip"><?php echo wp_kses( sprintf( __( 'To display fixed starting dates in page/post use the following %1$sShortcode.%2$s', 'wte-fixed-departure-dates' ), '<b>', '</b>' ), array( 'b' => array() ) ); ?></span>
	<div class="wpte-field wpte-field-gray wpte-floated">
		<input
			id="wpte-copy-fsd-shortcode"
			readonly=""
			type="text"
			value="[WTE_Fixed_Starting_Dates id='<?php echo esc_attr( $post_id ); ?>']"
			data-parsley-id="60"
		>
		<button
			data-copyid="wpte-copy-fsd-shortcode"
			class="wpte-copy-btn"
		>Copy</button>
	</div>
</div>
<div class="wpte-shortcode">
	<span
		class="wpte-tooltip"><?php echo wp_kses( sprintf( __( 'To display fixed starting dates in theme/template, please use below %1$sPHP Funtion.%2$s', 'wte-fixed-departure-dates' ), '<b>', '</b>' ), array( 'b' => array() ) ); ?></span>
	<div class="wpte-field wpte-field-gray wpte-floated">
		<input
			id="wpte-copy-fsd-shortcode-phpfxn"
			readonly=""
			type="text"
			value="&lt;?php echo do_shortcode(&quot;[WTE_Fixed_Starting_Dates id=<?php echo esc_attr( $post_id ); ?>]&quot;); ?&gt;"
			data-parsley-id="62"
		>
		<button
			data-copyid="wpte-copy-fsd-shortcode-phpfxn"
			class="wpte-copy-btn"
		><?php esc_html_e( 'Copy', 'wte-fixed-departure-dates' ); ?></button>
	</div>
</div>
