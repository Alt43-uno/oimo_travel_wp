<?php
/**
 * Structure for Review Comment Thread
 * Default: By default, comment-thread.php file will be read by the reveiw addon.
 * You can copy and use the following codes if you want the cleaner version of the review comment thread.
 * Or else, if you need more detailed html structure of the review comment thread, please use comment-thread.php
 */
?>
<li class="wte-review-comment-id" id="wte-review-comment-<?php echo get_comment_ID(); ?>" data-id="<?php echo get_comment_ID(); ?>">
    <?php do_action('comment_avatar'); ?>
    <div class="trip-comment-content">
        <?php do_action('comment_title'); ?>
        <div class="comment-rating">
            <?php do_action('comment_meta_author'); ?>
            <?php do_action('comment_client_location'); ?>
            <?php do_action('comment_meta_date'); ?>
            <?php do_action('comment_rating'); ?>
            <?php do_action('comment_reviewed_tour'); ?>
            <?php do_action('comment_content'); ?>
            <?php do_action('comment_meta_gallery'); ?>
            <?php do_action('comment_experience_date'); ?>
        </div>
    </div>
</li>
