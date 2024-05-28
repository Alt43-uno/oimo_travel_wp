<?php
$sum = 0;
$three_stars = 0;
$two_stars = 0;
$one_stars = 0;
$i = 0;
$four_stars = 0;
$five_stars = 0;
if(!empty($comments)){
foreach ($comments as $comment) {
    $rating = get_comment_meta($comment->comment_ID, 'stars', true);
    if ($rating == 5) {
        $five_stars++;
    }
    if ($rating == 4) {
        $four_stars++;
    }
    if ($rating == 3) {
        $three_stars++;
    }
    if ($rating == 2) {
        $two_stars++;
    }
    if ($rating == 1) {
        $one_stars++;
    }
    $sum = $sum + absint($rating);
    $i++;
}
$aggregate = $sum / $i;
$aggregate = round($aggregate, 2);

$five_stars_percent = $five_stars / $i * 100;
$four_stars_percent = $four_stars / $i * 100;
$three_stars_percent = $three_stars / $i * 100;
$two_stars_percent = $two_stars / $i * 100;
$one_stars_percent = $one_stars / $i * 100;

$comment_data['aggregate'] = $aggregate;
$comment_data['i'] = $i;
$comment_data['one_stars_percent'] = $one_stars_percent;
$comment_data['two_stars_percent'] = $two_stars_percent;
$comment_data['three_stars_percent'] = $three_stars_percent;
$comment_data['four_stars_percent'] = $four_stars_percent;
$comment_data['five_stars_percent'] = $five_stars_percent;

$comment_data['one_stars'] = $one_stars;
$comment_data['two_stars'] = $two_stars;
$comment_data['three_stars'] = $three_stars;
$comment_data['four_stars'] = $four_stars;
$comment_data['five_stars'] = $five_stars;
}
return $comment_data;
