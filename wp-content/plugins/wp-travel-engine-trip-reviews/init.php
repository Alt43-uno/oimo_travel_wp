<?php

class Wte_Trip_Review_Init extends WTE_Trip_Review_Library {

	function init() {

		include WTE_TRIP_REVIEW_BASE_PATH . '/includes/class-enqueue.php';
		include WTE_TRIP_REVIEW_BASE_PATH . '/includes/class-wte-geoplugin.php';

		if ( is_admin() ) {
			include WTE_TRIP_REVIEW_BASE_PATH . '/includes/class-wte-trip-reviews-backend-menu.php';
			// include WTE_TRIP_REVIEW_BASE_PATH . '/updater/wte-trip-review-updater.php';
		}
		if ( get_post_type() == 'trip' ) {
			add_action( 'wp_footer', array( $this, 'wte_trip_review_template' ) );
		}

		include WTE_TRIP_REVIEW_BASE_PATH . '/includes/class-wte-trip-reviews-functions.php';
		include WTE_TRIP_REVIEW_BASE_PATH . '/includes/class-shortcode.php';
		// New Action hook added
		add_action( 'wte_review_wrap_open', array( $this, 'review_wrap_open' ) );
		add_action( 'wte_review_wrap_close', array( $this, 'review_wrap_close' ) );
		add_action( 'wte_average_review_wrap_open', array( $this, 'average_review_wrap_open' ) );
		add_action( 'wte_average_review_wrap_close', array( $this, 'average_review_wrap_close' ) );
		add_action( 'wte_list_comment_wrap_open', array( $this, 'list_comment_ol_open' ) );
		add_action( 'wte_list_comment_wrap_close', array( $this, 'list_comment_ol_close' ) );
		add_action( 'wte_list_all_comments_wrap_open', array( $this, 'list_all_comments_ul_open' ) );
		add_action( 'wte_list_all_comments_wrap_close', array( $this, 'list_all_comments_ul_close' ) );
		add_action( 'wte_list_reviews', array( $this, 'wte_list_reviews' ) );

		/** (Specific Trip Reviews) action hooks  */
		add_action( 'wte_trip_review_header', array( $this, 'show_trip_review_header' ) );
		add_action( 'wte_trip_review_schema_json', array( $this, 'show_trip_review_schema_json' ) );
		add_action( 'wte_trip_average_rating', array( $this, 'show_trip_average_rating' ) );
		add_action( 'wte_trip_average_rating_from_wte', array( $this, 'show_trip_average_rating_from_wte' ) );
		add_action( 'wte_trip_overall_review', array( $this, 'show_trip_overall_average_rating' ) );
		add_action( 'wte_trip_average_rating_star', array( $this, 'trip_average_rating_star' ) );
		add_action( 'wte_trip_average_rating_based_on_text', array( $this, 'wte_trip_average_rating_based_on_text' ) );
		add_action( 'wte_average_review_range_emoticons', array( $this, 'average_review_range_emoticons' ) );
		add_action( 'wte_average_review_range_label', array( $this, 'average_review_range_label' ) );

		/** (Collective Company Reviews) action hooks */
		add_action( 'wte_company_review_header', array( $this, 'show_company_review_header' ) );
		add_action( 'wte_company_review_schema_json', array( $this, 'show_company_schema_markup_json' ) );
		add_action( 'wte_company_overall_review', array( $this, 'show_company_overall_average_rating' ) );
		add_action( 'wte_company_average_rating', array( $this, 'show_company_average_rating_count' ) );
		add_action( 'wte_company_average_rating_star', array( $this, 'company_average_rating_star' ) );
		add_action( 'wte_company_average_review_range_label', array( $this, 'company_average_review_range_label' ) );
		add_action( 'wte_company_average_rating_based_on_text', array( $this, 'company_average_rating_based_on_text' ) );
		add_action( 'wte_company_average_review_range_emoticons', array( $this, 'company_average_review_range_emoticons' ) );
		add_action( 'wte_list_all_reviews', array( $this, 'lists_all_reviews' ) );
		/*
		 * Comment callback sections each
		 */
		add_action( 'comment_related_post_wrap_open', array( $this, 'comment_related_post_wrap_open' ) );
		add_action( 'comment_related_post_wrap_close', array( $this, 'comment_related_post_wrap_close' ) );
		add_action( 'comment_avatar', array( $this, 'comment_avatar' ) );
		add_action( 'comment_title', array( $this, 'comment_title' ) );
		add_action( 'comment_meta_author', array( $this, 'comment_meta_author' ) );
		add_action( 'comment_meta_date', array( $this, 'comment_meta_date' ) );
		add_action( 'comment_rating', array( $this, 'comment_rating' ) );
		add_action( 'comment_reviewed_tour', array( $this, 'comment_reviewed_tour' ) );
		add_action( 'comment_content', array( $this, 'comment_content' ) );
		add_action( 'comment_days_ago', array( $this, 'comment_days_ago' ) );
		add_action( 'comment_experience_date', array( $this, 'comment_experience_date' ) );
		add_action( 'comment_client_location', array( $this, 'comment_client_location' ) );
		add_action( 'comment_meta_gallery', array( $this, 'comment_meta_gallery' ) );

		/**
		 * Action hook to call form
		 */
		add_action( 'wte_trip_rating_form', array( $this, 'show_trip_rating_form' ) );

		add_action( 'wp_ajax_wte_trip_review_comments_loadmore', array( $this, 'wte_trip_review_comments_loadmores' ) );
		add_action( 'wp_ajax_nopriv_wte_trip_review_comments_loadmore', array( $this, 'wte_trip_review_comments_loadmores' ) );
		add_action( 'wp_ajax_wte_company_review_comments_loadmore', array( $this, 'wte_company_review_comments_loadmores' ) );
		add_action( 'wp_ajax_nopriv_wte_company_review_comments_loadmore', array( $this, 'wte_company_review_comments_loadmores' ) );

		add_action( 'comment_form_before', array( $this, 'wte_reivew_form_div_before_comment_form' ) );
		add_action( 'comment_form_submit_button', array( $this, 'wte_text_after_submit_button' ), 10, 2 );
		add_action( 'restrict_manage_comments', array( $this, 'wte_comments_sort_filter' ) );

		add_filter( 'wp_insert_post_data', array( $this, 'wte_comments_on' ) );
		add_filter( 'get_default_comment_status', array( $this, 'wpdocs_open_comments_for_myposttype' ), 10, 3 );
		add_filter( 'comments_open', array( $this, 'wte_trip_comments_open' ), 10, 2 );

	}

