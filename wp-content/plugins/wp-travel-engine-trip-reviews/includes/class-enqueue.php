<?php
if ( ! class_exists( 'WTE_Trip_Reviews_Enqueue' ) ) :
	/**
	 * Menu Class.
	 */
	class WTE_Trip_Reviews_Enqueue {

		public function init() {
			// Load Assets
			add_action( 'admin_enqueue_scripts', array( $this, 'wte_trip_review_admin_assets' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'wte_trip_review_public_assets' ) );

		}

		/**
		 * Load backend Assets.
		 *
		 * @since 1.0.0
		 */
		function wte_trip_review_admin_assets() {
			$screen = get_current_screen();
			if ( $screen->post_type == 'booking' || isset( $_GET['page'] ) && $_GET['page'] == 'reviews' || ( isset( $_GET['action'] ) && $_GET['action'] == 'editcomment' ) ) {

				wp_enqueue_script( 'jquery-ui-datepicker' );

				wp_enqueue_style( 'datepicker-style', WTE_TRIP_REVIEW_FILE_URL . '/assets/css/lib/datepicker-style.css', WTE_TRIP_REVIEW_VERSION, 'all' );

				wp_enqueue_style( 'jquery-rateyo', WTE_TRIP_REVIEW_FILE_URL . '/assets/css/lib/jquery.rateyo.min.css', array(), WTE_TRIP_REVIEW_VERSION, 'all' );

				wp_enqueue_script( 'jquery-rateyo', WTE_TRIP_REVIEW_FILE_URL . '/assets/js/lib/jquery.rateyo.min.js', array( 'jquery' ), WTE_TRIP_REVIEW_VERSION, true );

				wp_enqueue_script( 'wte_trip_review_admin', WTE_TRIP_REVIEW_FILE_URL . '/assets/js/wte-trip-review-admin.js', array( 'jquery' ), '', false );
				wp_localize_script(
					'wte_trip_review_admin',
					'WTEAjaxData',
					array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )
				);

				wp_enqueue_style( 'wte_trip_review_admin', WTE_TRIP_REVIEW_FILE_URL . '/assets/css/wte-trip-review-admin.css', array(), '', 'all' );

			}
		}

		/**
		 * Load frontend Assets.
		 *
		 * @since 1.0.0
		 */
		function wte_trip_review_public_assets() {

			$asset_script_path = '/dist/';
			$version_prefix    = '-' . WTE_TRIP_REVIEW_VERSION;
			$asset_min_path    = '/min/';

			if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
				$asset_script_path = '/';
				$version_prefix    = '';
				$asset_min_path    = '/';
			}

			$ajax_nonce         = wp_create_nonce( 'wtetr_ajax_nonce' );
			$wtetr_object_array = array(
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => $ajax_nonce,
				'plugin_url' => WTE_TRIP_REVIEW_FILE_URL,
				'home_url'   => esc_url( home_url( '/' ) ),
			);

			wp_enqueue_script( 'wte_trip_review_public', WTE_TRIP_REVIEW_FILE_URL . '/assets/js' . $asset_min_path . 'wte-trip-review-public' . $version_prefix . '.js', array( 'jquery', 'dropzone' ), WTE_TRIP_REVIEW_VERSION, true );

			/** to concatenate and remove later -main to add as well */
			wp_enqueue_style( 'wte_trip_review_public', WTE_TRIP_REVIEW_FILE_URL . '/assets/css/wte-trip-review-public.css', array(), WTE_TRIP_REVIEW_VERSION, 'all' );
			/** End of debug css enqueue */

			wp_enqueue_script( 'dropzone', WTE_TRIP_REVIEW_FILE_URL . '/assets/js' . $asset_min_path . 'dropzone' . $version_prefix . '.js', array( 'jquery' ), WTE_TRIP_REVIEW_VERSION, true );

			wp_enqueue_style( 'dropzone', WTE_TRIP_REVIEW_FILE_URL . '/assets/css' . $asset_min_path . 'dropzone' . $version_prefix . '.css', array(), WTE_TRIP_REVIEW_VERSION, 'all' );

			wp_localize_script( 'wte_trip_review_public', 'wtetr_public_js_object', $wtetr_object_array );

			wp_enqueue_style( 'jquery-rateyo', WTE_TRIP_REVIEW_FILE_URL . '/assets/css/lib/jquery.rateyo.min.css', array(), WTE_TRIP_REVIEW_VERSION, 'all' );

			wp_enqueue_script( 'jquery-rateyo', WTE_TRIP_REVIEW_FILE_URL . '/assets/js/lib/jquery.rateyo.min.js', array( 'jquery' ), WTE_TRIP_REVIEW_VERSION, true );

			wp_enqueue_script( 'jquery-appear', WTE_TRIP_REVIEW_FILE_URL . '/assets/js/lib/jquery.appear.js', array( 'jquery' ), WTE_TRIP_REVIEW_VERSION, true );

			wp_enqueue_script( 'fancybox-popup', WTE_TRIP_REVIEW_FILE_URL . '/assets/js/lib/jquery.fancybox.min.js', array( 'jquery', 'wte_trip_review_public' ) );
			wp_enqueue_style( 'fancybox-popup', WTE_TRIP_REVIEW_FILE_URL . '/assets/css/lib/jquery.fancybox.min.css' );
			wp_enqueue_script( 'jquery-mCustomScrollbar', WTE_TRIP_REVIEW_FILE_URL . '/assets/js/lib/jquery.mCustomScrollbar.concat.min.js', array( 'jquery', 'wte_trip_review_public' ) );
			wp_enqueue_style( 'jquery-mCustomScrollbar', WTE_TRIP_REVIEW_FILE_URL . '/assets/css/lib/jquery.mCustomScrollbar.min.css' );

			global $post;

			if ( is_singular( 'trip' ) || ( is_singular() && is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'Wte_All_Trip_Review_List' ) )
			|| ( is_tax( array( 'destination', 'activities', 'trip_types' ) ) ) ) {
				wp_enqueue_script( 'dropzone', WTE_TRIP_REVIEW_FILE_URL . '/assets/js/dropzone.js', array( 'jquery', 'wte_trip_review_public' ), WTE_TRIP_REVIEW_VERSION, true );
				wp_enqueue_style( 'dropzone', WTE_TRIP_REVIEW_FILE_URL . '/assets/css/dropzone.css', WTE_TRIP_REVIEW_VERSION, 'all' );
			}

		}

	}
endif;
$obj = new WTE_Trip_Reviews_Enqueue();
$obj->init();
