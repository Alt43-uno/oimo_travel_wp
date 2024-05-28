<?php
$final_content = trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($content))))));
$sku = apply_filters( 'wte_trip_schema_sku', "WTE-$post->ID", $post->ID );

 echo '<script type="application/ld+json">';
 echo '{
     "@context": "http://schema.org",
     "@type": "Review",
     "author": {
         "@type": "Person",
         "name": "' . get_the_author_meta('user_nicename') . '"
     },
     "itemReviewed": {
         "@type": "Product",
         "image": "' . get_the_post_thumbnail_url($post->ID) . '",
         "name": "' . get_the_title($post->ID) . '",
         "url": "' . get_permalink($post->ID) . '",
         "description": "' . $final_content . '",
         "sku": "' . $sku .'",
         "brand": {
             "@type": "Thing",
             "name": "' . get_bloginfo("name") . '"
         },
         "offers": {
             "@type": "Offer",
             "price": ' . $actual_price . ',
             "priceCurrency": "' . $priceCurrency . '",
             "availability": "http://schema.org/InStock",
             "url": "' . get_permalink($post->ID) . '"
         },
         "aggregateRating": {
             "@type": "AggregateRating",
             "ratingValue": ' . $comment_datas['aggregate'] . ',
             "bestRating": 5,
             "reviewCount": ' . $comment_datas['i'] . '
         }
     },
     "reviewRating": {
         "@type": "Rating",
         "ratingValue": ' . $comment_datas['aggregate'] . ',
         "bestRating": 5,
         "worstRating": 1
     }
 }';

 echo '</script>';