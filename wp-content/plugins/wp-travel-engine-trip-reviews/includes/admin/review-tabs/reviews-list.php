<?php
/**
 * Review Lists.
 */
?>
<div class="trip-review" id="trip-reviews">
	<div class="wpte-field wpte-trip-code wpte-floated">
		<div class="wpte-info-block">
			<b><?php _e( 'Note', 'wte-trip-review' ); ?> :</b>
			<p><?php _e( 'You can use the shortcode <b>[Wte_Trip_Review_List]</b> to display Trip Reviews for individual trip in tabs or trip content.', 'wte-trip-review' ); ?></p>
			<p><?php _e( 'Use the shortcode <b>[Wte_All_Trip_Review_List]</b> to display Trip Reviews for all trips in trip tabs or pages.', 'wte-trip-review' ); ?></p>
			<p><?php _e( 'Trip reviews can also be managed from the default comment edit screens in admin dashboard.', 'wte-trip-review' ); ?></p>
			</div>
	</div>

	<?php
	$args     = array(
		'post_type' => 'trip',
		'order'     => 'desc',
		'orderby'   => 'comment_date',
	);
	$comments = get_comments( $args );
	if ( ! empty( $comments ) ) {
		echo '<div class="trip-review-list">';
		foreach ( $comments as $comment ) :
			$rate              = get_comment_meta( $comment->comment_ID, 'stars', true );
			$comment_post_link = get_the_title( $comment->comment_post_ID );
			if ( isset( $rate ) && $rate != '' ) {
				?>
				<li>
					<?php edit_post_link( $comment_post_link, '<div class="post-link">', '</div>', $comment->comment_post_ID ); ?>
					<div class="review-wrap">
						<div class="comment-author">
							<?php
							$photo = get_comment_meta( $comment->comment_ID, 'photo', true );
							if ( isset( $photo ) && $photo != '' ) {
								echo wp_get_attachment_image( $photo, 'thumbnail' );
							} else {
								echo get_avatar( $comment->comment_author_email, 96 );
							}
							?>
						</div>
						<div class="trip-comment-content">
							<?php
							$commenttitle = get_comment_meta( $comment->comment_ID, 'title', true );
							echo '<span class="comment-title">';
							?>
							<a href="<?php echo esc_url( "comment.php?action=editcomment&c=$comment->comment_ID" ); ?>" title="<?php echo $commenttitle; ?>">
								<?php echo $commenttitle; ?></a>
							<?php
							echo '</span>';
							$status = $comment->comment_approved;
							if ( $status == 1 ) {
								echo '<span class="approved">' . '(Approved)' . '</span>';
							} else {
								echo '<span class="not-approved">' . '(Not Approved)' . '</span>';
							}
							?>
							<div class="comment-rating">
								<?php
								if ( $commenttitle ) {
									$commenttitle = '<div class="comment-meta">';
									printf( '<span class="url">' . __( 'By %s', 'wte-trip-review' ) . '</span>', $comment->comment_author );
									echo '<span class="comment-meta">' . $comment->comment_date . '</span></div>';
								}
								$text = '';
								if ( $commentwte_trip_review_rating = get_comment_meta( $comment->comment_ID, 'stars', true ) ) {
									$offstar                       = 5 - $commentwte_trip_review_rating;
									$onstar                        = $commentwte_trip_review_rating;
									$commentwte_trip_review_rating = '<p class="comment-wte-trip-review-rating"><img src="' . WTE_TRIP_REVIEW_FILE_URL . '/images/' . $onstar . 'star.png"/>';

									for ( $i = 0; $i < $offstar; $i++ ) {
										$commentwte_trip_review_rating .= '<img src="' . WTE_TRIP_REVIEW_FILE_URL . '/images/star_off.png"/>';
									}

									$text = $text . $commentwte_trip_review_rating;
									echo $text;
								} else {
									echo $text;
								}
								?>
								<div class="comment-content"><?php echo $comment->comment_content; ?></div>
								<?php
								$the_comment_status = wp_get_comment_status( $comment );

								$out = '';

								$del_nonce     = esc_html( '_wpnonce=' . wp_create_nonce( "delete-comment_$comment->comment_ID" ) );
								$approve_nonce = esc_html( '_wpnonce=' . wp_create_nonce( "approve-comment_$comment->comment_ID" ) );

								$url = "comment.php?c=$comment->comment_ID";

								$approve_url   = esc_url( $url . "&action=approvecomment&$approve_nonce" );
								$unapprove_url = esc_url( $url . "&action=unapprovecomment&$approve_nonce" );
								$spam_url      = esc_url( $url . "&action=spamcomment&$del_nonce" );
								$unspam_url    = esc_url( $url . "&action=unspamcomment&$del_nonce" );
								$trash_url     = esc_url( $url . "&action=trashcomment&$del_nonce" );
								$untrash_url   = esc_url( $url . "&action=untrashcomment&$del_nonce" );
								$delete_url    = esc_url( $url . "&action=deletecomment&$del_nonce" );

								// Preorder it: Approve | Reply | Quick Edit | Edit | Spam | Trash.
								$actions = array(
									'approve'   => '',
									'unapprove' => '',
									'reply'     => '',
									'quickedit' => '',
									'edit'      => '',
									'spam'      => '',
									'unspam'    => '',
									'trash'     => '',
									'untrash'   => '',
									'delete'    => '',
								);
								$test    = '';
								if ( 'approved' === $the_comment_status ) {
									$actions['unapprove'] = "<a href='$unapprove_url' data-wp-lists='delete:the-comment-list:comment-$comment->comment_ID:e7e7d3:action=dim-comment&amp;new=unapproved' class='vim-u vim-destructive' aria-label='" . esc_attr__( 'Unapprove this comment', 'wte-trip-review' ) . "'>" . __( 'Unapprove', 'wte-trip-review' ) . '</a>';
									$test                .= $actions['unapprove'];
								} elseif ( 'unapproved' === $the_comment_status ) {
									$actions['approve'] = "<a href='$approve_url' data-wp-lists='delete:the-comment-list:comment-$comment->comment_ID:e7e7d3:action=dim-comment&amp;new=approved' class='vim-a vim-destructive' aria-label='" . esc_attr__( 'Approve this comment', 'wte-trip-review' ) . "'>" . __( 'Approve', 'wte-trip-review' ) . '</a>';
									$test              .= $actions['approve'];
								}
								if ( 'spam' !== $the_comment_status ) {
									$actions['spam'] = "<a href='$spam_url' data-wp-lists='delete:the-comment-list:comment-$comment->comment_ID::spam=1' class='vim-s vim-destructive' aria-label='" . esc_attr__( 'Mark this comment as spam', 'wte-trip-review' ) . "'>" . /* translators: mark as spam link */ _x( 'Spam', 'verb', 'wte-trip-review' ) . '</a>';
									$test           .= $actions['spam'];
								} elseif ( 'spam' === $the_comment_status ) {
									$actions['unspam'] = "<a href='$unspam_url' data-wp-lists='delete:the-comment-list:comment-$comment->comment_ID:66cc66:unspam=1' class='vim-z vim-destructive' aria-label='" . esc_attr__( 'Restore this comment from the spam', 'wte-trip-review' ) . "'>" . _x( 'Not Spam', 'comment', 'wte-trip-review' ) . '</a>';
									$test             .= $actions['unspam'];
								}

								if ( 'trash' === $the_comment_status ) {
									$actions['untrash'] = "<a href='$untrash_url' data-wp-lists='delete:the-comment-list:comment-$comment->comment_ID:66cc66:untrash=1' class='vim-z vim-destructive' aria-label='" . esc_attr__( 'Restore this comment from the Trash', 'wte-trip-review' ) . "'>" . __( 'Restore', 'wte-trip-review' ) . '</a>';
									$test              .= $actions['untrash'];
								}

								if ( 'spam' === $the_comment_status || 'trash' === $the_comment_status || ! EMPTY_TRASH_DAYS ) {
									$actions['delete'] = "<a href='$delete_url' data-wp-lists='delete:the-comment-list:comment-$comment->comment_ID::delete=1' class='delete vim-d vim-destructive' aria-label='" . esc_attr__( 'Delete this comment permanently', 'wte-trip-review' ) . "'>" . __( 'Delete Permanently', 'wte-trip-review' ) . '</a>';
									$test             .= $actions['delete'];
								} else {
									$actions['trash'] = "<a href='$trash_url' data-wp-lists='delete:the-comment-list:comment-$comment->comment_ID::trash=1' class='delete vim-d vim-destructive' aria-label='" . esc_attr__( 'Move this comment to the Trash', 'wte-trip-review' ) . "'>" . _x( 'Trash', 'verb', 'wte-trip-review' ) . '</a>';
									$test            .= $actions['untrash'];
								}

								if ( 'spam' !== $the_comment_status && 'trash' !== $the_comment_status ) {
									$actions['edit'] = "<a href='comment.php?action=editcomment&amp;c={$comment->comment_ID}' aria-label='" . esc_attr__( 'Edit this comment', 'wte-trip-review' ) . "'>" . __( 'Edit', 'wte-trip-review' ) . '</a>';
									$test           .= $actions['edit'];

									$format = '<a data-comment-id="%d" data-post-id="%d" data-action="%s" class="%s" aria-label="%s" href="#">%s</a>';

									$actions['quickedit'] = sprintf( $format, $comment->comment_ID, $comment->comment_post_ID, 'edit', 'vim-q comment-inline', esc_attr__( 'Quick edit this comment inline', 'wte-trip-review' ), __( 'Quick&nbsp;Edit', 'wte-trip-review' ) );

									$actions['reply'] = sprintf( $format, $comment->comment_ID, $comment->comment_post_ID, 'replyto', 'vim-r comment-inline', esc_attr__( 'Reply to this comment', 'wte-trip-review' ), __( 'Reply', 'wte-trip-review' ) );
								}

								/** This filter is documented in wp-admin/includes/dashboard.php */
								$actions = apply_filters( 'comment_row_actions', array_filter( $actions ), $comment );

								$i    = 0;
								$out .= '<div class="row-actions">';
								foreach ( $actions as $action => $link ) {
									++$i;
									( ( ( 'approve' === $action || 'unapprove' === $action ) && 2 === $i ) || 1 === $i ) ? $sep = '' : $sep = ' | ';

									// Reply and quickedit need a hide-if-no-js span when not added with ajax
									if ( ( 'reply' === $action || 'quickedit' === $action ) && ! wp_doing_ajax() ) {
										$action .= ' hide-if-no-js';
									} elseif ( ( $action === 'untrash' && $the_comment_status === 'trash' ) || ( $action === 'unspam' && $the_comment_status === 'spam' ) ) {
										if ( '1' == get_comment_meta( $comment->comment_ID, '_wp_trash_meta_status', true ) ) {
											$action .= ' approve';
										} else {
											$action .= ' unapprove';
										}
									}
									$out .= "<span class='$action'>$sep$link</span>";
								}
								$out .= '</div>';
								echo '<span class="actions">' . $test . '</span>';
								echo $out;
								?>
							</div>
						</div>
				</li>
				<?php
			}
		endforeach;
		echo '</div>';
	}
	?>
</div>
