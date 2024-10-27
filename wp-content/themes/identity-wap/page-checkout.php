<?php


$args = [
    'wfPage' => '6614972bac3437b7aa2582ad',
    'body' => 'body',
    'head' => 'head/page-about',
];   

if (function_exists('udesly_set_frontend_editor_data')) {
    udesly_set_frontend_editor_data('page-checkout');
}
     
get_header('', $args);

/* Start the Loop */
while ( have_posts() ) :
    the_post();
    udesly_get_content_template( 'page-checkout' );
endwhile;
// End of the loop.

$args = [
  'footer' => 'footer/page-checkout',
];  

if (function_exists('udesly_output_frontend_editor_data')) {
     udesly_output_frontend_editor_data('page-checkout');
}

get_footer('', $args);