	/**
	 * @since 2.1.1 as a method
	 */
	public function wte_trip_comments_open( $open, $post_id ) {
		$post = get_post( $post_id );

		if ( 'trip' == $post->post_type ) {
			$open = true;
		}

		return $open;
	}

	/**
	 *
	 * @since 2.1.1 as a method
	 */
	public function wpdocs_open_comments_for_myposttype( $status, $post_type, $comment_type ) {
		if ( 'trip' !== $post_type ) {
			return $status;
		}
		// You could be more specific here for different comment types if desired
		return 'open';
	}

	/**
	 * Moved from main file to here.
	 * Enables comment status to open for the post_type trip.
	 *
	 * @since 2.1.1 as a method
	 */
	public function wte_comments_on( $data ) {
		if ( $data['post_type'] == 'trip' ) {
			$data['comment_status'] = 'open';
		}
		return $data;
	}

	/**
	 * Create custom comments filter for post types
	 **/
	function wte_comments_sort_filter() {
		$args       = array(
			'public'   => true,
			'_builtin' => false,
		);
		$post_types = get_post_types( $args, 'objects' );

		if ( $post_types ) {
			echo '<select name="post_type" id="filter-by-post-type">';
			echo '<option value="">' . __( 'All post types', 'wte-trip-review' ) . '</option>';

			foreach ( $post_types as $post_type ) {
				$label = $post_type->label;
				$name  = $post_type->name;
				if ( $name == 'trip' ) {
					?>
					<option value="<?php echo $name; ?>" <?php echo isset( $_GET['post_type'] ) && $_GET['post_type'] == $name ? 'selected="selected"' : ''; ?>><?php echo $label . ' ' . __( 'Reviews', 'wte-trip-review' ); ?></option>
					<?php
				}
			}
			echo '</select>';
		}

	}

	/**
	 * Add message field after submit button for comment form
	 */
	function wte_text_after_submit_button( $submit_button, $args ) {
		$submit_after = sprintf(
			'<span class="submit-after-comment-wrap" style="display:none;"><span class="after-submit-comment-loader"><img width="30" src="' . WTE_TRIP_REVIEW_FILE_URL . '/images/loader.gif"/></span><span class="submit-comment-note">%s</span></span>',
			__( 'Please wait while comment is being processed.', 'wte-trip-review' )
		);
		return $submit_button . $submit_after;
	}

	/**
	 * Function: Pull Trip Specific Based Text
	 * Output (X stars - Based on X reviews)
	 *
	 * @return void
	 */
	function wte_trip_average_rating_based_on_text() {
		global $post;
		ob_start();
		$comment_datas = $this->pull_comment_data( get_the_ID() );
		$data = wptravelengine_reviews_get_trip_reviews( $post->ID );
		if ( ! empty( $comment_datas ) ) {
			?>
				<div class="aggregate-rating">
					<span class="stars">
						<span class="rating-star"><?php echo number_format( (float) $data['average'], 1 ); ?></span>
						<?php wptravelengine_reviews_star_markup( (float) $data['average'] ); ?>
					</span>
					<span class="review-based-on-wrap"> - <?php _e( 'Based on ', 'wte-trip-review' ); ?>  </span> <span><?php printf( _nx( '%s travel review', '%s travel reviews', absint( $comment_datas['i'] ), 'review count', 'wte-trip-review' ), number_format_i18n( $comment_datas['i'] ) ); ?>
					</span>
				</div>
			<?php
		}

		$output = ob_get_clean();
		$output = apply_filters( 'wte_filtered_trip_average_rating_based_on_text', $output );
		echo $output;
	}

	function company_average_rating_based_on_text() {
		ob_start();
		$comment_datas = $this->pull_all_comments_data();
		if ( ! empty( $comment_datas ) ) {
			?>
			<div class="aggregate-rating">
				<span class="stars">
					<span class="rating-star"><?php echo esc_attr( $comment_datas['aggregate'] ); ?></span>
					<?php _e( 'stars', 'wte-trip-review' ); ?>
				</span><span class="review-based-on-wrap"> - <?php _e( 'Based on ', 'wte-trip-review' ); ?></span> <span><?php echo absint( $comment_datas['i'] ); ?></span> <?php echo __( _nx( 'review', 'reviews', absint( $comment_datas['i'] ), 'review count', 'wte-trip-review' ) ); ?>
			</div>
			<?php
			$output = ob_get_clean();
			$output = apply_filters( 'wte_filtered_company_average_rating_based_on_text', $output );
			echo $output;
		}
	}



