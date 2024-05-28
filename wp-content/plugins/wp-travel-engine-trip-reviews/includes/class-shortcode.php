<?php
if ( ! class_exists( 'WTE_Trip_Reviews_Shortcode' ) ) :
	/**
	 * Menu Class.
	 */
	class WTE_Trip_Reviews_Shortcode extends Wte_Trip_Review_Init {

		public function init() {
			// Reviews Shortcode
			add_shortcode( 'Wte_Trip_Review_List', array( $this, 'show_trip_rating_shortcode' ) );
			add_shortcode( 'Wte_All_Trip_Review_List', array( $this, 'wte_all_trip_review_shortcode' ) );
		}

		/*
		*  Shortcode: Display all of the reviews
		*/

		function wte_all_trip_review_shortcode( $atts ) {
			// If REST_REQUEST is defined (by WordPress) and is a TRUE, then it's a REST API request.
			$is_rest_route = ( defined( 'REST_REQUEST' ) && REST_REQUEST );

			if ( ( is_admin() && ! $is_rest_route ) || // admin and AJAX (via admin-ajax.php) requests
				( ! is_admin() && $is_rest_route ) ) {   // REST requests only
				return;
			}
			ob_start();
			$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
			$comments_data             = $this->pull_all_comments_data();
			if ( ! empty( $comments_data ) ) {
				do_action( 'wte_review_wrap_open' );
				do_action( 'wte_company_review_header' );
				do_action( 'wte_average_review_wrap_open' );
				do_action( 'wte_company_review_schema_json' );
				do_action( 'wte_company_average_rating' );
				do_action( 'wte_average_review_wrap_close' );
				do_action( 'wte_company_overall_review' );
				do_action( 'wte_list_all_reviews', $atts );
				do_action( 'wte_review_wrap_close' );
			}
			$data = ob_get_contents();
			ob_get_clean();
			echo $data;
		}

		/*
		*  Shortcode: Display Post/Trip specific reviews
		*/

		function show_trip_rating_shortcode() {
			global $post;
			// If REST_REQUEST is defined (by WordPress) and is a TRUE, then it's a REST API request.
			$is_rest_route = ( defined( 'REST_REQUEST' ) && REST_REQUEST );

			if ( ( is_admin() && ! $is_rest_route ) || // admin and AJAX (via admin-ajax.php) requests
				( ! is_admin() && $is_rest_route ) ) {   // REST requests only
				return;
			}
			ob_start();
			if ( is_singular( 'trip' ) ) {
				if ( class_exists( 'Wte_Trip_Review_Init' ) ) {
					if ( get_comments_number() ) {
						$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
						$object                    = new Wp_Travel_Engine_Functions();
						$priceCurrency             = $object->trip_currency_code( $post );
						$priceCurrency             = ! empty( $priceCurrency ) ? $priceCurrency : 'USD';
						$actual_price              = $object->trip_price( $post->ID );
						$actual_price              = ! empty( $actual_price ) ? $actual_price : 0;
						$content                   = $post->post_content;
						$comments_data             = $this->pull_comment_data( get_the_ID() );
						if ( ! empty( $comments_data ) ) {
							include WTE_TRIP_REVIEW_BASE_PATH . '/includes/function-inc/trip-shortcode-inc.php';
						}
					}
				}
			}
			$output = ob_get_contents();
			ob_end_clean();
			echo $output;
		}
	}
endif;
$obj = new WTE_Trip_Reviews_Shortcode();
$obj->init();
