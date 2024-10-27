<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
?>
<?php
            if (function_exists('udesly_set_frontend_editor_data') && wp_doing_ajax()) {
              udesly_set_frontend_editor_data('front-page');
          }
?>
<?php

        if (isset($_GET['p_id'])) {
          $paged = $_GET['p_id'];
        } else {
          $paged = isset($args['paged']) ? $args['paged'] : 1;  
        }
          
        

$args = [
  "post_type" => "product",
  "posts_per_page" => 2,
  "meta_query" => [
    "relation" => "AND",
    [
      "key" => "featured",
      "value" => 1,
      "compare" => "EXISTS"
    ]
  ],
  "paged" => $paged
];

$args = apply_filters('udesly/posts/products-max-2-where-featured-eq-true', $args);
        
        $query = new WP_Query($args);
?>
<div class="w-dyn-list" udy-collection="product">
                <?php if ( $query->have_posts() ) : ?><div role="list" class="featured-product-list w-dyn-items">
                  <?php while ($query->have_posts()) : $query->the_post(); global $post, $product, $variant; ?><div role="listitem" class="w-dyn-item">
                    <div data-w-id="d332ea60-89cb-21a2-02c2-1e3ef71807ce" class="featured-wrapper">
                      <div id="w-node-d332ea60-89cb-21a2-02c2-1e3ef71807cf-aa2582a9" class="sticky-heading">
                        <div class="stacked-intro small">
                          <h1 class="product-title large"><?php the_title() ?></h1>
                          <div class="body-display small"><?php echo $product->get_short_description() ?></div>
                        </div>
                        <a href="<?php the_permalink() ?>" class="underline-link w-inline-block">
                          <div class="button-text-wrapper">
                            <div class="button-text">Voir les produits en détail</div>
                          </div>
                          <div class="link-arrow-wrapper"><img src="<?php echo udesly_get_image(_u('in21885d5f', 'img'))->src ?>" loading="lazy" alt="<?php echo udesly_get_image(_u('in21885d5f', 'img'))->alt ?>" class="arrow-icon" data-img="in21885d5f" srcset="<?php echo udesly_get_image(_u('in21885d5f', 'img'))->srcset ?>"></div>
                        </a>
                      </div>
                      <div class="featured-item-image first">
                        <div class="featured-product-image one" style="<?php echo "background-image: url('" . udesly_get_custom_post_field( $post->ID, "card-image-one", "ImageRef" )->src . "')" ?>"></div>
                      </div>
                      <div class="featured-item-image second">
                        <div class="featured-product-image two" style="<?php echo "background-image: url('" . udesly_get_custom_post_field( $post->ID, "card-image-two", "ImageRef" )->src . "')" ?>"></div>
                      </div>
                      <div class="featured-item-image third">
                        <div class="featured-product-image three" style="<?php echo "background-image: url('" . udesly_get_custom_post_field( $post->ID, "card-image-three", "ImageRef" )->src . "')" ?>"></div>
                      </div>
                    </div>
                  </div><?php endwhile; ?>
                </div>
                <?php else : ?><div class="w-dyn-empty">
                  <div>No items found.</div>
                </div><?php endif; ?>
              </div>
<?php wp_reset_postdata(); ?>
 