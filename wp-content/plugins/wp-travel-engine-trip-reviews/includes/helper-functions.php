<?php
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
function wp_travel_engine_trip_reviews_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
        $template_path = apply_filters( 'wp_travel_engine_trip_reviews_template_path', 'wp-travel-engine/' );
    }

	if ( ! $default_path ) {
		$default_path = plugin_dir_path( WTE_TRIP_REVIEW_FILE_PATH ) . '/templates/';
	}

	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit(  $template_path ) . $template_name,
			$template_name
		)
	);

	// Get default template.
	if ( ! $template ) {
		// Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				trailingslashit( $template_name ),
				$template_name
			)
		);
		if( ! $template ){
			$template = $default_path . $template_name;
		}
	}

	// Return what we found.
	return apply_filters( 'wp_travel_engine_trip_reviews_locate_template', $template, $template_name, $template_path );
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
function wp_travel_engine_trip_reviews_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	$cache_key = sanitize_key ( implode( '-', array( 'template', $template_name, $template_path, $default_path, WTE_TRIP_REVIEW_VERSION ) ) );
	$template = (string) wp_cache_get( $cache_key, 'wp-travel-engine' );

	if ( ! $template ) {
		$template = wp_travel_engine_trip_reviews_locate_template( $template_name, $template_path, $default_path );
		wp_cache_set( $cache_key, $template, 'wp-travel-engine' );
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$filter_template = apply_filters( 'wp_travel_engine_trip_reviews_get_template', $template, $template_name, $args, $template_path, $default_path );

	if( $filter_template !== $template ) {
		if ( ! file_exists( $filter_template ) ) {
			/* translators: %s template */
			wp_travel_engine_trip_reviews_doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'wte-trip-review' ), '<code>' . $template . '</code>' ), '1.0.0' );
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
			wp_travel_engine_trip_reviews_doing_it_wrong(
				__FUNCTION__,
				__( 'action_args should not be overwritten when calling wp_travel_engine_trip_reviews_get_template.', 'wte-trip-review' ),
				'1.0.0'
			);
			unset( $args['action_args'] );
		}
		extract( $args );
	}

	do_action( 'wp_travel_engine_trip_reviews_before_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );

	include $action_args['located'];

	do_action( 'wp_travel_engine_trip_reviews_after_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );
}


/**
 * Like wp_travel_engine_trip_reviews_get_template, but return the HTML instaed of outputting.
 *
 * @see wp_travel_engine_trip_reviews_get_template
 * @since 1.0.0
 *
 * @param string $template_name Template name.
 * @param array $args           Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 *
 * @return string.
 */
function wp_travel_engine_trip_reviews_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	ob_start();
		wp_travel_engine_trip_reviews_get_template( $template_name, $args, $template_path, $default_path );
	return ob_get_clean();
}

