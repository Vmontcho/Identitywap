<?php


$args = [
    'wfPage' => '6614972bac3437b7aa2582b6',
    'body' => 'body',
    'head' => 'head/page-about',
];   

if (function_exists('udesly_set_frontend_editor_data')) {
    udesly_set_frontend_editor_data('thank-you');
}
     
get_header('', $args);

udesly_get_content_template( 'thank-you' );

$args = [
  'footer' => 'footer/front-page',
];  

if (function_exists('udesly_output_frontend_editor_data')) {
     udesly_output_frontend_editor_data('thank-you');
}

get_footer('', $args);
