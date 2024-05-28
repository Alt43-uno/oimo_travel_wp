<?php
class WTE_Fixed_Starting_Dates_Post_Type {

	function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'WTE_Fixed_Starting_Dates_add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'WTE_Fixed_Starting_Dates_save_meta_box_data' ) );
		add_filter( 'WTE_Fixed_Starting_Dates_tab', array( $this, 'WTE_Fixed_Starting_Dates_config_tab' ), 10, 2 );
		add_filter( 'wte_fixed_departure_use_tab', array( $this, 'wte_fixed_departure_usage_tab' ), 10, 2 );
	}

	/**
	 * Adds metabox for backend configurations.
	 *
	 * @since 1.0.0
	 */
	function WTE_Fixed_Starting_Dates_add_meta_boxes() {
		$screens = array( 'trip' );
		foreach ( $screens as $screen ) {
			add_meta_box(
				'fixed_departure_dates_tab_id',
				__( 'Trip Fixed Starting Dates Configuration', 'wte-fixed-departure-dates', 'wte-fixed-departure-dates' ),
				array( $this, 'WTE_Fixed_Starting_Dates_tab_meta_box_callback' ),
				$screen,
				'normal',
				'high'
			);
		}
	}

	// Tab for wte-fixed-departure-dates listing and settings
	public function WTE_Fixed_Starting_Dates_tab_meta_box_callback() {
		global $post;
		$tb_settings = get_post_meta( $post->ID, 'WTE_Fixed_Starting_Dates_setting', true );

		$tab_args = array(
			'container_id'       => 'tab-container',
			'content_wrap_class' => 'fdd-setting-outer-wrap',
			'tabs'               => array(
				array(
					'id'        => 'fdd',
					'title'     => __( 'Fixed Starting Dates', 'wte-fixed-departure-dates' ),
					'is_active' => true,
					'content'   => apply_filters( 'WTE_Fixed_Starting_Dates_tab', '', $tb_settings ),
				),
				array(
					'id'      => 'uses',
					'title'   => __( 'Usage', 'wte-fixed-departure-dates' ),
					'content' => apply_filters( 'wte_fixed_departure_use_tab', '', $tb_settings ),
				),
			),
		);
		WTE_Fixed_Starting_Dates_Meta_Tabs::create_tabs( $tab_args );
	}

	/**
	 * Members tab.
	 *
	 * @since 1.0.0
	 */
	function WTE_Fixed_Starting_Dates_config_tab( $data, $tb_settings ) {
		ob_start();
		include_once WTE_FIXED_DEPARTURE_BASE_PATH . '/admin/views/dates-config.php';
		$data .= ob_get_contents();
		ob_end_clean();
		return $data;
	}

	/**
	 * Settings tab.
	 *
	 * @since 1.0.0
	 */
	function wte_fixed_departure_usage_tab( $data, $tb_settings ) {
		ob_start();
		include_once WTE_FIXED_DEPARTURE_BASE_PATH . '/admin/views/dates-uses.php';
		$data .= ob_get_contents();
		ob_end_clean();
		return $data;
	}

	/**
	 * When the post is saved, saves our custom data.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	function WTE_Fixed_Starting_Dates_save_meta_box_data( $post_id ) {

		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */
		if ( isset( $_POST['WTE_Fixed_Starting_Dates_setting']['departure_dates']['flag'] ) ) {

			if ( isset( $_POST['WTE_Fixed_Starting_Dates_setting'] ) && isset( $_POST['WTE_Fixed_Starting_Dates_setting']['departure_dates']['sdate'] ) ) {
				$array_settings = $_POST['WTE_Fixed_Starting_Dates_setting'];

				$len      = sizeof( $array_settings['departure_dates']['sdate'] );
				$arr_keys = array_keys( $array_settings['departure_dates']['sdate'] );
				$msg      = '';
				foreach ( $arr_keys as $key => $value ) {
					if ( $array_settings['departure_dates']['edate'][ $value ] < $array_settings['departure_dates']['sdate'][ $value ] ) {
						$msg = __( 'For all fixed departures, ending date must be greater than starting date.', 'wte-fixed-departure-dates' );
					}
					$msg1 = '';
					if ( isset( $array_settings['departure_dates']['cost'][ $value ] ) && $array_settings['departure_dates']['cost'][ $value ] != '' && ! absint( $array_settings['departure_dates']['cost'][ $value ] ) ) {
						$msg1 = __( ' For all fixed departures, please insert valid cost.', 'wte-fixed-departure-dates' );
					}
				}

				if ( $msg != '' || $msg1 != '' ) {
					printf( '<a href="%s" class="link">%s</a>', $_SERVER['HTTP_REFERER'], 'Back<<' );
					$err = $msg . $msg1;
					wp_die( $err );
					exit;
				} else {
					$data1 = $_POST['WTE_Fixed_Starting_Dates_setting'];
					update_post_meta( $post_id, 'WTE_Fixed_Starting_Dates_setting', $data1 );
					$list_serialized = $_POST['list_serialized'];
					update_post_meta( $post_id, 'list_serialized', $list_serialized );
				}
			} else {
				$data1 = '';
				update_post_meta( $post_id, 'WTE_Fixed_Starting_Dates_setting', $data1 );
				$list_serialized = '';
				update_post_meta( $post_id, 'list_serialized', $list_serialized );
			}
		}
	}
}

new WTE_Fixed_Starting_Dates_Post_Type();
