<?php
/**
 * Trip review tab.
 */
    $review_tab_details = array(
        'wpte-trip-reviews-list' => array(
            'tab_label'         => __( 'Trip Reviews', 'wte-trip-review' ),
            'tab_heading'       => __( 'Trip Reviews', 'wte-trip-review' ),
            'content_path'      => plugin_dir_path( __FILE__ ) . '/review-tabs/reviews-list.php',
            'callback_function' => 'wpte_reviews_trip_tab_review_list',
            'content_key'       => 'wpte-trip-reviews-list',
            'current'           => true,
            'content_loaded'    => true,
            'priority'          => 10
        ),
        'wpte-trip-reviews-form' => array(
            'tab_label'         => __( 'Trip Review Form', 'wte-trip-review' ),
            'tab_heading'       => __( 'Trip Review Form', 'wte-trip-review' ),
            'content_path'      => plugin_dir_path( __FILE__ ) . '/review-tabs/reviews-form.php',
            'callback_function' => 'wpte_reviews_trip_tab_review_form',
            'content_key'       => 'wpte-trip-reviews-form',
            'current'           => false,
            'content_loaded'    => false,
            'priority'          => 20
        ),
    );
    // Sorted array of tabs.
    $review_tab_details = wp_travel_engine_sort_array_by_priority( $review_tab_details );

    // Initialize tabs class.
    include_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . '/admin/class-wp-travel-engine-tabs-ui.php';
    $admin_tabs_ui = new WP_Travel_Engine_Tabs_UI;

    $tab_args = array(
        'id'          => 'wpte-trip-review',
        'class'       => 'wpte-trip-review',
        'content_key' => 'wpte_trip_review_tab'
    );
    echo '<div class="wpte-add-ons-wrap" id="wpte-add-ons"><div class="wpte-trp-rev-wrap">';
        // Load Tabs.
        $admin_tabs_ui->init( $tab_args )->template( $review_tab_details );
    echo '</div></div>';
?>