function wptravelengine_reviews_star_markup( $average ) {
	?>
	<div class="wpte-trip-review-stars">
		<div class="stars-group-wrapper">
			<div class="stars-placeholder-group">
				<?php
				echo implode(
					'',
					array_map(
						function() {
							return '<svg width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M6.41362 0.718948C6.77878 -0.0301371 7.84622 -0.0301371 8.21138 0.718948L9.68869 3.74946C9.83326 4.04602 10.1148 4.25219 10.4412 4.3005L13.7669 4.79272C14.5829 4.91349 14.91 5.91468 14.3227 6.49393L11.902 8.88136C11.6696 9.1105 11.5637 9.4386 11.6182 9.76034L12.1871 13.1191C12.3258 13.9378 11.464 14.559 10.7311 14.1688L7.78252 12.5986C7.4887 12.4421 7.1363 12.4421 6.84248 12.5986L3.89386 14.1688C3.16097 14.559 2.29922 13.9378 2.43789 13.1191L3.0068 9.76034C3.06129 9.4386 2.95537 9.1105 2.72303 8.88136L0.302324 6.49393C-0.285 5.91468 0.0420871 4.91349 0.85811 4.79272L4.18383 4.3005C4.5102 4.25219 4.79174 4.04602 4.93631 3.74946L6.41362 0.718948Z" fill="#EBAD34"></path></svg>';
						},
						range( 0, 4 )
					)
				);
				?>
			</div>
			<div
				class="stars-rated-group"
				style="width: <?php echo (int) $average / 5 * 100; ?>%"
			>
			<?php
				echo implode(
					'',
					array_map(
						function() {
							return '<svg width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M6.41362 0.718948C6.77878 -0.0301371 7.84622 -0.0301371 8.21138 0.718948L9.68869 3.74946C9.83326 4.04602 10.1148 4.25219 10.4412 4.3005L13.7669 4.79272C14.5829 4.91349 14.91 5.91468 14.3227 6.49393L11.902 8.88136C11.6696 9.1105 11.5637 9.4386 11.6182 9.76034L12.1871 13.1191C12.3258 13.9378 11.464 14.559 10.7311 14.1688L7.78252 12.5986C7.4887 12.4421 7.1363 12.4421 6.84248 12.5986L3.89386 14.1688C3.16097 14.559 2.29922 13.9378 2.43789 13.1191L3.0068 9.76034C3.06129 9.4386 2.95537 9.1105 2.72303 8.88136L0.302324 6.49393C-0.285 5.91468 0.0420871 4.91349 0.85811 4.79272L4.18383 4.3005C4.5102 4.25219 4.79174 4.04602 4.93631 3.74946L6.41362 0.718948Z" fill="#EBAD34"></path></svg>';
						},
						range( 0, 4 )
					)
				);
			?>
			</div>
		</div>
	</div>
	<?php
}

function wptravelengine_reviews_the_user_star_rating( $comment_id ) {
	$rating_star = get_comment_meta( $comment_id, 'stars', true );
	wptravelengine_reviews_star_markup( $rating_star );
}


/**
 * Gets Trip Reviews.
 */
function wptravelengine_reviews_get_trip_reviews( $trip_id ) {

	global $wpdb;

	// SELECT c.comment_content, JSON_OBJECTAGG(wp_commentmeta.`meta_key`, wp_commentmeta.meta_value)  FROM wp_comments as c INNER JOIN wp_commentmeta WHERE c.comment_post_ID = 22 AND c.comment_ID = wp_commentmeta.comment_id GROUP BY wp_commentmeta.comment_id
	$where = "c.comment_ID = cm.comment_id AND c.comment_post_ID = {$trip_id}";
	$query = "SELECT c.comment_ID, c.comment_content, JSON_OBJECTAGG(cm.meta_key, cm.meta_value) as reviews_meta FROM {$wpdb->comments} as c INNER JOIN {$wpdb->commentmeta} as cm WHERE {$where} GROUP BY cm.comment_id";

	$results = $wpdb->get_results( $query );

	$_result = array();
	if ( $results && is_array( $results ) ) {
		$reviews_meta = array(
			'phone'           => '',
			'title'           => '',
			'stars'           => 0,
			'experience_date' => '',
		);
		$i            = 0;
		foreach ( $results as $result ) {
			$_result[ $i ]['ID']      = (int) $result->comment_ID;
			$_result[ $i ]['content'] = $result->comment_content;

			if ( isset( $result->reviews_meta ) && json_decode( $result->reviews_meta ) ) {
				$_metas = json_decode( $result->reviews_meta );
				foreach ( $reviews_meta as $key => $value ) {
					if ( isset( $_metas->$key ) ) {
						$_result[ $i ][ $key ] = 'stars' === $key ? (int) $_metas->{$key} : $_metas->{$key};
					} else {
						$_result[ $i ][ $key ] = $value;
					}
				}
			}
			$i++;
		}
	}

	$stars = array_column( $_result, 'stars' );

	return array(
		'reviews' => $_result,
		'average' => count( $stars ) > 0 ? array_sum( $stars ) / count( $stars ) : 0,
		'count'   => count( $stars ),
	);
}
