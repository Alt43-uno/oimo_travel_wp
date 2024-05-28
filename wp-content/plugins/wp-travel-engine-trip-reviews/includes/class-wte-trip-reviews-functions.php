<?php
/**
 * Comment Form Modification for WP Travel Engine Trip Reviews
 */
if ( ! class_exists( 'WTE_Trip_Reviews_Functions' ) ) :
	/**
	 * //Comment Form Modification Class.
	 */
	class WTE_Trip_Reviews_Functions extends WTE_Trip_Review_Library {

		// Comment Form Modification
		public function init() {
			add_filter( 'comment_form_fields', array( $this, 'wte_review_comment_form_fields' ) );
			add_filter( 'comment_form_defaults', array( $this, 'wpte_modify_comment_form_class' ) );
			add_action( 'add_meta_boxes_comment', array( $this, 'wte_trip_review_extend_comment_add_meta_box' ) );
			add_action( 'edit_comment', array( $this, 'wte_trip_review_extend_comment_edit_metafields' ) );
			add_filter( 'comment_text', array( $this, 'wte_trip_modify_comment' ) );
			add_action( 'comment_post', array( $this, 'wpte_comment_email_notification' ), 11, 2 );
			add_action( 'comment_post', array( $this, 'wte_trip_review_save_comment_meta_data' ) );
			add_action( 'wte_review_form_wrap_open', array( $this, 'wte_review_form_wrap_open' ) );
			add_action( 'wte_review_form_wrap_close', array( $this, 'wte_review_form_wrap_close' ) );
			add_action( 'wte_review_combined_wrap_open', array( $this, 'wte_review_combined_wrap_open' ) );
			add_action( 'wte_review_combined_wrap_open', array( $this, 'add_form_personal_legend' ), 9 );
			add_action( 'wte_review_combined_wrap_close', array( $this, 'wte_review_combined_wrap_close' ) );
			add_action( 'wte_review_form_review_rate', array( $this, 'wte_review_form_review_rate' ) );
			add_action( 'wte_review_form_review_title', array( $this, 'wte_review_form_review_title' ) );
			add_action( 'wte_review_form_experience_date', array( $this, 'wte_review_form_experience_date' ) );
			add_action( 'wte_review_form_photo_gallery', array( $this, 'wte_review_form_photo_gallery' ) );
			add_action( 'review_form_rating_section', array( $this, 'act_func_review_form_rating_section' ) );
			add_filter( 'custom_trip_review_rating_icon_input', array( $this, 'filt_func_custom_trip_review_rating_icon_input' ) );
			add_action( 'wte_review_form_name', array( $this, 'wte_review_form_name' ) );
			add_action( 'wte_review_form_email', array( $this, 'wte_review_form_email' ) );
			add_action( 'wte_review_form_website', array( $this, 'wte_review_form_website' ) );
			add_action( 'wte_review_form_textarea', array( $this, 'wte_review_form_textarea' ) );
			add_action( 'wte_review_form_consent', array( $this, 'wte_review_form_consent' ) );
			add_filter( 'comment_post_redirect', array( $this, 'redirect_after_comment' ) );
		}

		function add_form_personal_legend() {
			if ( is_user_logged_in() ) return;
			echo '<legend>' . __( 'Personal Information', 'wte-trip-review' ) . '</legend>';
		}

		/**
		 * Redirect to same page after Review form submission instead of unapproved comment url
		 */
		function redirect_after_comment( $location ) {
			return $_SERVER['HTTP_REFERER'];
		}

		function wte_review_comment_form_fields( $fields ) {

			if ( get_post_type() == 'trip' ) {
				ob_start();
				if ( ! is_user_logged_in() ) {
					if ( isset( $fields['author'] ) ) :
						unset( $fields['author'] );
					endif;
					if ( isset( $fields['email'] ) ) :
						unset( $fields['email'] );
					endif;
					if ( isset( $fields['url'] ) ) :
						unset( $fields['url'] );
					endif;
					if ( isset( $fields['cookies'] ) ) :
						unset( $fields['cookies'] );
					endif;
				}
				if ( isset( $fields['comment'] ) ) :
					unset( $fields['comment'] );
				endif;

				do_action( 'wte_review_form_wrap_open' );
				do_action( 'wte_review_form_review_rate' );
				do_action( 'wte_review_form_review_title' );
				do_action( 'wte_review_form_textarea' );
				do_action( 'wte_review_combined_wrap_open' );
				do_action( 'wte_review_form_name' );
				do_action( 'wte_review_form_experience_date' );
				do_action( 'wte_review_form_email' );
				do_action( 'wte_review_form_website' );
				do_action( 'wte_review_combined_wrap_close' );
				do_action( 'wte_review_form_photo_gallery' );
				do_action( 'wte_review_form_wrap_close' );
				do_action( 'wte_review_form_consent' );

				$output = ob_get_clean();
				$output = apply_filters( 'wte_filtered_trip_all_custom_form_fields', $output );
				echo $output;
			}
			return $fields;
		}


		// Add fields after default fields above the comment box, always visible
		public function wte_trip_review_additional_fields() {
			ob_start();
			if ( get_post_type() == 'trip' ) {
				do_action( 'wte_review_form_wrap_open' );
				do_action( 'wte_review_form_review_title' );
				do_action( 'wte_review_form_review_rate' );
				do_action( 'wte_review_form_experience_date' );
				do_action( 'wte_review_form_photo_gallery' );
				do_action( 'wte_review_form_wrap_close' );
			}
			$output = ob_get_clean();
			$output = apply_filters( 'wte_filtered_trip_additional_form_fields', $output );
			echo $output;
		}

		/** Resuable Test function: To filter icon for review in Review Form
		 * For trip review - Custom Rate Icon - Like Trip Advisor
		 */
		function filt_func_custom_trip_review_rating_icon_input() {
			$icon_type       = '';
			$icon_fill_color = '#F39C12';

			$review_icon_type        = apply_filters( 'trip_rating_icon_type', $icon_type );
			$review_icon_fill_colors = apply_filters( 'trip_rating_icon_fill_color', $icon_fill_color );
			$form_review_part        = '';
			$form_review_part       .= '<div class="comment-form-title comment-form-wte-trip-review-rating">
			<label for="wte-trip-review-rating">' . __( 'Ratings', 'wte-trip-review' ) . '<span class="required">*</span></label>';
			$form_review_part       .= '<fieldset id="wte-trip-review-rating-field" class="wte-trip-review-rating trip-review-form-rating">' .
					'<div class="review-form-rating ' . ( ! empty( $review_icon_type ) ? 'svg-trip-adv' : 'trip-review-default' ) . '"
				data-icon-type="' . htmlspecialchars( $review_icon_type ) . '"
				data-rateyo-rated-fill="' . $review_icon_fill_colors . '"
				>
				</div>' .
					'<input type="hidden" required="required" name="stars" value="0" >
			</fieldset>';
			$form_review_part       .= '<span id="rating-error" class="rating-rating-field comment-rating-field-message error" for="rating" style="display:none">' . __( 'This field is required', 'wte-trip-review' ) . '</span></div>';
			return $form_review_part;
		}

		public function wpte_modify_comment_form_class( $defaults ) {
			if ( get_post_type() == 'trip' ) {
				$defaults['class_form'] = 'rating-form';
			}
			return $defaults;
		}

		// Add an edit option in comment edit screen
		public function wte_trip_review_extend_comment_add_meta_box( $comment ) {

			if ( get_post_type( $comment->comment_post_ID ) == 'trip' ) {
					add_meta_box( 'title', __( 'Comment Metadata - WTE Trip Review Comment', 'wte-trip-review' ), array( $this, 'wte_trip_review_extend_comment_meta_box' ), 'comment', 'normal', 'high' );
			}
		}

		public function wte_trip_review_extend_comment_meta_box( $comment ) {
			$title             = get_comment_meta( $comment->comment_ID, 'title', true );
			$stars             = get_comment_meta( $comment->comment_ID, 'stars', true );
			$photo             = get_comment_meta( $comment->comment_ID, 'photo', true );
			$experience_date   = get_comment_meta( $comment->comment_ID, 'experience_date', true );
			$client_location   = get_comment_meta( $comment->comment_ID, 'client_location', true );
			$gallery_images    = get_comment_meta( $comment->comment_ID, 'gallery_images', true );
			$gallery_max_count = get_comment_meta( $comment->comment_ID, 'gallery_max_count', true );
			wp_nonce_field( 'extend_comment_update', 'extend_comment_update', false );
			?>
<p class="meta-trip-review-title">
	<label for="title"><?php _e( 'Review Title', 'wte-trip-review' ); ?>
		<span class="required">*</span>
	</label>
	<span class="commentwte_trip_review_title">

	</span>
	<fieldset class="wte-trip-review-title">
		<input type="text" name="title" id="title" placeholder="<?php _e( 'Review Title', 'wte-trip-review' ); ?>"
			value="<?php echo esc_attr( $title ); ?>" class="widefat" />
	</fieldset>
</p>

<p class="meta-trip-review-rates comment-form-wte-trip-review-rating">
	<label for="wte-trip-review-rating"><?php _e( 'Rating', 'wte-trip-review' ); ?>
		<span class="required">*</span>
	</label>
	<span class="commentwte_trip_review_ratingbox">
		<fieldset class="wte-trip-review-rating">
			<input type="radio" name="stars" id="5_stars" value="5" <?php checked( $stars, 5 ); ?> required>
			<label class="stars" for="5_stars">5 stars</label>
			<input type="radio" name="stars" id="4_stars" value="4" <?php checked( $stars, 4 ); ?>>
			<label class="stars" for="4_stars">4 stars</label>
			<input type="radio" name="stars" id="3_stars" value="3" <?php checked( $stars, 3 ); ?>>
			<label class="stars" for="3_stars">3 stars</label>
			<input type="radio" name="stars" id="2_stars" value="2" <?php checked( $stars, 2 ); ?>>
			<label class="stars" for="2_stars">2 stars</label>
			<input type="radio" name="stars" id="1_stars" value="1" <?php checked( $stars, 1 ); ?>>
			<label class="stars" for="1_stars">1 star</label>
		</fieldset>
	</span>
</p>
<p class="meta-trip-review-experiance-date">
	<label for="date-of-experience"><?php _e( 'Date Of Experience', 'wte-trip-review' ); ?></label>
	<span class="commentwte_trip_review_experience_date">
		<fieldset class="wte-trip-review-experience-date">
			<input id="date-of-experience" name="experience_date"
				placeholder="<?php _e( 'Date Of Experience', 'wte-trip-review' ); ?>"
				value="<?php echo esc_attr( $experience_date ); ?>" type="text" tabindex="5" />
		</fieldset>
	</span>
</p>
<p class="meta-trip-review-client-location">
	<label for="client-location"><?php _e( 'Client Location', 'wte-trip-review' ); ?></label>
	<span class="commentwte_trip_review_client_location">
		<fieldset class="wte-trip-review-client-location">
			<input type="text" name="client_location" id="client-location"
				placeholder="<?php _e( 'Client Location', 'wte-trip-review' ); ?>"
				value="<?php echo esc_attr( $client_location ); ?>" class="widefat" />
		</fieldset>
	</span>
</p>
			<?php
			if ( $photo ) {
				?>
<div class="image-upload-wrap">
	<label for="wte-trip-review-rating"><?php _e( 'Photo', 'wte-trip-review' ); ?>
		<span class="required">*</span>
	</label>
	<div class="commenter-photo">
					<?php echo wp_get_attachment_image( $photo, 'thumbnail' ); ?>
	</div>
	<div class="image-upload">
		<input type="hidden" id="image_url" name="upload" value="<?php echo esc_attr( $photo ); ?>">
		<input type="button" id="upload-btn" class="button button-primary"
			value="<?php _e( 'Upload Photo', 'wte-trip-review' ); ?>">
		<input type="button" id="remove-btn" class="button button-secondary"
			value="<?php _e( 'Remove Photo', 'wte-trip-review' ); ?>">
	</div>
</div>
				<?php
			}
			?>
<p class="meta-review-client-location">
	<label for="client-location"><?php _e( 'Gallery Images', 'wte-trip-review' ); ?></label>
	<span class="commentwte_trip_review_gallery_images">
		<fieldset class="wte-trip-review-gallery_images">
			<a class='trip-review-img-gallery-add button button-secondary' href='#'
				data-uploader-title='<?php _e( 'Add image to gallery', 'wte-trip-review' ); ?>'
				data-uploader-button-text='<?php _e( 'Add Image(s)', 'wte-trip-review' ); ?>' style="max-height: 35px;">
				<i class="fas fa-plus"></i> <?php _e( 'Add Image(s)', 'wte-trip-review' ); ?>
			</a>
			<ul class="wte-trip-review-gallery">
				<?php
				if ( ! empty( $gallery_images ) ) {
					foreach ( $gallery_images as $keys => $val ) {
						$keyArray          = array();
						$keyArray[ $keys ] = $keys;
						$image             = wp_get_attachment_image_src( $val );
						?>
				<li>
					<input type="hidden" class="trip-gallery-hidden" name="gallery_images[]"
						value="<?php echo esc_attr( intval( $val ) ); ?> ">
					<img class="wte-tr-image-preview" src="<?php echo esc_url( $image[0] ); ?>">
					<div class="trip-gallery-img-action">
						<a class="wte-tr-change-image" href="#"
							data-uploader-title="<?php _e( 'Change Image', 'wte-trip-review' ); ?>"
							data-uploader-button-text="<?php _e( 'Change image', 'wte-trip-review' ); ?>"
							title="<?php _e( 'Change Image', 'wte-trip-review' ); ?>"><i class="fas fa-sync-alt"></i>
						</a>
						<a class="wte-tr-remove-image" href="#"><i class="fas fa-trash-alt"></i>
						</a>
					</div>
				</li>
						<?php
					}
					$max_key = ( isset( $keyArray ) && ! empty( $keyArray ) ) ? array_keys( $keyArray, max( $keyArray ) ) : array( '0' => '0' );
					?>
				<input type="hidden" name="gallery_max_count" value="<?php echo $max_key[0]; ?>"
					class="wte-trip-review-max-img-count" />
				<?php } ?>
			</ul>
		</fieldset>
	</span>
</p>
			<?php

		}

		// Update comment meta data from comment edit screen
		public function wte_trip_review_extend_comment_edit_metafields( $comment_id ) {
			$comment = get_comment( $comment_id );
			if ( get_post_type( $comment->comment_post_ID ) == 'trip' ) {
				if ( ! isset( $_POST['extend_comment_update'] ) || ! wp_verify_nonce( $_POST['extend_comment_update'], 'extend_comment_update' ) ) {
					return;
				}

				if ( ( isset( $_POST['phone'] ) ) && ( $_POST['phone'] != '' ) ) :
					$phone = wp_filter_nohtml_kses( $_POST['phone'] );
					update_comment_meta( $comment_id, 'phone', $phone );
				else :
					delete_comment_meta( $comment_id, 'phone' );
				endif;

				if ( ( isset( $_POST['title'] ) ) && ( $_POST['title'] != '' ) ) :
					$title = wp_filter_nohtml_kses( $_POST['title'] );
					update_comment_meta( $comment_id, 'title', $title );
				else :
					delete_comment_meta( $comment_id, 'title' );
				endif;

				if ( ( isset( $_POST['stars'] ) ) && ( $_POST['stars'] != '' ) ) :
					$stars = wp_filter_nohtml_kses( $_POST['stars'] );
					update_comment_meta( $comment_id, 'stars', $stars );
				else :
					delete_comment_meta( $comment_id, 'stars' );
				endif;

				if ( ( isset( $_POST['upload'] ) ) && ( $_POST['upload'] != '' ) ) :
					$photo = wp_filter_nohtml_kses( $_POST['upload'] );
					update_comment_meta( $comment_id, 'photo', $photo );
				else :
					delete_comment_meta( $comment_id, 'photo' );
				endif;

				if ( ( isset( $_POST['experience_date'] ) ) && ( $_POST['experience_date'] != '' ) ) :
					$photo = wp_filter_nohtml_kses( $_POST['experience_date'] );
					update_comment_meta( $comment_id, 'experience_date', $photo );
				else :
					delete_comment_meta( $comment_id, 'experience_date' );
				endif;

				// $client_location = $this->find_client_location();
				if ( ( isset( $_POST['client_location'] ) ) && ! empty( $_POST['client_location'] != '' ) ) :
					$client_location = wp_filter_nohtml_kses( $_POST['client_location'] );
					update_comment_meta( $comment_id, 'client_location', $client_location );
				else :
					delete_comment_meta( $comment_id, 'client_location' );
				endif;

				if ( ( isset( $_POST['gallery_images'] ) ) && ( $_POST['gallery_images'] != '' ) ) :
					$gallery_images = array_map( 'sanitize_text_field', wp_unslash( $_POST['gallery_images'] ) );
					update_comment_meta( $comment_id, 'gallery_images', $gallery_images );
				else :
					delete_comment_meta( $comment_id, 'gallery_images' );
				endif;

				if ( ( isset( $_POST['gallery_max_count'] ) ) && ( $_POST['gallery_max_count'] != '' ) ) :
					$gallery_max_count = wp_filter_nohtml_kses( $_POST['gallery_max_count'] );
					update_comment_meta( $comment_id, 'gallery_max_count', $gallery_max_count );
				else :
					delete_comment_meta( $comment_id, 'gallery_max_count' );
				endif;

				$status = wp_get_comment_status( $comment_id );
				if ( 'approved' === $status ) {
					wp_set_comment_status( $comment_id, 'approved' );
				} else {
					wp_set_comment_status( $comment_id, 'hold' );
				}
			}
		}

		// Add the comment meta (saved earlier) to the comment text
		// You can also output the comment meta values directly in comments template
		public function wte_trip_modify_comment( $text ) {
			if ( get_post_type() == 'trip' ) {

				if ( $commenttitle = get_comment_meta( get_comment_ID(), 'title', true ) ) {
					$commenttitle = '<strong>' . esc_attr( $commenttitle ) . '</strong><br/>';
					$text         = $commenttitle . $text;
				}

				if ( $commentwte_trip_review_rating = get_comment_meta( get_comment_ID(), 'stars', true ) ) {

					$offstar                       = 5 - $commentwte_trip_review_rating;
					$onstar                        = $commentwte_trip_review_rating;
					$commentwte_trip_review_rating = '<p class="comment-wte-trip-review-rating"><img src="' . WTE_TRIP_REVIEW_FILE_URL . '/images/' . $onstar . 'star.gif"/>';

					for ( $i = 0; $i < $offstar; $i++ ) {
						$commentwte_trip_review_rating .= '<img src="' . WTE_TRIP_REVIEW_FILE_URL . '/assets/icons/star_off.png"/>';
					}

					$text = $text . $commentwte_trip_review_rating;
					return $text;
				} else {
					return $text;
				}
			} else {
				return $text;
			}
		}

		public function wpte_comment_email_notification( $comment_ID, $comment_approved ) {

			$post_type = get_post_type();
			if ( $post_type !== 'trip' ) {
				return;
			}
			$comment      = get_comment( $comment_ID );
			$post_ID      = $comment->comment_post_ID;
			$author_ID    = get_post_field( 'post_author', $post_ID );
			$author_email = get_option( 'admin_email' );
			if ( isset( $author_email ) ) {
				$message = 'New comment on <a href="' . get_permalink( $post_ID ) . '">' .
					get_the_title( $post_ID ) . '</a>';
				// add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
				wp_mail( $author_email, 'New Comment', $message );
			}
		}

		// Save the comment meta data along with comment
		public function wte_trip_review_save_comment_meta_data( $comment_id ) {
			$comment = get_comment( $comment_id );
			if ( isset( $_POST['nonce'] ) || wp_verify_nonce( $_POST['nonce'], 'admin_comment_update' ) ) {
				return;
			}

			if ( get_post_type( $comment->comment_post_ID ) == 'trip' ) {

				wp_set_comment_status( $comment_id, 'hold' );

				if ( ( isset( $_POST['phone'] ) ) && ( $_POST['phone'] != '' ) ) {
					$phone = wp_filter_nohtml_kses( $_POST['phone'] );
				}
				add_comment_meta( $comment_id, 'phone', $phone );

				if ( ( isset( $_POST['title'] ) ) && ( $_POST['title'] != '' ) ) {
					$title = wp_filter_nohtml_kses( $_POST['title'] );
				}
				add_comment_meta( $comment_id, 'title', $title );

				if ( ( isset( $_POST['stars'] ) ) && ( $_POST['stars'] != '' ) ) {
					$stars = wp_filter_nohtml_kses( $_POST['stars'] );
				}
				add_comment_meta( $comment_id, 'stars', $stars );

				if ( ( isset( $_POST['experience_date'] ) ) && ( $_POST['experience_date'] != '' ) ) {
					$experience_date = wp_filter_nohtml_kses( $_POST['experience_date'] );
				}
				add_comment_meta( $comment_id, 'experience_date', $experience_date );

				$client_location = $this->find_client_location();
				if ( ( isset( $client_location ) ) && ! empty( $client_location != '' ) ) {
					$client_location = wp_filter_nohtml_kses( $client_location );
				}
				add_comment_meta( $comment_id, 'client_location', $client_location );

				$attach_id_array = array();
				if ( $_FILES ) {
					$files = $_FILES['gallery'];
					foreach ( $files['name'] as $key => $value ) {
						if ( $files['name'][ $key ] ) {
							$file   = array(
								'name'     => $files['name'][ $key ],
								'type'     => $files['type'][ $key ],
								'tmp_name' => $files['tmp_name'][ $key ],
								'error'    => $files['error'][ $key ],
								'size'     => $files['size'][ $key ],
							);
							$_FILES = array( 'gallery' => $file );
							foreach ( $_FILES as $file => $array ) {
								$newupload         = $this->wte_tr_handle_attachment( $file, $pid );
								$attach_id_array[] = $newupload;
							}
						}
					}
					add_comment_meta( $comment_id, 'gallery_images', $attach_id_array );
				}
			}
		}
	}

endif;

$obj = new WTE_Trip_Reviews_Functions();
$obj->init();
