<?php

ob_start();
$wp_travel_engine_settings = get_option('wp_travel_engine_settings');
$comment_datas = $this->pull_all_comments_data();
$review_repsonse_texts = $this->overall_textlist_of_responses();
$review_emoticons_list = $this->emoticon_lists();
$emoticon_option = isset( $wp_travel_engine_settings['trip_reviews']['show_emoticons'] ) ? esc_attr( $wp_travel_engine_settings['trip_reviews']['show_emoticons'] ) : '';
remove_filter( 'the_content', 'wpautop' );
if (!empty($comment_datas)) {
?>
<div class="overall-rating-wrap">
    <div class="rating-bar-outer-wrap">
        <?php if(!empty($emoticon_option)){ ?>
            <span class="review-emoji-icon"><?php echo apply_filters("the_content", $review_emoticons_list["excellent_icon"]);?></span>
        <?php } ?>
        <span class="trip-review-response-text"><?php echo $review_repsonse_texts['excellent_label'];?></span>
        <div class="rating-bar">
            <div class="rating-bar-inner" data-percent="<?php echo $comment_datas['five_stars_percent'];?>">
                <span class="percent"><?php echo $comment_datas['five_stars'];?></span>
            </div>
        </div>
    </div>
    <div class="rating-bar-outer-wrap">
        <?php if(!empty($emoticon_option)){ ?>
            <span class="review-emoji-icon"><?php echo apply_filters('the_content', $review_emoticons_list['vgood_icon']); ?></span>
        <?php } ?>
        <span class="trip-review-response-text"><?php echo $review_repsonse_texts['vgood_label'];?></span>
        <div class="rating-bar">
            <div class="rating-bar-inner" data-percent="<?php echo $comment_datas['four_stars_percent'];?>">
                <span class="percent"><?php echo $comment_datas['four_stars'];?></span>
            </div>
        </div>
    </div>
    <div class="rating-bar-outer-wrap">
        <?php if(!empty($emoticon_option)){ ?>
            <span class="review-emoji-icon"><?php echo apply_filters('the_content', $review_emoticons_list['average_icon']);?></span>
        <?php } ?>
        <span class="trip-review-response-text"><?php echo $review_repsonse_texts['average_label'];?></span>
        <div class="rating-bar">
            <div class="rating-bar-inner" data-percent="<?php echo $comment_datas['three_stars_percent'];?>">
                <span class="percent"><?php echo $comment_datas['three_stars'];?></span>
            </div>
        </div>
    </div>
    <div class="rating-bar-outer-wrap">
        <?php if(!empty($emoticon_option)){ ?>
        <span class="review-emoji-icon"><?php echo apply_filters('the_content', $review_emoticons_list['poor_icon']);?></span>
        <?php } ?>
        <span class="trip-review-response-text"><?php echo $review_repsonse_texts['poor_label'];?></span>
        <div class="rating-bar">
            <div class="rating-bar-inner" data-percent="<?php echo $comment_datas['two_stars_percent'];?>">
                <span class="percent"><?php echo $comment_datas['two_stars'];?></span>
            </div>
        </div>
    </div>
    <div class="rating-bar-outer-wrap">
        <?php if(!empty($emoticon_option)){ ?>
        <span class="review-emoji-icon"><?php echo apply_filters('the_content', $review_emoticons_list['terrible_icon']);?></span>
        <?php } ?>
        <span class="trip-review-response-text"><?php echo $review_repsonse_texts['terrible_label'];?></span>
        <div class="rating-bar">
            <div class="rating-bar-inner" data-percent="<?php echo $comment_datas['one_stars_percent'];?>">
                <span class="percent"><?php echo $comment_datas['one_stars'];?></span>
            </div>
        </div>
    </div>
</div>
<?php }

$overall_average_template = ob_get_clean();

$overall_average_template = apply_filters('company_overall_average_custom_template',$overall_average_template);

echo $overall_average_template;

add_filter( 'the_content', 'wpautop' );