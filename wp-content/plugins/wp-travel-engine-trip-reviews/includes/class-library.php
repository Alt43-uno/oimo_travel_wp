<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!!' );
if ( ! class_exists( 'WTE_Trip_Review_Library' ) ) {

	class WTE_Trip_Review_Library {

		/**
		 * Prints array in pre format
		 *
		 * @since 1.0.0
		 *
		 * @param array $array
		 */
		function print_array( $array ) {
			echo '<pre>';
			print_r( $array );
			echo '</pre>';
		}

		function wte_review_form_name() {
			if ( ! is_user_logged_in() ) {
				$current_user = wp_get_current_user();

				$review_name_field_placeholder = apply_filters( 'wte_trip_reviews_filtered_review_name_placeholder', __( 'Name*', 'wte-trip-review' ) );

				$form_default_field = '<p class="comment-form-author">' .
					'<label for="author">' . _x( 'Name', 'wte-trip-review' ) . '<span class="required">*</span></label>' .
					'<input id="author" required="" name="author" type="text" placeholder="' . $review_name_field_placeholder . '" value="' . esc_attr( $current_user->user_login ) .
					'" size="30" tabindex="1" aria-required="true" required=""/></p>';
					echo $form_default_field;
			}
		}

		function wte_review_form_email() {
			if ( ! is_user_logged_in() ) {
				$current_user = wp_get_current_user();

				$review_email_field_placeholder = apply_filters( 'wte_trip_reviews_filtered_review_email_placeholder', __( 'Email*', 'wte-trip-review' ) );

				$form_default_field = '<p class="comment-form-email">' .
				'<label for="email">' . _x( 'Email', 'wte-trip-review' ) . '</label>' .
				'<input id="email" required="" name="email" type="text" placeholder="' . $review_email_field_placeholder . '" value="' . esc_attr( $current_user->user_email ) .
				'" size="30"  tabindex="2" aria-required="true" required=""/></p>';
					echo $form_default_field;
			}
		}
		function wte_review_form_website() {
			if ( ! is_user_logged_in() ) {
				$current_user = wp_get_current_user();

				$review_website_field_placeholder = apply_filters( 'wte_trip_reviews_filtered_review_textarea_placeholder', __( 'Website', 'wte-trip-review' ) );

				$form_default_field = '<p class="comment-form-url">' .
				'<label for="url">' . _x( 'Website', 'wte-trip-review' ) . '</label>' .
				'<input id="url" name="url" type="text" placeholder="' . $review_website_field_placeholder . '" value="' . esc_attr( $current_user->user_url ) .
				'" size="30"  tabindex="3" /></p>';

				echo $form_default_field;
			}
		}

		function wte_review_form_textarea() {
			$review_texterea_field_placeholder = apply_filters( 'wte_trip_reviews_filtered_review_textarea_placeholder', __( 'Write review description', 'wte-trip-review' ) );

			$form_default_field = '<div class="comment-form-comment"><label for="comment">' . _x( 'Write Review*', 'noun', 'wte-trip-review' ) .
			'</label><textarea id="comment" required="" placeholder="' . $review_texterea_field_placeholder .
			'" name="comment" aria-required="true"></textarea></div>';
			echo $form_default_field;
		}

		function wte_review_form_consent() {
			if ( ! is_user_logged_in() ) {
				$form_default_field = '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"> <label for="wp-comment-cookies-consent">' . __( 'Save my name, email, and website in this browser for the next time I comment.', 'wte-trip-review' ) . '</label></p>';
				echo $form_default_field;
			}
		}

		/**
		 * function
		 * Custom Function to pull the post type singular
		 *
		 * @return void
		 */
		function get_current_post_type() {

			global $post, $typenow, $current_screen;

			if ( $post && $post->post_type ) {
				return $post->post_type;

			} elseif ( $typenow ) {
				return $typenow;

			} elseif ( $current_screen && $current_screen->post_type ) {
				return $current_screen->post_type;

			} elseif ( isset( $_REQUEST['post_type'] ) ) {
				return sanitize_key( $_REQUEST['post_type'] );
			}

			return null;

		}

		function wpte_trip_post_select( $select_id, $post_type, $selected = 0 ) {
			$post_type_object = get_post_type_object( $post_type );
			$label            = $post_type_object->label;
			echo '<label class="wpte-field-label">Select ' . $label . ':<span class="required">*</span></label>';
			$posts = get_posts(
				array(
					'post_type'        => $post_type,
					'post_status'      => 'publish',
					'suppress_filters' => false,
					'posts_per_page'   => -1,
				)
			);
			echo '<select name="' . $select_id . '" id="' . $select_id . '">';
			echo '<option value = "" >Select ' . $label . ' </option>';
			foreach ( $posts as $post ) {
				echo '<option value="', $post->ID, '"', $selected == $post->ID ? ' selected="selected"' : '', '>', $post->post_title, '</option>';
			}
			echo '</select>';
		}

		function find_client_location() {
			$geolocate_client_class = new WTE_TripReview_GeoLocate();
			$geolocate_client_class->locate();
			if ( isset( $geolocate_client_class->countryName ) && ! empty( $geolocate_client_class->countryName ) ) {
				$client_location = $geolocate_client_class->countryName;
			} else {
				$client_location = '';
			}
			return $client_location;
		}

		function wte_tr_handle_attachment( $file_handler, $post_id, $set_thu = false ) {
			if ( $_FILES[ $file_handler ]['error'] !== UPLOAD_ERR_OK ) {
				__return_false();
			}
			require_once ABSPATH . 'wp-admin' . '/includes/image.php';
			require_once ABSPATH . 'wp-admin' . '/includes/file.php';
			require_once ABSPATH . 'wp-admin' . '/includes/media.php';
			$attach_id = media_handle_upload( $file_handler, $post_id );
			if ( is_wp_error( $attach_id ) ) {
				return;
			}
			return $attach_id;
		}

		function pull_comment_data( $postid ) {
			$args         = array(
				'post_id'        => $postid,
				'status'         => 'approve',
				'orderby'        => 'comment_date',
				'comment_parent' => 0,
			);
			$comments     = get_comments( $args );
			$comment_data = array();
			include WTE_TRIP_REVIEW_BASE_PATH . '/includes/function-inc/comment-data-inc.php';
			return $comment_data;
		}

		function pull_all_comments_data() {
			global $post;
			$args         = array(
				'post_type'      => 'trip',
				'orderby'        => 'comment_date',
				'status'         => 'approve',
				'comment_parent' => 0,
			);
			$comments     = get_comments( $args );
			$comment_data = array();
			include WTE_TRIP_REVIEW_BASE_PATH . '/includes/function-inc/comment-data-inc.php';
			return $comment_data;
		}

		function overall_textlist_of_responses() {
			$wp_travel_engine_settings                = get_option( 'wp_travel_engine_settings', true );
			$review_repsonse_texts                    = array();
			$review_repsonse_texts['excellent_label'] = isset( $wp_travel_engine_settings['trip_reviews']['excellent_label'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['excellent_label'] ) : __( 'Excellent', 'wte-trip-review' );
			$review_repsonse_texts['vgood_label']     = isset( $wp_travel_engine_settings['trip_reviews']['vgood_label'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['vgood_label'] ) : __( 'Very Good', 'wte-trip-review' );
			$review_repsonse_texts['average_label']   = isset( $wp_travel_engine_settings['trip_reviews']['average_label'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['average_label'] ) : __( 'Average', 'wte-trip-review' );
			$review_repsonse_texts['poor_label']      = isset( $wp_travel_engine_settings['trip_reviews']['poor_label'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['poor_label'] ) : __( 'Poor', 'wte-trip-review' );
			$review_repsonse_texts['terrible_label']  = isset( $wp_travel_engine_settings['trip_reviews']['terrible_label'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['terrible_label'] ) : __( 'Terrible', 'wte-trip-review' );
			return $review_repsonse_texts;
		}

		/**
		 * Internal Function: Pull emoticon List
		 *
		 * @return void
		 */
		function emoticon_lists() {
			$review_emoticons_list                   = array();
			$review_emoticons_list['excellent_icon'] = ':grin:';
			$review_emoticons_list['vgood_icon']     = ':smile:';
			$review_emoticons_list['average_icon']   = ':???:';
			$review_emoticons_list['poor_icon']      = ':sad:';
			$review_emoticons_list['terrible_icon']  = ':mad:';
			$review_emoticons_lists                  = apply_filters( 'filtered_emoticons_icon_for_review', $review_emoticons_list );
			return $review_emoticons_lists;
		}

		/** Trip Reveiw Templates for form */
		function wte_trip_review_template() {
			$rating_field_placeholder = apply_filters( 'wte_trip_reviews_filtered_rating_placeholder', __( 'Ratings*', 'wte-trip-review' ) );
			?>
			<div id="wte-trip-review-template">
				<p class="comment-form-wte-trip-review-rating">
					<label for="wte-trip-review-rating"><?php echo $rating_field_placeholder; ?>
					<span class="required">*</span></label>
					<span class="commentwte_trip_review_ratingbox">
						<fieldset class="wte-trip-review-rating">
							<input type="radio" name="stars" id="5_stars" value="5" required>
							<label class="stars" for="5_stars">5 stars</label>
							<input type="radio" name="stars" id="4_stars" value="4">
							<label class="stars" for="4_stars">4 stars</label>
							<input type="radio" name="stars" id="3_stars" value="3">
							<label class="stars" for="3_stars">3 stars</label>
							<input type="radio" name="stars" id="2_stars" value="2">
							<label class="stars" for="2_stars">2 stars</label>
							<input type="radio" name="stars" id="1_stars" value="1">
							<label class="stars" for="1_stars">1 star</label>
						</fieldset>
					</span>
				</p>
			</div>
			<?php
		}

		function wte_review_form_wrap_open() {
			echo '<div class="review-title-rating">';
		}
		function wte_review_combined_wrap_open() {
			echo '<div class="review-combined-field">';
		}
		function wte_review_combined_wrap_close() {
			echo '</div>';
		}
		function wte_review_form_wrap_close() {
			echo '</div>';
		}
		function wte_review_form_review_rate() {

			$rating_field_placeholder = apply_filters( 'wte_trip_reviews_filtered_rating_placeholder', __( 'Ratings*', 'wte-trip-review' ) );

			$form_default_review_rate_field = '<p class="comment-form-title comment-form-wte-trip-review-rating">' .
			'<label for="wte-trip-review-rating">' . $rating_field_placeholder . '<span class="required">*</span></label>' .
			'<fieldset class="wte-trip-review-rating">
                <input type="radio" name="stars" id="5_stars" value="5" required>
                <label class="stars" for="5_stars">5 stars</label>
                <input type="radio" name="stars" id="4_stars" value="4" >
                <label class="stars" for="4_stars">4 stars</label>
                <input type="radio" name="stars" id="3_stars" value="3" >
                <label class="stars" for="3_stars">3 stars</label>
                <input type="radio" name="stars" id="2_stars" value="2" >
                <label class="stars" for="2_stars">2 stars</label>
                <input type="radio" name="stars" id="1_stars" value="1" >
                <label class="stars" for="1_stars">1 star</label>
            </fieldset></p>';
			$review_form_rating_section     = apply_filters( 'custom_trip_review_rating_icon_input', $form_default_review_rate_field );
			echo $review_form_rating_section;
		}

		function wte_review_form_review_title() {
			$review_title_field_placeholder = apply_filters( 'wte_trip_reviews_filtered_review_title_placeholder', __( 'Add review title', 'wte-trip-review' ) );

			echo '<p class="comment-form-title">' .
			'<label for="title">' . __( 'Review Title*', 'wte-trip-review' ) . '</label>' .
			'<input id="title" required name="title" placeholder="' . $review_title_field_placeholder . '" type="text" size="30"  tabindex="5" /></p>';
		}

		function wte_review_form_experience_date() {
			$wp_travel_engine_settings         = get_option( 'wp_travel_engine_settings' );
			$hide_experience_date_field        = isset( $wp_travel_engine_settings['trip_reviews']['hide_experience_date_field'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_experience_date_field'] ) : '';
			$experience_date_field_placeholder = apply_filters( 'wte_trip_reviews_filtered_experience_date_placeholder', __( 'Date Of Experience', 'wte-trip-review' ) );

			if ( empty( $hide_experience_date_field ) ) {

				echo '<p class="comment-form-title comment-form-experience-date">' .
				'<label for="date-of-experience">' . __( 'Date Of Experience', 'wte-trip-review' ) . '</label>' .
				'<input id="date-of-experience" autocomplete="off" name="experience_date" placeholder="' . $experience_date_field_placeholder . '" type="text" tabindex="5" />' .
				'</p>';
			}
		}

		function wte_review_form_photo_gallery() {
			$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings' );
			$hide_image_upload_field   = isset( $wp_travel_engine_settings['trip_reviews']['hide_image_upload_field'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_image_upload_field'] ) : '';
			$gallery_field_label       = apply_filters(
                'wte_trip_reviews_filtered_image_upload_field_label',
                sprintf( __( '%2$sDrop your Image or click to %1$sbrowse%3$s%3$s%4$sSupported file : JPG, PNG, GIF%3$s', 'wte-trip-review' ), '<span>', '<span class="wte-reviews-dropzone-placeholder">', '</span>', '<span class="supported-message">' ) );
			$gallery_upload_svg_icon   = apply_filters(
				'wte_trip_reviews_filtered_image_upload_field_svg',
				'<svg width="44" height="42" viewBox="0 0 44 42" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M35.7845 11.2873C35.314 8.49148 33.9297 5.93997 31.8125 4.00372C29.46 1.85032 26.4018 0.665039 23.2169 0.665039C20.7559 0.665039 18.3582 1.37078 16.3043 2.70082C14.5943 3.80467 13.1738 5.29757 12.1694 7.05287C11.7351 6.97143 11.2827 6.9262 10.8304 6.9262C6.98499 6.9262 3.85441 10.0568 3.85441 13.9021C3.85441 14.3998 3.9087 14.8793 3.99918 15.3498C1.511 17.1594 0 20.0728 0 23.1763C0 25.6825 0.931935 28.1164 2.63294 30.0436C4.37919 32.0161 6.68641 33.1832 9.14744 33.319C9.17458 33.319 9.19268 33.319 9.21982 33.319H17.001C17.6796 33.319 18.2225 32.7761 18.2225 32.0975C18.2225 31.4189 17.6796 30.876 17.001 30.876H9.25601C5.55542 30.6498 2.44294 27.1302 2.44294 23.1672C2.44294 20.6066 3.81822 18.218 6.03496 16.9241C6.55069 16.6256 6.76784 16.0013 6.56878 15.4403C6.38783 14.9517 6.29735 14.436 6.29735 13.884C6.29735 11.3868 8.33313 9.35104 10.8304 9.35104C11.3642 9.35104 11.889 9.44151 12.3775 9.62247C12.9747 9.83962 13.6352 9.56819 13.9066 8.99817C15.5986 5.40615 19.254 3.08988 23.226 3.08988C28.5643 3.08988 32.9706 7.08906 33.4773 12.3911C33.5316 12.9431 33.9478 13.3864 34.4906 13.4769C38.517 14.1645 41.5571 17.8832 41.5571 22.1267C41.5571 26.6235 38.0193 30.5322 33.6582 30.867H26.9899C26.3113 30.867 25.7685 31.4099 25.7685 32.0884C25.7685 32.767 26.3113 33.3099 26.9899 33.3099H33.7035C33.7306 33.3099 33.7578 33.3099 33.794 33.3099C36.5536 33.1109 39.1322 31.8442 41.0504 29.7269C42.9595 27.6278 44 24.9315 44 22.1267C43.991 17.0508 40.5256 12.5359 35.7845 11.2873Z" fill="#2183DF"/>
            <path d="M29.3332 24.3347C29.8127 23.8552 29.8127 23.0861 29.3332 22.6066L22.8639 16.1373C22.6377 15.9111 22.3211 15.7754 22.0044 15.7754C21.6877 15.7754 21.371 15.9021 21.1448 16.1373L14.6756 22.6066C14.196 23.0861 14.196 23.8552 14.6756 24.3347C14.9108 24.57 15.2275 24.6966 15.5351 24.6966C15.8428 24.6966 16.1594 24.579 16.3947 24.3347L20.7829 19.9465V40.1143C20.7829 40.7929 21.3258 41.3357 22.0044 41.3357C22.683 41.3357 23.2259 40.7929 23.2259 40.1143V19.9465L27.6141 24.3347C28.0846 24.8143 28.8537 24.8143 29.3332 24.3347Z" fill="#2183DF"/>
            </svg>
            '
			);
			if ( empty( $hide_image_upload_field ) ) {
				echo '<legend>' . __( 'Add Images', 'wte-trip-review' ) . '</legend>';
				echo '<p class="comment-form-title comment-form-photo-gallery">' .
				'<label for="input-review-images">' . __( 'Photo Gallery', 'wte-trip-review' ) . '</label><span class="comment-form-photo-gallery">' .
				'<span class="file-type-error" style="display:none;">' . __( 'Invalid File Type. Supported File Type: JPEG/JPG, PNG.', 'wte-trip-review' ) . '</span>' .
				'<span class="review-upload-image-text" style="display:none;">' . $gallery_field_label . '</span>' .
				'<span class="review-upload-image-svg" style="display:none;">' . $gallery_upload_svg_icon . '</span>' .
				'<div id="wpte-upload-review-images" class="fallback dropzone input-review-images"></div>' .
				'</p>';
			}
		}
	}

	/**
	 * Locate a template and return the path for inclusion.
	 *
	 * This is the load order:
	 *
	 * yourtheme/$template_path/$template_name
	 * yourtheme/$template_name
	 * $default_path/$template_name
	 *
	 * @since 1.0.0
	 *
	 * @param string $template_name Template name.
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path Default path. (default: '').
	 *
	 * @return string Template path.
	 */
	function wte_trip_reviews_locate_template( $template_name, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) {
			$template_path = apply_filters( 'wp_travel_engine_trip_reviews_template_path', 'wp-travel-engine-trip-reviews/' );
		}

		if ( ! $default_path ) {
			$default_path = apply_filters( 'wp_travel_engine_trip_reviews_filtered_template_path', WTE_TRIP_REVIEW_BASE_PATH . '/includes/function-inc/template-inner/' );
		}

		// Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		// Get default template.
		if ( ! $template ) {
			// Look within passed path within the theme - this is priority.
			$template = locate_template(
				array(
					trailingslashit( $template_name ),
					$template_name,
				)
			);
			if ( ! $template ) {
				$template = $default_path . $template_name;
			}
		}

		// Return what we found.
		return apply_filters( 'wte_trip_reviews_locate_template', $template, $template_name, $template_path );
	}

	/**
	 * Get other templates (e.g. article attributes) passing attributes and including the file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $template_name   Template name.
	 * @param array  $args            Arguments. (default: array).
	 * @param string $template_path   Template path. (default: '').
	 * @param string $default_path    Default path. (default: '').
	 */
	function wte_trip_reviews_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

		$template = wte_trip_reviews_locate_template( $template_name, $template_path, $default_path );

		// Allow 3rd party plugin filter template file from their plugin.
		$filter_template = apply_filters( 'wte_trip_reviews_get_template', $template, $template_name, $args, $template_path, $default_path );

		if ( $filter_template !== $template ) {
			if ( ! file_exists( $filter_template ) ) {
				/* translators: %s template */
				wte_doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'wte-trip-review' ), '<code>' . $template . '</code>' ), '1.0.0' );
				return;
			}
			$template = $filter_template;
		}

		$action_args = array(
			'template_name' => $template_name,
			'template_path' => $template_path,
			'located'       => $template,
			'args'          => $args,
		);
		if ( ! empty( $args ) && is_array( $args ) ) {
			if ( isset( $args['action_args'] ) ) {
				wte_doing_it_wrong(
					__FUNCTION__,
					__( 'action_args should not be overwritten when calling wte_trip_reviews_get_template.', 'wte-trip-review' ),
					'1.0.0'
				);
				unset( $args['action_args'] );
			}
			extract( $args );
		}
		include $action_args['located'];
	}
}