	/**
	 *  Function: to alter review icon
	 * default: Star Icon
	 * for Travel Muni: Tripadvisor Icon
	 *
	 * @return void
	 */
	function trip_average_rating_star() {
		global $post;
		$review_data = wptravelengine_reviews_get_trip_reviews( $post->ID );

		ob_start();

		if ( ! empty( $comment_datas ) ) {
			wptravelengine_reviews_star_markup( $review_data['average'] );
		}

		echo apply_filters( 'wte_filtered_trip_average_rating_star', ob_get_clean(), $review_data );
	}

	/**
	 * Function: top alter review icon
	 * Default: star
	 * for Travel Muni: Tripadvisor Icon - <svg xmlns="//www.w3.org/2000/svg" viewBox="0 0 800 600"><circle cx="400" cy="300" r="250" stroke-width="20" /></svg>
	 *
	 * @return void
	 */
	function company_average_rating_star() {
		ob_start();
		$comment_datas           = $this->pull_all_comments_data();
		$icon_type               = '';
		$icon_fill_color         = '#F39C12';
		$review_icon_type        = apply_filters( 'trip_rating_icon_type', $icon_type );
		$review_icon_fill_colors = apply_filters( 'trip_rating_icon_fill_color', $icon_fill_color );
		?>
		<div class="agg-rating trip-review-stars <?php echo ! empty( $review_icon_type ) ? 'svg-trip-adv' : 'trip-review-default'; ?>"
			data-icon-type='<?php echo $review_icon_type; ?>' data-rating-value="<?php echo $comment_datas['aggregate']; ?>"
			data-rateyo-rated-fill="<?php echo $review_icon_fill_colors; ?>" data-rateyo-read-only="true">
		</div>
		<?php
		$output = ob_get_clean();
		$output = apply_filters( 'wte_filtered_company_average_rating_star', $output );
		echo $output;
	}

	/**
	 * Action hook: Main Comment Wrap
	 *
	 * @return parent-wrap
	 */
	function review_wrap_open() {
		echo '<div class="review-wrap">';
	}

	function review_wrap_close() {
		echo '</div>';
	}


	/**
	 * Action hook: Comment list wrap
	 * callable action hook: wte_list_comment_wrap_open
	 * called Function: wte_list_reviews, show_trip_rating_shortcode
	 */
	function list_comment_ol_open() {
		echo '<ol class="comment-list">';
	}

	function list_comment_ol_close() {
		echo '</ol>';
	}

	function list_all_comments_ul_open() {
		echo '<ul class="comment-list">';
	}

	function list_all_comments_ul_close() {
		echo '</ul>';
	}

	function average_review_wrap_open() {
		echo '<div class="average-rating">';
	}

	function average_review_wrap_close() {
		echo '</div>';
	}

	/**
	 * Action hook: Comment Related Post Wrap
	 * Have been called in Archive Comment
	 * callable action hook: comment_related_post_wrap_open
	 * called function: rw_archive_comment_callback
	 *
	 * @return parent-wrap
	 */
	function comment_related_post_wrap_open() {
		echo '<div class="comment-related-post">';
	}

	function comment_related_post_wrap_close() {
		echo '</div>';
	}

	function wte_list_reviews() {
		global $post;
		$limit    = get_option( 'comments_per_page' );
		$comments = get_comments(
			array(
				'post_id'     => $post->ID,
				'status'      => 'approve',
				'post_status' => 'publish',
				'order'       => 'desc',
				'orderby'     => 'comment_date',
			)
		);
		$pages    = ceil( count( $comments ) / $limit );
		$cpage    = get_query_var( 'cpage' ) ? get_query_var( 'cpage' ) : 1;
		if ( isset( $comments ) && $comments != '' && sizeof( $comments ) > 0 ) {

			do_action( 'wte_list_comment_wrap_open' );

			wp_list_comments(
				array(
					'callback' => array( $this, 'rw_archive_comment_callback' ),
					'type'     => 'comment',
				),
				$comments
			);

			if ( get_option( 'page_comments' ) ) {
				paginate_comments_links(
					array(
						'base'    => add_query_arg( 'cpage', '%#%' ),
						'total'   => $pages,
						'current' => $cpage,
						'echo'    => false,
					)
				);
			}

			if ( $pages > 1 ) {
				echo '<div class="wte-tr-readmore-wrap"><a href="#"
                class="wtetr_comment_loadmore"
                data-offset="' . $limit . '"
                data-parent-id="' . $post->ID . '"
                data-current-page="' . $cpage . '">' . __( 'Read More Reviews', 'wte-trip-review' ) . '</a></div>';
			}

			do_action( 'wte_list_comment_wrap_close' );
		}
	}

	/*
	 * Ajax callback function
	 * Load more - for the comment part
	 */

