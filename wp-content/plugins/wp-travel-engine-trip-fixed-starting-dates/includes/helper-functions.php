<?php
/**
 * Helper functions.
 */
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
function wte_fsd_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
        $template_path = apply_filters( 'wp_travel_engine_FSD_template_path', 'wp-travel-engine-fsd/' );
    }

	if ( ! $default_path ) {
		$default_path = WTE_FIXED_DEPARTURE_BASE_PATH . '/templates/';
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
	return apply_filters( 'wte_fsd_locate_template', $template, $template_name, $template_path );
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
function wte_fsd_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	$cache_key = sanitize_key ( implode( '-', array( 'template', $template_name, $template_path, $default_path, WTE_FIXED_DEPARTURE_VERSION ) ) );
	$template = (string) wp_cache_get( $cache_key, 'wp-travel-engine-fsd' );

	if ( ! $template ) {
		$template = wte_fsd_locate_template( $template_name, $template_path, $default_path );
		wp_cache_set( $cache_key, $template, 'wp-travel-engine-fsd' );
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$filter_template = apply_filters( 'wte_fsd_get_template', $template, $template_name, $args, $template_path, $default_path );

	if( $filter_template !== $template ) {
		if ( ! file_exists( $filter_template ) ) {
			/* translators: %s template */
			wte_fsd_doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'wte-fixed-departure-dates' ), '<code>' . $template . '</code>' ), '1.0.0' );
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
			wte_fsd_doing_it_wrong(
				__FUNCTION__,
				__( 'action_args should not be overwritten when calling wte_fsd_get_template.', 'wte-fixed-departure-dates' ),
				'1.0.0'
			);
			unset( $args['action_args'] );
		}
		extract( $args );
	}

	do_action( 'wte_fsd_before_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );

	include $action_args['located'];

	do_action( 'wte_fsd_after_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );
}


/**
 * Like wte_fsd_get_template, but return the HTML instaed of outputting.
 *
 * @see wte_fsd_get_template
 * @since 1.0.0
 *
 * @param string $template_name Template name.
 * @param array $args           Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 *
 * @return string.
 */
function wte_fsd_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	ob_start();
		wte_fsd_get_template( $template_name, $args, $template_path, $default_path );
	return ob_get_clean();
}

/**
 * Get currency code or symbol.
 *
 * @return void
 */
function wte_fsd_get_currency_code_or_symbol(){
	$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
	$code = 'USD';

    if( isset( $wp_travel_engine_settings['currency_code'] ) && $wp_travel_engine_settings['currency_code']!= '' ){
        $code = $wp_travel_engine_settings['currency_code'];
	}

	$symbol = wp_travel_engine_get_currency_symbol( $code );

	$currency_option = isset( $wp_travel_engine_settings['currency_option'] ) && ! empty( $wp_travel_engine_settings['currency_option'] ) ? $wp_travel_engine_settings['currency_option'] : 'symbol';

	return 'symbol' === $currency_option ? $symbol : $code;
}

/**
 *
 * @since 2.3.10
 */
function wte_fsd_core_settings() {
	if ( function_exists( 'wptravelengine_settings' ) ) {
		return wptravelengine_settings();
	}
}
