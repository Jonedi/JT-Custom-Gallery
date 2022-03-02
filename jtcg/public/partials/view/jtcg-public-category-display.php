<?php

$output = '
    <div class="jtcg-row">
        <div class="categoryTemplate">';

 $val_columns       = $settings['columns'];
 $category          = $settings['category'];
 $postPerPage       = $settings['postPerPage'];
 $order             = $settings['order'];
 $orderby           = $settings['orderby'];

 switch( $val_columns ) {
     case 2:
         $column = 'm6';
         break;
     case 3:
         $column = 'm4';
         break;
     case 4:
         $column = 'm3';
         break;

 }

 if( ! is_null( $category ) && $category != '' ) {

     $args_query = [
        'cat'               => $category,
        'posts_per_page'    => $postPerPage,
        'order'             => $order,
        'orderby'           => $orderby
    ];

    $query = new WP_Query( $args_query );

     if( $query->have_posts() ) {

         while( $query->have_posts() ) {

             $query->the_post();

             $title     = get_the_title();
             $excerpt   = get_the_excerpt();
             $link      = get_the_permalink();
             $time      = get_the_time( 'F j Y' );

             if( has_post_thumbnail() ) {

                $src = get_the_post_thumbnail_url(
                    get_the_ID(),
                    'medium'
                );

            } else {
                $src = '';
            }

             $output .= "
                <div class='jtcg-carditem col s12 $column'>
                    <div class='card'>
                        <div class='card-image'>
                            <img src='$src' alt='$title'>
                            <span class='card-title'>$title</span>
                            <a target='_blank' href='$link' class='btn-floating halfway-fab waves-effect waves-light red'><i class='material-icons'>link</i></a>
                        </div>
                        <div class='card-content'>$excerpt</p>
                        </div>
                    </div>
                </div>";

         }

     }

     wp_reset_postdata();
 }

$output .= '</div>
    </div>';
    