<?php
/**
 * Comment thread
 */

global $post;
$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );

$comment_ID = get_comment_ID();
?>

<li class="wte-review-comment-id" id="wte-review-comment-<?php echo $comment_ID; ?>" data-id="<?php echo $comment_ID; ?>">
	<div class="comment-author">
		<?php
		// Comment Avatar
		$photo = get_comment_meta( $comment_ID, 'photo', true );
		if ( isset( $photo ) && $photo != '' ) {
			echo wp_get_attachment_image( $photo, array( '96', '96' ) );
		} else {
			echo get_avatar( get_comment_author_email( $comment_ID ), 96 );
		}
		?>
	</div>
	<div class="trip-comment-content">
		<?php
		// Comment Title
		$commenttitle = get_comment_meta( $comment_ID, 'title', true );
		if ( $commenttitle ) {
			?>
			<span class="comment-title"> <?php echo esc_attr( $commenttitle ); ?></span>
			<?php
		}
		?>

		<div class="comment-rating">
			<?php
			// Comment Author
			$commenttitle = get_comment_meta( $comment_ID, 'title', true );

			wptravelengine_reviews_the_user_star_rating( $comment_ID );

			if ( $commenttitle ) {
				printf( '<span class="url">' . __( 'By %s', 'wte-trip-review' ) . '</span>', get_comment_author() );
			}
			?>

			<?php
			// Client Location
			$hide_client_location_field = isset( $wp_travel_engine_settings['trip_reviews']['hide_client_location_field'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_client_location_field'] ) : '';
			if ( $hide_client_location_field ) {
				// hide
			} else {
				$client_location = get_comment_meta( $comment_ID, 'client_location', true );
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
			?>

			<?php
			// Comment Date
			$commenttitle = get_comment_meta( $comment_ID, 'title', true );

			if ( $commenttitle ) {
				echo '<span class="comment-meta">' . get_comment_date() . '</span>';
			}

			// Reviewed Tour
			$main_label               = __( 'Reviewed Tour', 'wte-trip-review' );
			$hide_reviewed_tour_field = isset( $wp_travel_engine_settings['trip_reviews']['hide_reviewed_tour_field'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_reviewed_tour_field'] ) : '';
			if ( $hide_reviewed_tour_field ) {
				// hide
			} else {
				$commentid          = $comment_ID;
				$comment            = get_comment( $commentid );
				$comment_post_title = get_the_title( $comment->comment_post_ID );
				$comment_post_id    = $comment->comment_post_ID;
				if ( isset( $post->ID ) && intval( $comment_post_id ) !== $post->ID ) {
					?>
					<div class="comment-related-post">
						<?php
						if ( isset( $wp_travel_engine_setting['review'] ) && isset( $wp_travel_engine_setting['review']['reviewed_tour_text'] ) && $wp_travel_engine_setting['review']['reviewed_tour_text'] != '' ) {
							$main_label = esc_attr( $wp_travel_engine_setting['review']['reviewed_tour_text'] );
						} else {
							$main_label = $main_label;
						}
						echo '<span>' . $main_label . '</span>';
						?>

						<a href="<?php echo esc_url( get_permalink( $comment_post_id ) ); ?>" title="<?php echo esc_attr( $comment_post_title ); ?>"
						><?php echo esc_html( $comment_post_title ); ?></a>
					</div>
					<?php
				}
			}
			?>

			<?php
				// Review Comment
			?>
			<div class="comment-content">
				<?php echo get_comment_text(); ?>
			</div>

			<?php
			// Image Gallery
			$hide_image_upload_field = isset( $wp_travel_engine_settings['trip_reviews']['hide_image_upload_field'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_image_upload_field'] ) : '';
			if ( $hide_image_upload_field ) {
				// hide
			} else {
				$comment_id     = ( isset( $comment ) && is_object( $comment ) ) ? $comment->comment_ID : $comment_ID;
				$gallery_images = get_comment_meta( $comment_id, 'gallery_images', true );
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
							?>
						</div>
					<?php
				}
			}
			?>

			<?php
			// Experience Date
			$hide_experience_date_field = isset( $wp_travel_engine_settings['trip_reviews']['hide_experience_date_field'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_experience_date_field'] ) : '';
			if ( $hide_experience_date_field ) {
				// hide
			} else {
				$experience_date = get_comment_meta( $comment_ID, 'experience_date', true );
				if ( ! empty( $experience_date ) ) {
					$converted_date = date_i18n( 'F, Y', strtotime( $experience_date ) )
					?>
					<div class="comment-experience-date">
						<span class="experience-date-text"><?php _e( 'Date of Experience', 'wte-trip-review' ); ?>:</span>
						<?php echo $converted_date; ?></div>
					<?php
				}
			}
			?>
		</div>
	</div>
</li>