	function wte_trip_review_comments_loadmores() {
		global $post;
		$limit   = get_option( 'comments_per_page' );
		$post_id = intval( sanitize_text_field( $_POST['post_id'] ) );
		$cpage   = intval( sanitize_text_field( $_POST['cpage'] ) );
		$offset  = intval( sanitize_text_field( $_POST['offset'] ) );

		$cpage = $cpage + 1;
		$post  = get_post( $post_id );
		setup_postdata( $post );
		$comments = get_comments(
			array(
				'post_id'     => $post_id,
				'status'      => 'approve',
				'post_status' => 'publish',
				'order'       => 'desc',
				'orderby'     => 'comment_date',
			)
		);
		$pages    = ceil( count( $comments ) / $limit );
		if ( isset( $cpage ) && ! empty( $cpage ) && $cpage <= $pages ) {
			wp_list_comments(
				array(
					'avatar_size' => 100,
					'page'        => $cpage, // current comment page
					'per_page'    => get_option( 'comments_per_page' ),
					'short_ping'  => true,
					'callback'    => array( $this, 'rw_archive_comment_callback' ),
					'type'        => 'comment',
				)
			);

			if ( isset( $cpage ) && ! empty( $cpage ) && $cpage < $pages ) {
				echo '<div class="wte-tr-readmore-wrap"><a href="#" class="wtetr_comment_loadmore"
                data-offset="' . $offset . '"
                data-parent-id="' . $post_id . '"
                data-current-page="' . $cpage . '">' . __( 'Read More Reviews', 'wte-trip-review' ) . '</a></div>';
			} else {
				wp_reset_postdata();
			}
		}
		die;
	}

	/** List All Reviews
	 * Overall Company Review
	 */
	function lists_all_reviews( $atts ) {
		global $post;
		$limit    = get_option( 'comments_per_page' );
		$number   = isset( $atts['number'] ) && ! empty( $atts['number'] ) ? sanitize_text_field( $atts['number'] ) : '';
		$comments = get_comments(
			array(
				'post_type'   => 'trip',
				'status'      => 'approve',
				'post_status' => 'publish',
				'order'       => 'desc',
				'orderby'     => 'comment_date',
				'number'      => $number,

			)
		);
		$pages = ceil( count( $comments ) / $limit );
		$cpage = get_query_var( 'cpage' ) ? get_query_var( 'cpage' ) : 1;

		if ( isset( $comments ) && $comments != '' && sizeof( $comments ) > 0 ) {
			do_action( 'wte_list_all_comments_wrap_open' );
			wp_list_comments(
				array(
					'avatar_size' => 100,
					'page'        => $cpage, // current comment page
					'per_page'    => get_option( 'comments_per_page' ),
					'short_ping'  => true,
					'callback'    => array( $this, 'rw_archive_comment_callback' ),
					'type'        => 'comment',
				),
				$comments
			);

			paginate_comments_links(
				array(
					'base'    => add_query_arg( 'cpage', '%#%' ),
					'total'   => $pages,
					'current' => $cpage,
					'echo'    => false,
				)
			);
			$lpage_class = ( ( $pages - 1 ) == $cpage ) ? 'review-last-page' : '';
			if ( $pages > 1 ) {
				echo '<div class="wte-tr-readmore-wrap"><a href="#"
                class="wtetr_company_comment_loadmore ' . $lpage_class . '"
                data-offset="' . $limit . '"
                data-parent-id="trip"
                data-current-page="' . $cpage . '">' . __( 'Read More Reviews', 'wte-trip-review' ) . '</a><div class="wte-review-empty-div" style="display:none;"></div></div>';
			}
			do_action( 'wte_list_all_comments_wrap_close' );
		}
	}

	/*
	 * Ajax callback function
	 * Load more - for the comment part
	 */

	function wte_company_review_comments_loadmores() {
		global $post;
		$limit = get_option( 'comments_per_page' );
		$cpage = intval( sanitize_text_field( $_POST['cpage'] ) );

		$offset   = intval( sanitize_text_field( $_POST['offset'] ) );
		$cpage    = $cpage + 1;
		$comments = get_comments(
			array(
				'post_type'   => 'trip',
				'status'      => 'approve',
				'post_status' => 'publish',
				'order'       => 'desc',
				'orderby'     => 'comment_date',
			)
		);

		$pages = ceil( count( $comments ) / $limit );
		if ( isset( $cpage ) && ! empty( $cpage ) && $cpage <= $pages ) {
			wp_list_comments(
				array(
					'avatar_size' => 100,
					'page'        => $cpage, // current comment page
					'per_page'    => get_option( 'comments_per_page' ),
					'short_ping'  => true,
					'callback'    => array( $this, 'rw_archive_comment_callback' ),
					'type'        => 'comment',
				),
				$comments
			);
			$lpage_class = ( ( $pages - 1 ) == $cpage ) ? 'review-last-page' : '';
			if ( isset( $cpage ) && ! empty( $cpage ) && $cpage < $pages ) {
				echo '<div class="wte-tr-readmore-wrap">
                <a href="#" class="wtetr_company_comment_loadmore ' . $lpage_class . '"
                data-offset="' . $offset . '"
                data-current-page="' . $cpage . '">' . __( 'Read More Reviews', 'wte-trip-review' ) . '</a><div class="wte-review-empty-div" style="display:none;"></div></div>';
			} else {
				// wp_reset_postdata();
			}
		}
		die;
	}

