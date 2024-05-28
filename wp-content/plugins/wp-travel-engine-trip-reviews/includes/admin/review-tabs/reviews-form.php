<?php
/**
 * Reviews form.
 */
?>
<div class="trip-review" id="trip-review-form">
            <div class="success" id="wte-trip-review-success"
                style="background: rgb(176, 253, 197);padding: 10px;border-radius: 3px;width: 43%;display: none;"><span
                    class="dashicons dashicons-no-alt"></span></div>
            <p><b><?php _e('You can add trip reviews from the below form.', 'wte-trip-review'); ?></b></p>
            <form action="#" class="wpte-comment-form" name="wpte_comment_form">
                <?php wp_nonce_field('admin_comment_update', 'admin_comment_update', false); ?>
                <div class="wpte-field  wpte-radio wpte-floated" id="wte-trip-review-template">
                        <label class="wpte-field-label" for="wte-trip-review-rating"><?php _e('Rating:', 'wte-trip-review'); ?><span
                                class="required">*</span></label>
                        <span class="commentwte_trip_review_ratingbox">
                            <div id="wte-trip-review-rating"></div>
                            <input id="wte-trip-review-rating-value" type="hidden" name="stars">
                            <!-- <fieldset class="wte-trip-review-rating">
                                <input type="radio" name="stars" id="5_stars" value="5">
                                <label class="stars" for="5_stars">5 stars</label>
                                <input type="radio" name="stars" id="4_stars" value="4">
                                <label class="stars" for="4_stars">4 stars</label>
                                <input type="radio" name="stars" id="3_stars" value="3">
                                <label class="stars" for="3_stars">3 stars</label>
                                <input type="radio" name="stars" id="2_stars" value="2">
                                <label class="stars" for="2_stars">2 stars</label>
                                <input type="radio" name="stars" id="1_stars" value="1">
                                <label class="stars" for="1_stars">1 star</label>
                            </fieldset> -->
                        </span>
                </div>

                <div class="wpte-field wpte-text wpte-floated">
                    <label class="wpte-field-label"for="title"><?php _e('Review Title:', 'wte-trip-review'); ?><span
                            class="required">*</span></label>
                    <input type="text" id="title" name="title">
                </div>

                <div class="wpte-field wpte-text wpte-floated">
                    <label class="wpte-field-label" for="review-date"><?php _e('Review Date:', 'wte-trip-review'); ?><span
                            class="required">*</span></label>
                    <input type="text" id="review-date" name="user_date">
                </div>

                <div class="wpte-field wpte-textarea wpte-floated">
                    <label class="wpte-field-label" for="msg"><?php _e('Review:', 'wte-trip-review'); ?><span class="required">*</span></label>
                    <textarea id="msg" name="user_message"></textarea>
                </div>

                <div class="wpte-field wpte-text wpte-floated">
                    <label class="wpte-field-label" for="name"><?php _e('Name:', 'wte-trip-review'); ?><span class="required">*</span></label>
                    <input type="text" id="name" name="user_name">
                </div>

                <div class="wpte-field wpte-text wpte-floated">
                    <label class="wpte-field-label" for="mail"><?php _e('E-mail:', 'wte-trip-review'); ?></label>
                    <input type="email" id="mail" name="user_mail">
                </div>
                <div class="wpte-field wpte-select wpte-floated" style="margin-bottom: 20px;">
                    <?php wpte_review_trip_post_select('select-trip', 'trip', $selected = 0); ?>
                </div>
                <div class="wpte-field wpte-text wpte-floated">
                    <label class="wpte-field-label" for="date-of-experience"><?php _e('Date Of Experience', 'wte-trip-review'); ?></label>
                    <input id="date-of-experience" name="experience_date" type="text" autocomplete="off" />
                </div>
                <div class="wpte-field wpte-select wpte-floated">
                    <label class="wpte-field-label" for="client-location"><?php _e('Client Country', 'wte-trip-review'); ?></label>
                    <select class="wpte-enhanced-select" name="client_location" id="client-location">
                        <?php
                        $wte = new Wp_Travel_Engine_Functions();
                        $country_options = $wte->wp_travel_engine_country_list();
                        if (!empty($country_options)) {
                            echo '<option value="">' . __('Please Select Country', 'wte-trip-review') . '</option>';
                            foreach ($country_options as $key => $val) {
                                echo '<option value="' . $val . '">' . $val . '</option>';
                            }
                        } else {
                            echo '<option disabled="disabled">' . __('No Country List Found', 'wte-trip-review') . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="wpte-field image-upload-wrap wpte-image">
                    <div class="image-upload">
                        <label class="wpte-field-label" for="image_url"><?php _e('User Image', 'wte-trip-review'); ?></label>
                        <input type="button" id="upload-btn" class="button button-secondary"
                            value="<?php _e('Upload Photo', 'wte-trip-review'); ?>">
                    </div>
                    <div class="commenter-photo"></div>
                    <input type="button" id="remove-btn" class="button button-secondary"
                        value="<?php _e('Remove Photo', 'wte-trip-review'); ?>">
                    <input type="hidden" id="image_url" name="upload" value="">
                </div>
                <div class="wpte-field">
                    <label class="wpte-field-label" for="gallery-images"><?php _e('Gallery Images', 'wte-trip-review'); ?></label>
                    <a class='trip-review-img-gallery-add button button-secondary' href='#'
                    data-uploader-title='<?php _e('Add Image to Gallery', 'wte-trip-review'); ?>'
                    data-uploader-button-text='<?php _e('Add Image(s)', 'wte-trip-review'); ?>'
                    style="max-height: 35px;">
                        <i class="fas fa-plus"></i> <?php _e('Add Image(s)', 'wte-trip-review'); ?>
                    </a>
                    <div>
                        <ul class="wte-trip-review-gallery">
                        </ul>
                        <input type="hidden" name="gallery_max_count" value="0" class="wte-trip-review-max-img-count" />
                    </div>
                </div>
                <div id="loader" style="display: none;">
                    <div class="table">
                        <div class="table-row">
                            <div class="table-cell">
                                <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <?php
                    $other_attributes = array('id' => 'wpte-button-id');
                    submit_button(__('Add Review', 'wte-trip-review'), 'primary', 'submit', true, $other_attributes);
                    ?>
                </div>
            </form>
            <script>
                jQuery(document).ready(function($) {
                $('body').on('click', '.success .dashicons-no-alt', function(e) {
                $(this).parent().fadeOut('slow', function() {
                $(this).remove();
                });
                });
                });
            </script>
            <style>
                .wpte-comment-form .wpte-field input,
                .wpte-comment-form .wpte-field textarea,
                .wpte-comment-form .wpte-field select {
                    border: 1px solid rgba(85, 93, 102, 0.2);
                }
            </style>
        </div>