<?php
if ( ! class_exists( 'WTE_Trip_Reviews_Enqueue' ) ) :
	/**
	 * Comment Part: Comment Avatar
	 */
	class WTE_Trip_Reviews_Enqueue extends Wte_Trip_Review_Init {

		function comment_avatar() {
			?>
			<div class="comment-author">
			<?php
			$photo = get_comment_meta( get_comment_ID(), 'photo', true );

			if ( isset( $photo ) && $photo != '' ) {
				echo wp_get_attachment_image( $photo, array( '96', '96' ) );
			} else {
				echo get_avatar( get_comment_author_email( get_comment_ID() ), 96 );
			}
			?>
			</div>

			<?php
		}

		/**
		 * Comment Part: Comment Title
		 */
		function comment_title() {
			$commenttitle = get_comment_meta( get_comment_ID(), 'title', true );
			if ( $commenttitle ) {
				echo '<span class="comment-title">' . esc_attr( $commenttitle ) . '</span>';
			}
		}

		/**
		 * Comment Part: Comment Author
		 */
		function comment_meta_author() {
			$commenttitle = get_comment_meta( get_comment_ID(), 'title', true );
			if ( $commenttitle ) {
				printf( '<span class="url">%s</span>', get_comment_author() );
			}
		}

		/**
		 * Comment Part: Date of comment submission
		 */
		function comment_meta_date() {
			$commenttitle = get_comment_meta( get_comment_ID(), 'title', true );
			if ( $commenttitle ) {
				echo '<span class="comment-meta">' . get_comment_date() . '</span>';
			}
		}

		/**
		 * Comment Part: Comment Author
		 */
		function comment_meta_gallery() {
			$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings' );
			$hide_image_upload_field   = isset( $wp_travel_engine_settings['trip_reviews']['hide_image_upload_field'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_image_upload_field'] ) : '';
			if ( $hide_image_upload_field ) {
				remove_action( 'comment_meta_gallery', 'comment_meta_gallery' );
			} else {
				ob_start();
				$gallery_images = get_comment_meta( get_comment_ID(), 'gallery_images', true );
				if ( isset( $gallery_images ) && ! is_object( $gallery_images ) && ! empty( $gallery_images ) ) {
					?>
				<div class="trip-review-detail-gallery">
					<?php
					foreach ( $gallery_images as $keys => $id ) {
						$image_thumbnail = wp_get_attachment_image( $id, 'thumbnail' );
						$image_full      = wp_get_attachment_image_url( $id, 'large' );
						if ( ! empty( $image_thumbnail ) ) {
							?>
							<a class="trip-review-gallery-link" href="<?php echo esc_url( $image_full ); ?>" data-fancybox="review-gallery">
							<?php echo $image_thumbnail; ?>
							</a>
							<?php
						}
					}
					$output = ob_get_clean();
					$output = apply_filters( 'wte_filtered_comment_gallery_section', $output );
					echo $output;
					?>
				</div>
					<?php
				}
			}
		}

		/**
		 * Comment Part: Comment Rating
		 */
		function comment_rating() {
			$text = '';
			if ( $commentwte_trip_review_rating = get_comment_meta( get_comment_ID(), 'stars', true ) ) {
				$icon_type               = '';
				$icon_fill_color         = '#F39C12';
				$review_icon_type        = apply_filters( 'trip_rating_icon_type', $icon_type );
				$review_icon_fill_colors = apply_filters( 'trip_rating_icon_fill_color', $icon_fill_color );
				?>
				<div class="comment-indv-rating-wrap">
					<div class="comment-indvidual-rating trip-review-stars <?php echo ! empty( $review_icon_type ) ? 'svg-trip-adv' : 'trip-review-default'; ?>"
					data-icon-type='<?php echo $review_icon_type; ?>'"
					data-rating-value=" <?php echo $commentwte_trip_review_rating; ?>"
					data-rateyo-rated-fill="<?php echo $review_icon_fill_colors; ?>" data-rateyo-read-only="true">
					</div>
				</div>
				<?php
				echo $text;
			} else {
				echo $text;
			}
		}

		/**
		 * Comment Part: Current Reviewed Tour
		 */
		function comment_reviewed_tour() {
			$main_label                = __( 'Reviewed Tour', 'wte-trip-review' );
			$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
			$hide_reviewed_tour_field  = isset( $wp_travel_engine_settings['trip_reviews']['hide_reviewed_tour_field'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_reviewed_tour_field'] ) : '';
			if ( $hide_reviewed_tour_field ) {
				remove_action( 'comment_reviewed_tour', 'comment_reviewed_tour' );
			} else {
				ob_start();
				global $post;
				$comment_id         = get_comment_ID();
				$comment            = get_comment( $comment_id );
				$comment_post_title = get_the_title( $comment->comment_post_ID );
				$comment_post_id    = $comment->comment_post_ID;

				if ( isset( $post->ID ) && intval( $comment_post_id ) !== $post->ID ) {
					do_action( 'comment_related_post_wrap_open' );

					if ( isset( $wp_travel_engine_setting['review'] ) && isset( $wp_travel_engine_setting['review']['reviewed_tour_text'] ) && $wp_travel_engine_setting['review']['reviewed_tour_text'] != '' ) {
						$main_label = esc_attr( $wp_travel_engine_setting['review']['reviewed_tour_text'] );
					} else {
						$main_label = $main_label;
					}
					echo '<span>' . $main_label . '</span>';
					?>
					<a href="<?php echo esc_url( get_permalink( $comment_post_id ) ); ?>" title="<?php echo esc_attr( $comment_post_title ); ?>">
					<?php echo esc_html( $comment_post_title ); ?>
					</a>
					<?php
					do_action( 'comment_related_post_wrap_close' );
				}
				$output = ob_get_clean();
				$output = apply_filters( 'wte_filtered_comment_reviewed_tour_section', $output );
				echo $output;
			}
		}

		/**
		 * Comment Part:
		 * Convert text to X days ago
		 */
		function comment_days_ago() {
			?>
		<span>(<?php printf( _x( '%1$s ago', '%2$s = human-readable time difference', 'wte-trip-review' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ); ?>)</span>
			<?php
		}

		/**
		 * Comment Part:
		 * Comment Content
		 */
		function comment_content() {
			?>
			<div class="comment-content"><?php echo get_comment_text(); ?></div>
			<?php
		}

		/**
		 * Comment Part:
		 * Experiance Date
		 */
		function comment_experience_date() {
			$wp_travel_engine_settings  = get_option( 'wp_travel_engine_settings' );
			$hide_experience_date_field = isset( $wp_travel_engine_settings['trip_reviews']['hide_experience_date_field'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_experience_date_field'] ) : '';

			if ( $hide_experience_date_field ) {
				remove_action( 'comment_experience_date', 'comment_experience_date' );
			} else {
				ob_start();
				$experience_date = get_comment_meta( get_comment_ID(), 'experience_date', true );
				if ( ! empty( $experience_date ) ) {
					$converted_date = date_i18n( 'F, Y', strtotime( $experience_date ) )
					?>
				<div class="comment-experience-date">
					<span class="experience-date-text"><?php _e( 'Date of Experience', 'wte-trip-review' ); ?>:</span>
					<?php echo $converted_date; ?></div>
					<?php
				}
				$output = ob_get_clean();
				$output = apply_filters( 'wte_filtered_comment_experience_date_section', $output );
				echo $output;
			}
		}

		/**
		 * Comment Part:
		 * Client Location
		 */
		function comment_client_location() {
			$wp_travel_engine_settings  = get_option( 'wp_travel_engine_settings' );
			$hide_client_location_field = isset( $wp_travel_engine_settings['trip_reviews']['hide_client_location_field'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_client_location_field'] ) : '';
			if ( $hide_client_location_field ) {
				remove_action( 'comment_client_location', 'comment_client_location' );
			} else {
				$client_location = get_comment_meta( get_comment_ID(), 'client_location', true );
				if ( ! empty( $client_location ) ) {
					?>
				<div class="comment-client-location">
					<span class="client-location-text">
					<?php _e( 'from', 'wte-trip-review' ); ?>
					</span>
					<?php echo $client_location; ?>
				</div>
					<?php
				}
			}
		}

	}
endif;
$obj = new WTE_Trip_Reviews_Enqueue();
$obj->init();