	/**
	 * Action Hook: Emoticon Icon Display before review star
	 *
	 * @return void
	 */
	public function average_review_range_emoticons() {
		ob_start();
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings' );
		$comment_datas             = $this->pull_comment_data( get_the_ID() );
		$review_emoticons_list     = $this->emoticon_lists();
		$aggregate_review          = round( $comment_datas['aggregate'], 1 );
		$emoticon_option           = isset( $wp_travel_engine_settings['trip_reviews']['show_emoticons'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['show_emoticons'] ) : '';

		if ( ! empty( $aggregate_review ) && $emoticon_option == 1 ) {
			remove_filter( 'the_content', 'wpautop' );
			switch ( $aggregate_review ) {
				case in_array( $aggregate_review, range( 0, 1, 0.1 ) ): // the range from range of 0-1
					echo apply_filters( 'the_content', $review_emoticons_list['terrible_icon'] );
					break;
				case in_array( $aggregate_review, range( 1, 2, 0.1 ) ): // range of 1-2
					echo apply_filters( 'the_content', $review_emoticons_list['poor_icon'] );
					break;
				case in_array( $aggregate_review, range( 2, 3, 0.1 ) ): // range of 2-3
					echo apply_filters( 'the_content', $review_emoticons_list['average_icon'] );
					break;
				case in_array( $aggregate_review, range( 3, 4, 0.1 ) ): // range of 3-4
					echo apply_filters( 'the_content', $review_emoticons_list['vgood_icon'] );
					break;
				case in_array( $aggregate_review, range( 4, 5, 0.1 ) ): // range of 4-5
					echo apply_filters( 'the_content', $review_emoticons_list['excellent_icon'] );
					break;
				default:
			}
			add_filter( 'the_content', 'wpautop' );
		}
		$output = ob_get_clean();
		$output = apply_filters( 'wte_filtered_average_review_range_emoticons', $output );
		echo $output;
	}

	/**
	 * Action Hook: Emoticon Icon Display before review star
	 *
	 * @return void
	 */
	public function company_average_review_range_emoticons() {
		ob_start();
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings' );
		$comment_datas             = $this->pull_all_comments_data( get_the_ID() );
		$review_emoticons_list     = $this->emoticon_lists();
		$aggregate_review          = round( $comment_datas['aggregate'], 1 );
		$emoticon_option           = isset( $wp_travel_engine_settings['trip_reviews']['show_emoticons'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['show_emoticons'] ) : '';

		remove_filter( 'the_content', 'wpautop' );
		if ( ! empty( $aggregate_review ) && $emoticon_option == 1 ) {
			switch ( $aggregate_review ) {
				case in_array( $aggregate_review, range( 0, 1, 0.1 ) ): // the range from range of 0-1
					echo apply_filters( 'the_content', $review_emoticons_list['terrible_icon'] );
					break;
				case in_array( $aggregate_review, range( 1, 2, 0.1 ) ): // range of 1-2
					echo apply_filters( 'the_content', $review_emoticons_list['poor_icon'] );
					break;
				case in_array( $aggregate_review, range( 2, 3, 0.1 ) ): // range of 2-3
					echo apply_filters( 'the_content', $review_emoticons_list['average_icon'] );
					break;
				case in_array( $aggregate_review, range( 3, 4, 0.1 ) ): // range of 3-4
					echo apply_filters( 'the_content', $review_emoticons_list['vgood_icon'] );
					break;
				case in_array( $aggregate_review, range( 4, 5, 0.1 ) ): // range of 4-5
					echo apply_filters( 'the_content', $review_emoticons_list['excellent_icon'] );
					break;
				default:
			}
			add_filter( 'the_content', 'wpautop' );
		}
		$output = ob_get_clean();
		$output = apply_filters( 'wte_filtered_company_average_review_range_emoticons', $output );
		echo $output;
	}

	public function average_review_range_label() {
		$comment_datas = $this->pull_comment_data( get_the_ID() );

		$review_repsonse_texts = $this->overall_textlist_of_responses();
		if ( ! empty( $comment_datas ) ) {
			$aggregate_review = round( $comment_datas['aggregate'], 1 );
			switch ( $aggregate_review ) {
				case in_array( $aggregate_review, range( 0, 1, 0.1 ) ): // the range from range of 0-1
					echo $review_repsonse_texts['terrible_label'];
					break;
				case in_array( $aggregate_review, range( 1, 2, 0.1 ) ): // range of 1-2
					echo $review_repsonse_texts['poor_label'];
					break;
				case in_array( $aggregate_review, range( 2, 3, 0.1 ) ): // range of 2-3
					echo $review_repsonse_texts['average_label'];
					break;
				case in_array( $aggregate_review, range( 3, 4, 0.1 ) ): // range of 3-4
					echo $review_repsonse_texts['vgood_label'];
					break;
				case in_array( $aggregate_review, range( 4, 5, 0.1 ) ): // range of 4-5
					echo $review_repsonse_texts['excellent_label'];
					break;
				default:
			}
		}
	}

	public function company_average_review_range_label() {
		$comment_datas = $this->pull_all_comments_data();

		$review_repsonse_texts = $this->overall_textlist_of_responses();
		if ( ! empty( $comment_datas ) ) {
			$aggregate_review = round( $comment_datas['aggregate'], 1 );
			switch ( $aggregate_review ) {
				case in_array( $aggregate_review, range( 0, 1, 0.1 ) ): // the range from range of 0-1
					echo $review_repsonse_texts['terrible_label'];
					break;
				case in_array( $aggregate_review, range( 1, 2, 0.1 ) ): // range of 1-2
					echo $review_repsonse_texts['poor_label'];
					break;
				case in_array( $aggregate_review, range( 2, 3, 0.1 ) ): // range of 2-3
					echo $review_repsonse_texts['average_label'];
					break;
				case in_array( $aggregate_review, range( 3, 4, 0.1 ) ): // range of 3-4
					echo $review_repsonse_texts['vgood_label'];
					break;
				case in_array( $aggregate_review, range( 4, 5, 0.1 ) ): // range of 4-5
					echo $review_repsonse_texts['excellent_label'];
					break;
				default:
			}
		}
	}

	/**
	 * Action Hook: Show company Header
	 *
	 * @return void
	 */
	public function show_company_review_header() {
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
		$main_label                = __( 'Overall Company Rating', 'wte-trip-review' );
		if ( isset( $wp_travel_engine_settings['trip_reviews'] ) && isset( $wp_travel_engine_settings['trip_reviews']['company_summary_label'] ) && $wp_travel_engine_settings['trip_reviews']['company_summary_label'] != '' ) {
			$main_label = esc_attr( $wp_travel_engine_settings['trip_reviews']['company_summary_label'] );
		}
		echo '<div class="trip-review-title"><b>' . $main_label . '</b></div>';
	}

	 /**
	  * Function: To Show Trip Review Header for particular Trip
	  *
	  * @param [type] $postid
	  * @return void
	  */
	public function show_trip_review_header() {
		$main_label                = __( 'Overall Trip Rating', 'wte-trip-review' );
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
		$wp_travel_engine_setting  = get_post_meta( get_the_ID(), 'wp_travel_engine_setting', true );

		$comments_data = $this->pull_comment_data( get_the_ID() );

		if ( ! empty( $comments_data ) ) {
			if ( isset( $wp_travel_engine_setting['review'] ) && isset( $wp_travel_engine_setting['review']['review_title'] ) && $wp_travel_engine_setting['review']['review_title'] != '' ) {
				$main_label = esc_attr( $wp_travel_engine_setting['review']['review_title'] );
			} elseif ( isset( $wp_travel_engine_settings['trip_reviews'] ) && isset( $wp_travel_engine_settings['trip_reviews']['summary_label'] ) && $wp_travel_engine_settings['trip_reviews']['summary_label'] != '' ) {
				$main_label = esc_attr( $wp_travel_engine_settings['trip_reviews']['summary_label'] );
			}
			echo '<div class="trip-review-title"><b>' . $main_label . '</b></div>';
		}
	}

	/**
	 * Function: To show trip specific overall review
	 *
	 * @param [type] $postid
	 * @return void
	 */
	public function show_trip_overall_review( $postid ) {
		global $post;
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
		if ( class_exists( 'Wte_Trip_Review_Init' ) ) {
			// Review Average section goes here  (Based on X Review)
			$comments_data = $this->pull_comment_data( get_the_ID() );
			if ( ! empty( $comments_data ) ) {
				do_action( 'wte_average_review_wrap_open' );
				do_action( 'wte_trip_review_schema_json' );
				do_action( 'wte_trip_average_rating' );
				do_action( 'wte_average_review_wrap_close' );
				do_action( 'wte_trip_overall_review' );
			}
		}
	}

	function show_trip_review_schema_json() {
		global $post;
		$object        = new Wp_Travel_Engine_Functions();
		$priceCurrency = $object->trip_currency_code( $post );
		$priceCurrency = ! empty( $priceCurrency ) ? $priceCurrency : 'USD';
		$actual_price  = $object->trip_price( $post->ID );
		$actual_price  = ! empty( $actual_price ) ? $actual_price : 0;
		$content       = stripslashes_deep( $post->post_content );
		$content       = strip_tags( strip_shortcodes( $content ) );
		$image         = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
		$comment_datas = $this->pull_comment_data( get_the_ID() );
		if ( ! empty( $comment_datas ) ) {
			include WTE_TRIP_REVIEW_BASE_PATH . '/includes/function-inc/schema-data-inc.php';
		}
	}

	function show_trip_overall_average_rating() {
		$id            = get_the_ID();
		$comment_datas = $this->pull_comment_data( $id );
		if ( ! empty( $comment_datas ) ) {
			$overall_average_template_file = apply_filters( 'overall_average_custom_template', 'overall-average-review-data-inc.php' );
			wte_trip_reviews_get_template( $overall_average_template_file, array( 'id' => $id ) );
		}
		// include WTE_TRIP_REVIEW_BASE_PATH . '/includes/function-inc/overall-average-review-data-inc.php';
	}

	function show_trip_average_rating() {
		$review_repsonse_texts = $this->overall_textlist_of_responses();
		$comment_datas         = $this->pull_comment_data( get_the_ID() );
		if ( ! empty( $comment_datas ) ) {
			do_action( 'wte_average_review_range_emoticons' );
			do_action( 'wte_trip_average_rating_star' );
			do_action( 'wte_trip_average_rating_based_on_text' );
		}
	}

	function show_trip_average_rating_from_wte() {
		$review_repsonse_texts = $this->overall_textlist_of_responses();
		$comment_datas         = $this->pull_comment_data( get_the_ID() );
		if ( ! empty( $comment_datas ) ) {
			do_action( 'wte_average_review_wrap_open' );
			do_action( 'wte_average_review_range_emoticons' );
			do_action( 'wte_trip_average_rating_star' );
			do_action( 'wte_trip_average_rating_based_on_text' );
			do_action( 'wte_average_review_wrap_close' );
		}
	}

	function show_trip_rating( $postid ) {
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
		if ( class_exists( 'Wte_Trip_Review_Init' ) ) {
			do_action( 'wte_review_wrap_open' );
			do_action( 'wte_trip_review_header' );
			do_action( 'wte_trip_average_rating_from_wte' );
			do_action( 'wte_trip_overall_review' );
			do_action( 'wte_trip_review_schema_json' );
			do_action( 'wte_list_reviews' );
			do_action( 'wte_review_wrap_close' );
		}
	}

	function show_company_schema_markup_json() {
		global $post;
		$object        = new Wp_Travel_Engine_Functions();
		$priceCurrency = $object->trip_currency_code( $post );
		$priceCurrency = ! empty( $priceCurrency ) ? $priceCurrency : 'USD';
		$actual_price  = $object->trip_price( $post->ID );
		$actual_price  = ! empty( $actual_price ) ? $actual_price : 0;
		$content       = stripslashes_deep( $post->post_content );
		$content       = strip_tags( strip_shortcodes( $content ) );
		$image         = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
		$comment_datas = $this->pull_all_comments_data();
		if ( ! empty( $comment_datas ) ) {
			include WTE_TRIP_REVIEW_BASE_PATH . '/includes/function-inc/schema-data-inc.php';
		}
	}

	/**
	 * Function
	 * Overall Company Review Display: (Exellent 2, Very Good 1)
	 *
	 * @return void
	 */
	function show_company_overall_average_rating() {
		include WTE_TRIP_REVIEW_BASE_PATH . '/includes/function-inc/overall-company-average-review-data-inc.php';
	}

	/**
	 * function
	 * Average value (Rating XX: Based on X Reveiews )
	 *
	 * @return void
	 */
	function show_company_average_rating_count() {
		$review_repsonse_texts = $this->overall_textlist_of_responses();
		$comment_datas         = $this->pull_all_comments_data();
		if ( ! empty( $comment_datas ) ) {
			do_action( 'wte_company_average_review_range_emoticons' );
			do_action( 'wte_company_average_rating_star' );
			do_action( 'wte_company_average_rating_based_on_text' );
		}
	}

	function wte_review_comment_form_fields( $fields ) {
		if ( isset( $fields['stars'] ) ) {
			unset( $fields['stars'] );
		}
		$commenter                 = wp_get_current_commenter();
		$fields['title']           = $this->wte_review_form_review_title();
		$fields['gallery']         = $this->wte_review_form_photo_gallery();
		$fields['experience_date'] = $this->wte_review_form_experience_date();
		$fields['stars']           = $this->wte_review_form_review_rate();

		$fields['author'] = '<p class="comment-form-author">' .
			'<label for="author">' . _x( 'Name', 'wte-trip-review' ) . '</label>' .
			'<input id="author" name="author" type="text" placeholder="' . __( 'Name*', 'wte-trip-review' ) . '" value="' . esc_attr( $commenter['comment_author'] ) .
			'" size="30" tabindex="1" aria-required="true" required=""/></p>';

		$fields['email'] = '<p class="comment-form-email">' .
			'<label for="email">' . _x( 'Email', 'wte-trip-review' ) . '</label>' .
			'<input id="email" name="email" type="text" placeholder="' . __( 'Email*', 'wte-trip-review' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) .
			'" size="30"  tabindex="2" aria-required="true" required=""/></p>';

		$fields['url'] = '<p class="comment-form-url">' .
			'<label for="url">' . _x( 'Website', 'wte-trip-review' ) . '</label>' .
			'<input id="url" name="url" type="text" placeholder="' . _x( 'Website', 'wte-trip-review' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) .
			'" size="30"  tabindex="3" /></p>';

			// redefine your own textarea (the comment body)
		$fields['comment'] = '<p class="comment-form-comment"><label for="comment">' . _x( 'Review', 'noun', 'wte-trip-review' ) . '</label><textarea id="comment" required placeholder="' . __( 'Write a review*', 'wte-trip-review' ) . '" name="comment" aria-required="true"></textarea></p>';

		return $fields;
	}

	/**
	 * function to call review form in front
	 *
	 * @return void
	 */
	function show_trip_rating_form() {
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
		$review_form_hide          = isset( $wp_travel_engine_settings['trip_reviews']['hide_form'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_form'] ) : '';
		if ( empty( $review_form_hide ) ) {
			$comments_args = array(
				'label_submit' => __( 'Submit', 'wte-trip-review' ),
				'title_reply'  => __( 'Write a Review', 'wte-trip-review' ),
			);
			if ( 'trip' == get_post_type() ) {
				comment_form( $comments_args );
			}
		}
	}

	/**
	 * Add custom HTML between the `</h3>` and the `<form>` tags in the comment_form() output.
	 */
	function wte_reivew_form_div_before_comment_form() {
		if ( 'trip' == get_post_type() ) {
			add_filter( 'pre_option_comment_registration', array( $this, 'wte_reivew_form_message_html' ) );
		}
	}

	function wte_reivew_form_message_html( $comment_registration ) {
		echo '<p class="review-notes" id="review-notes" style="display:none;">' . __( 'Thank you. Your review will appear after admin approves it.', 'wte-trip-review' ) . '</p>' . '<p class="validate-notes" style="display:none;">' . __( 'Please fill all the fields.', 'wte-trip-review' ) . '</p>';
		remove_filter( current_filter(), __FUNCTION__ );
		return $comment_registration;
	}

	/**
	 * Display comment in home & archive page
	 * Archive Filter to call comment parts
	 *
	 * @return void
	 */
	function rw_archive_comment_callback( $comment, $args, $depth ) {
		return wp_travel_engine_trip_reviews_get_template( 'comment-thread.php' );
	}

	/**
	 * Comment Part: Comment Avatar
	 */
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
	function comment_meta_author( $comment ) {
		if ( ! $comment ) {
			$comment_id = get_comment_ID();
			$comment    = get_comment( $comment_id );
		}
		$commenttitle = get_comment_meta( $comment->comment_ID, 'title', true );
		if ( $commenttitle ) {
			printf( '<span class="url">%s</span>', get_comment_author( $comment->comment_ID ) );
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
	function comment_meta_gallery( $comment ) {
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings' );
		$hide_image_upload_field   = isset( $wp_travel_engine_settings['trip_reviews']['hide_image_upload_field'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_image_upload_field'] ) : '';
		if ( $hide_image_upload_field ) {
			remove_action( 'comment_meta_gallery', 'comment_meta_gallery' );
		} else {
			ob_start();
			$comment_id     = ( isset( $comment ) && is_object( $comment ) ) ? $comment->comment_ID : get_comment_ID();
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
			$output = ob_get_clean();
			$output = apply_filters( 'wte_filtered_comment_gallery_section', $output, $comment );
			echo $output;
		}
	}

	/**
	 * Comment Part: Comment Rating
	 */
	function comment_rating( $comment ) {
		$text       = '';
		$comment_id = ( isset( $comment ) && is_object( $comment ) ) ? $comment->comment_ID : get_comment_ID();
		if ( $commentwte_trip_review_rating = get_comment_meta( $comment_id, 'stars', true ) ) {
			$icon_type               = '';
			$icon_fill_color         = '#F39C12';
			$review_icon_type        = apply_filters( 'trip_rating_icon_type', $icon_type );
			$review_icon_fill_colors = apply_filters( 'trip_rating_icon_fill_color', $icon_fill_color );
			?>
			<div class="comment-indv-rating-wrap">
				<div class="comment-indvidual-rating trip-review-stars <?php echo ! empty( $review_icon_type ) ? 'svg-trip-adv' : 'trip-review-default'; ?>"
					data-icon-type='<?php echo $review_icon_type; ?>'
					data-rating-value=" <?php echo $commentwte_trip_review_rating; ?>"
					data-rateyo-rated-fill="<?php echo $review_icon_fill_colors; ?>" data-rateyo-read-only="true"></div>
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
	function comment_reviewed_tour( $comment = false ) {
		$main_label = __( 'Reviewed Tour', 'wte-trip-review' );
		if ( ! $comment ) {
			$comment_id = get_comment_ID();
			$comment    = get_comment( $comment_id );
		}
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
		$hide_reviewed_tour_field  = isset( $wp_travel_engine_settings['trip_reviews']['hide_reviewed_tour_field'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_reviewed_tour_field'] ) : '';
		if ( $hide_reviewed_tour_field ) {
			remove_action( 'comment_reviewed_tour', 'comment_reviewed_tour' );
		} else {
			ob_start();
			global $post;
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
			$output = apply_filters( 'wte_filtered_comment_reviewed_tour_section', $comment );
			echo $output;
		}
	}

	/**
	 * Comment Part:
	 * Convert text to X days ago
	 */
	function comment_days_ago( $comment = false ) {
		if ( ! $comment ) {
			$com_id  = get_comment_ID();
			$comment = get_comment( $com_id );
		}
		$comment_date = strtotime( $comment->comment_date );
		?>
			<span>(<?php printf( _x( '%1$s ago', '%2$s = human-readable time difference', 'wte-trip-review' ), human_time_diff( $comment_date, current_time( 'timestamp' ) ) ); ?>)</span>
		<?php
	}

	/**
	 * Comment Part:
	 * Comment Content
	 */
	function comment_content() {
		?>
		<div class="comment-content">
			<?php echo get_comment_text(); ?>
		</div>
		<?php
	}

	/**
	 * Comment Part:
	 * Experiance Date
	 */
	function comment_experience_date( $comment = false ) {
		if ( ! $comment ) {
			$comment_id = get_comment_ID();
			$comment    = get_comment( $comment_id );
		}
		$wp_travel_engine_settings  = get_option( 'wp_travel_engine_settings' );
		$hide_experience_date_field = isset( $wp_travel_engine_settings['trip_reviews']['hide_experience_date_field'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_experience_date_field'] ) : '';

		if ( $hide_experience_date_field ) {
			remove_action( 'comment_experience_date', 'comment_experience_date' );
		} else {
			ob_start();
			$experience_date = get_comment_meta( $comment->comment_ID, 'experience_date', true );
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
	function comment_client_location( $comment = false ) {
		if ( ! $comment ) {
			$comment_id = get_comment_ID();
			$comment    = get_comment( $comment_id );
		}
		$wp_travel_engine_settings  = get_option( 'wp_travel_engine_settings' );
		$hide_client_location_field = isset( $wp_travel_engine_settings['trip_reviews']['hide_client_location_field'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_client_location_field'] ) : '';
		if ( $hide_client_location_field ) {
			remove_action( 'comment_client_location', 'comment_client_location' );
		} else {
			$client_location = get_comment_meta( $comment->comment_ID, 'client_location', true );
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

$obj = new Wte_Trip_Review_Init();
$obj->init();
