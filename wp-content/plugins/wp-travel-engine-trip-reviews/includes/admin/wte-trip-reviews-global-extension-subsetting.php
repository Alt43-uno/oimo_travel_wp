<?php 
	$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
    $option = isset( $wp_travel_engine_settings['trip_reviews']['hide'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide'] ) : '';
    $option_form_hide = isset( $wp_travel_engine_settings['trip_reviews']['hide_form'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_form'] ) : '';
	$emoticon_option = isset( $wp_travel_engine_settings['trip_reviews']['show_emoticons'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['show_emoticons'] ) : '';
	$hide_experience_date_field = isset( $wp_travel_engine_settings['trip_reviews']['hide_experience_date_field'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_experience_date_field'] ) : '';
	$hide_image_upload_field = isset( $wp_travel_engine_settings['trip_reviews']['hide_image_upload_field'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_image_upload_field'] ) : '';
	$hide_reviewed_tour_field = isset( $wp_travel_engine_settings['trip_reviews']['hide_reviewed_tour_field'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_reviewed_tour_field'] ) : '';
	$hide_client_location_field = isset( $wp_travel_engine_settings['trip_reviews']['hide_client_location_field'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['hide_client_location_field'] ) : '';
	?>
    <div class="wp-travel-engine-fields-settings review wpte-main-wrap wpte-form-content">
        <div class="wpte-form-block">
            <div class="wpte-field wpte-checkbox advance-checkbox">
                <label class="wpte-field-label" for="wp_travel_engine_settings[trip_reviews][hide]"><?php _e('Hide Trip Reviews ', 'wte-trip-review');?></label>
                <div class="wpte-checkbox-wrap">
                    <input type="checkbox" id="wp_travel_engine_settings[trip_reviews][hide]" name="wp_travel_engine_settings[trip_reviews][hide]" value="1" <?php echo checked('1', $option); ?>>
                    <label for="wp_travel_engine_settings[trip_reviews][hide]" class="checkbox-label"></label>
                </div>
                <span class="wpte-tooltip"><?php _e('Enable the switch to hide whole trip reviews section on your trip page.', 'wte-trip-review');?></span>
            </div>
            <div class="wpte-field wpte-checkbox advance-checkbox">
                <label class="wpte-field-label" for="wp_travel_engine_settings[trip_reviews][hide_form]"><?php _e('Hide Trip Review Form', 'wte-trip-review');?></label>
                <div class="wpte-checkbox-wrap">
                    <input type="checkbox" id="wp_travel_engine_settings[trip_reviews][hide_form]" name="wp_travel_engine_settings[trip_reviews][hide_form]" value="1" <?php echo checked('1', $option_form_hide); ?>>
                    <label for="wp_travel_engine_settings[trip_reviews][hide_form]" class="checkbox-label"></label>
                </div>
                <span class="wpte-tooltip"><?php _e('Enable the switch to hide trip review form section on your trip page.', 'wte-trip-review');?></span>
            </div>
            <div class="wpte-title-wrap">
                <h3 class="wpte-title"><?php _e('Review Labels','wte-trip-review');?></h3>
            </div>
            <div class="wpte-field wpte-text wpte-floated">
                <label class="wpte-field-label" for="wp_travel_engine_settings[trip_reviews][summary_label]"><?php _e('Trip Review Label','wte-trip-review');?></label>
                <input type="text" id="wp_travel_engine_settings[trip_reviews][summary_label]" name="wp_travel_engine_settings[trip_reviews][summary_label]" value="<?php echo isset( $wp_travel_engine_settings['trip_reviews']['summary_label'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['summary_label'] ):'Overall Trip Rating:';?>">
                <span class="wpte-tooltip"><?php _e('Default Label: Overall Trip Rating. This label is displayed before the post specific trip reviews.','wte-trip-review');?></span>
            </div>
             <div class="wpte-field wpte-text wpte-floated">
                <label class="wpte-field-label" for="wp_travel_engine_settings[trip_reviews][company_summary_label]"><?php _e('Company Trip Label','wte-trip-review');?></label>
                <input type="text" id="wp_travel_engine_settings[trip_reviews][company_summary_label]" name="wp_travel_engine_settings[trip_reviews][company_summary_label]" value="<?php echo isset( $wp_travel_engine_settings['trip_reviews']['company_summary_label'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['company_summary_label'] ):'Overall Company Rating:';?>">
                 <span class="wpte-tooltip"><?php _e('Default Label: Overall Company Rating. This label is displayed before the all trip\'s review listing rather than individual post reviews.','wte-trip-review');?></span>
            </div>
            <div class="wpte-field wpte-text wpte-floated">
                <label class="wpte-field-label" for="wp_travel_engine_settings[trip_reviews][reviewed_tour_text]"><?php _e('Reviewed Tour Label','wte-trip-review');?></label>
                <input type="text" id="wp_travel_engine_settings[trip_reviews][reviewed_tour_text]" name="wp_travel_engine_settings[trip_reviews][reviewed_tour_text]" value="<?php echo isset( $wp_travel_engine_settings['trip_reviews']['reviewed_tour_text'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['reviewed_tour_text'] ):'Reviewed Tour:';?>">
                <span class="wpte-tooltip"><?php _e('Default Label: Reviewed Tour. This label is displayed before the trip post link on singular review i.e. quick link to trip post on which review was posted.','wte-trip-review');?></span>
            </div>
            <div class="wpte-field wpte-text wpte-floated">
                <label class="wpte-field-label" for="wp_travel_engine_settings[trip_reviews][excellent_label]"><?php _e('Excellent Review Label','wte-trip-review');?></label>
                <input type="text" id="wp_travel_engine_settings[trip_reviews][excellent_label]" name="wp_travel_engine_settings[trip_reviews][excellent_label]" value="<?php echo isset( $wp_travel_engine_settings['trip_reviews']['excellent_label'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['excellent_label'] ):'Excellent';?>">
            </div>
            <div class="wpte-field wpte-text wpte-floated">
                <label class="wpte-field-label" for="wp_travel_engine_settings[trip_reviews][vgood_label]"><?php _e('Very Good Review Label','wte-trip-review');?></label>
                <input type="text" id="wp_travel_engine_settings[trip_reviews][vgood_label]" name="wp_travel_engine_settings[trip_reviews][vgood_label]" value="<?php echo isset( $wp_travel_engine_settings['trip_reviews']['vgood_label'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['vgood_label'] ):'Very Good';?>">
            </div>
            <div class="wpte-field wpte-text wpte-floated">
                <label class="wpte-field-label" for="wp_travel_engine_settings[trip_reviews][average_label]"><?php _e('Average Review Label','wte-trip-review');?></label>
                <input type="text" id="wp_travel_engine_settings[trip_reviews][average_label]" name="wp_travel_engine_settings[trip_reviews][average_label]" value="<?php echo isset( $wp_travel_engine_settings['trip_reviews']['average_label'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['average_label'] ):'Average';?>">
            </div>
            <div class="wpte-field wpte-text wpte-floated">
                <label class="wpte-field-label" for="wp_travel_engine_settings[trip_reviews][poor_label]"><?php _e('Poor Review Label','wte-trip-review');?></label>
                <input type="text" id="wp_travel_engine_settings[trip_reviews][poor_label]" name="wp_travel_engine_settings[trip_reviews][poor_label]" value="<?php echo isset( $wp_travel_engine_settings['trip_reviews']['poor_label'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['poor_label'] ):'Poor';?>">
            </div>
            <div class="wpte-field wpte-text wpte-floated">
                <label class="wpte-field-label" for="wp_travel_engine_settings[trip_reviews][terrible_label]"><?php _e('Terrible Review Label','wte-trip-review');?></label>
                <input type="text" id="wp_travel_engine_settings[trip_reviews][terrible_label]" name="wp_travel_engine_settings[trip_reviews][terrible_label]" value="<?php echo isset( $wp_travel_engine_settings['trip_reviews']['terrible_label'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['terrible_label'] ):'Terrible';?>">
            </div>

    		<div class="wpte-title-wrap">
                <h3 class="wpte-title"><?php _e('Emoticons Setting','wte-trip-review');?></h3>
            </div>
            <div class="wpte-field wpte-checkbox advance-checkbox">
                <label class="wpte-field-label" for="wp_travel_engine_settings[trip_reviews][show_emoticons]"><?php _e('Show Trip Review Emoticons','wte-trip-review');?></label>
                <div class="wpte-checkbox-wrap">
                   <input type="checkbox" id="wp_travel_engine_settings[trip_reviews][show_emoticons]" name="wp_travel_engine_settings[trip_reviews][show_emoticons]" value="1" <?php echo checked('1', $emoticon_option); ?>>
                    <label for="wp_travel_engine_settings[trip_reviews][show_emoticons]" class="checkbox-label"></label>
                </div>
                <span class="wpte-tooltip"><?php _e('Default: Hidden. If checked, emoticons will be shown for average review and overall overage review section.','wte-trip-review');?></span>
            </div>

    		<div class="wpte-title-wrap">
                <h3 class="wpte-title"><?php _e('Show/Hide Fields','wte-trip-review');?></h3>
            </div>
    		<div class="wpte-field wpte-checkbox advance-checkbox">
                <label class="wpte-field-label" for="wp_travel_engine_settings[trip_reviews][hide_experience_date_field]"><?php _e('Hide Experience Date Field','wte-trip-review');?></label>
                <div class="wpte-checkbox-wrap">
                  <input type="checkbox" id="wp_travel_engine_settings[trip_reviews][hide_experience_date_field]" name="wp_travel_engine_settings[trip_reviews][hide_experience_date_field]" value="1" <?php echo checked('1', $hide_experience_date_field); ?>>
                <label for="wp_travel_engine_settings[trip_reviews][hide_experience_date_field]" class="checkbox-label"></label>
                </div>
                <span class="wpte-tooltip"><?php _e('Default: Shown. If checked, Experience Date field will be hidden from form and won\'t be shown in review section.','wte-trip-review');?></span>
            </div>
    		<div class="wpte-field wpte-checkbox advance-checkbox">
                <label class="wpte-field-label" for="wp_travel_engine_settings[trip_reviews][hide_image_upload_field]"><?php _e('Hide Gallery Image','wte-trip-review');?></label>
                <div class="wpte-checkbox-wrap">
                    <input type="checkbox" id="wp_travel_engine_settings[trip_reviews][hide_image_upload_field]" name="wp_travel_engine_settings[trip_reviews][hide_image_upload_field]" value="1" <?php echo checked('1', $hide_image_upload_field); ?>>
                <label for="wp_travel_engine_settings[trip_reviews][hide_image_upload_field]" class="checkbox-label"></label>
                </div>
                <span class="wpte-tooltip"><?php _e('Default: Shown. If checked, Gallery Image field will be hidden from form and won\'t be shown in review section.','wte-trip-review');?></span>
            </div>
    		<div class="wpte-field wpte-checkbox advance-checkbox">
                <label class="wpte-field-label" for="wp_travel_engine_settings[trip_reviews][hide_reviewed_tour_field]"><?php _e('Hide Reviewed Tour','wte-trip-review');?></label>
                <div class="wpte-checkbox-wrap">
                    <input type="checkbox" id="wp_travel_engine_settings[trip_reviews][hide_reviewed_tour_field]" name="wp_travel_engine_settings[trip_reviews][hide_reviewed_tour_field]" value="1" <?php echo checked('1', $hide_reviewed_tour_field); ?>>
                <label for="wp_travel_engine_settings[trip_reviews][hide_reviewed_tour_field]" class="checkbox-label"></label>
                </div>
                <span class="wpte-tooltip"><?php _e('Default: Shown. If checked, Reviewed Tour Link won\'t be shown in review section.','wte-trip-review');?></span>
            </div>
    		<div class="wpte-field wpte-checkbox advance-checkbox">
                <label class="wpte-field-label" for="wp_travel_engine_settings[trip_reviews][hide_client_location_field]"><?php _e('Hide Client Location','wte-trip-review');?></label>
                <div class="wpte-checkbox-wrap">
                   <input type="checkbox" id="wp_travel_engine_settings[trip_reviews][hide_client_location_field]" name="wp_travel_engine_settings[trip_reviews][hide_client_location_field]" value="1" <?php echo checked('1', $hide_client_location_field); ?>>
                <label for="wp_travel_engine_settings[trip_reviews][hide_client_location_field]" class="checkbox-label"></label>
                </div>
                <span class="wpte-tooltip"><?php _e('Default: Shown. If checked, Client Location field will be hidden from form and won\'t be shown in review section.','wte-trip-review');?></span>
            </div>
        </div>
    </div>