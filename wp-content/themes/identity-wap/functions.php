<?php

    function udesly_theme_utils_get_term_id_by_slug( $slug, $type ) {
        $term = get_term_by("slug", $slug, $type);

        if ($term) {
            return $term->term_id;
        }
        return 0;
    }

    function udesly_theme_utils_get_post_id_by_slug( $slug, $type ) {
        $post = get_page_by_path($slug, OBJECT, $type);

        if ($post) {
            return $post->ID;
        }
        return 0;
    }

        
    function udesly_identity_wap_setup() {
        
/*
 * Switch default core markup for search form, comment form, and comments
 * to output valid HTML5.
 */
        add_theme_support(
            'html5',
                array(
                    'comment-form',
                    'comment-list',
                    'gallery',
                    'caption',
                    'style',
                    'script',
                    'navigation-widgets',
                )
        );
        
        add_theme_support('woocommerce');

/**
 * Add support for core custom logo.
 *
 * @link https://codex.wordpress.org/Theme_Logo
 */
        $logo_width  = 300;
        $logo_height = 100;

        add_theme_support(
            'custom-logo',
                array(
                    'height'               => $logo_height,
                    'width'                => $logo_width,
                    'flex-width'           => true,
                    'flex-height'          => true,
                    'unlink-homepage-logo' => true,
                )
        );

        add_theme_support( 'title-tag' );
        
        add_theme_support( 'menus' );

        // Add theme support for selective refresh for widgets.
        add_theme_support( 'customize-selective-refresh-widgets' );

        // Add support for Block Styles.
        add_theme_support( 'wp-block-styles' );

        // Add support for full and wide align images.
        add_theme_support( 'align-wide' );

        // Add support for editor styles.
        add_theme_support( 'editor-styles' );
        
        // Add support for responsive embedded content.
        add_theme_support( 'responsive-embeds' );
         
        add_theme_support( 'post-thumbnails' ); 
    }
    
    add_action( 'after_setup_theme', 'udesly_identity_wap_setup' );

    add_action( 'admin_notices', function() {
        if (function_exists("udesly_define_post_type")) {
            return;
        }
        $class = 'notice notice-error';
        $message = 'The theme will not work properly without the Udesly App plugin installed!';
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
    });
    
    
    
    require_once get_template_directory() . '/tgm-plugin/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'udesly_register_required_plugins' );

function udesly_register_required_plugins() {

    $plugins = array(

        array(
            'name'      => 'Udesly App',
            'slug'      => 'udesly-wp-app',
            'source'    => 'https://github.com/udesly-adapter/udesly-wp-app/archive/master.zip',
        ),
        array(
			'name'      => 'WooCommerce',
			'slug'      => 'woocommerce',
			'required'  => true,
		),
    );

    /*
     * Array of configuration settings. Amend each line as needed.
     *
     * TGMPA will start providing localized text strings soon. If you already have translations of our standard
     * strings available, please help us make TGMPA even better by giving us access to these translations or by
     * sending in a pull-request with .po file(s) with the translations.
     *
     * Only uncomment the strings in the config array if you want to customize the strings.
     */
    $config = array(
        'id'           => 'udesly',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug'  => 'themes.php',            // Parent menu slug.
        'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
    );

    tgmpa( $plugins, $config );
}




   

    function define_post_types_for_identity_wap() {

        if (!function_exists('udesly_define_post_type')) {
            return;
        }
        
        udesly_define_post_type("product", [
        "labels" => [
            "name" => __("Products"),
            "singular_name" => __("Product"),
        ],
        "rewrite" => [
            "name" => __("product"),
        ],
    ]);
        
        
    
    }


   
    
    add_action('init', 'define_post_types_for_identity_wap');
    

 function udesly_theme_set_images_items_lightbox_script($id, $field, $type) {
	$images = udesly_get_custom_post_field( $id, $field, $type );
	
	$items_to_json = [];
	
	foreach ($images as $imageItem) {
		$image = $imageItem["image"];
		$items_to_json[] = [
			"type" => "image",
			"url" => $image->src,
			"caption" => $image->caption
		];
	}
		
	echo json_encode($items_to_json);
}
      

        
        add_action('acf/init', function() {

            if (!function_exists('udesly_custom_field_text')) {
                return;
            }
        
            udesly_register_custom_fields_for_taxonomy('product_cat', [
            udesly_custom_field_text([
            "name" => "page-title", 
            "label" => "Page title", 
            "instructions" => "", 
            ]),    
udesly_custom_field_text([
            "name" => "short-description", 
            "label" => "Short description", 
            "instructions" => "", 
            ]),    
udesly_custom_field_image([
            "name" => "featured-image", 
            "label" => "Featured image", 
            "instructions" => "", 
            ])
        ]);        
udesly_register_custom_fields_for_post_type('product',[
         udesly_custom_field_image([
            "name" => "card-image-one", 
            "label" => "Product card Image one", 
            "instructions" => "First image on the product card", 
            ]),   
udesly_custom_field_image([
            "name" => "card-image-two", 
            "label" => "Product card Image two", 
            "instructions" => "Second image on the product card to show up on hover", 
            ]),   
udesly_custom_field_image([
            "name" => "card-image-three", 
            "label" => "Product card Image three", 
            "instructions" => "Third image on the product card to show up on hover", 
            ]),   
udesly_custom_field_text([
            "name" => "color", 
            "label" => "Color", 
            "instructions" => "", 
            ]),   
udesly_custom_field_image([
            "name" => "size-guide-chart", 
            "label" => "Size guide/chart", 
            "instructions" => "", 
            ]),   
udesly_custom_field_set([
            "name" => "product-page-images", 
            "label" => "Product page images", 
            "instructions" => "Recommended square images ",
            ]),   
udesly_custom_field_textarea([
            "name" => "details-description", 
            "label" => "Details description", 
            "instructions" => "", 
            ]),   
udesly_custom_field_set([
            "name" => "customer-images", 
            "label" => "Detail images", 
            "instructions" => "Recommended three - five images",
            ]),   
udesly_custom_field_textarea([
            "name" => "fabric-description", 
            "label" => "Fabric description", 
            "instructions" => "", 
            ]),   
udesly_custom_field_image([
            "name" => "product-fabric-image", 
            "label" => "Fabric image", 
            "instructions" => "", 
            ]),   
udesly_custom_field_set([
            "name" => "customer-product-images", 
            "label" => "Customer product images", 
            "instructions" => "",
            ]),   
udesly_custom_field_checkbox([
            "name" => "new-product",
            "label" => "New product",
            "instructions" => "Add a \"New\" tag to the product card"
            ]),   
udesly_custom_field_checkbox([
            "name" => "popular",
            "label" => "Popular",
            "instructions" => "Show on the home page under \"POPULAR\""
            ]),   
udesly_custom_field_checkbox([
            "name" => "featured",
            "label" => "Featured",
            "instructions" => "Show on the home page under \"FEATURED\""
            ]),   
udesly_custom_field_text([
            "name" => "discount-percentage", 
            "label" => "Discount Percentage", 
            "instructions" => "Example: 30% off", 
            ])
    ]);
        
        });
        