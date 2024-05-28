<?php

namespace WPTravelEngine\Filters;

class Template {

	public static function include_trip_template( $template_path ) {
		if ( get_post_type() === \WP_TRAVEL_ENGINE_POST_TYPE ) {
			if ( is_single() ) {
				$template_path = wte_locate_template( 'single-trip.php' );
			}
			if ( is_archive() ) {
				$template_path = wte_locate_template( 'archive-trip.php' );
			}
			$taxonomies = array( 'trip_types', 'destination', 'activities' );
			foreach ( $taxonomies as $tax ) {
				if ( is_tax( $tax ) ) {
					$template_path = wte_locate_template( 'taxonomy-' . $tax . '.php' );
				}
			}
		}

		return $template_path;
	}
}
