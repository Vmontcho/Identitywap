<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
?>
<?php
            if (function_exists('udesly_set_frontend_editor_data') && wp_doing_ajax()) {
              udesly_set_frontend_editor_data('single-product');
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
  "posts_per_page" => 3,
  "order" => "rand",
  "ordeby" => "ASC",
  "post__not_in" => [
    get_the_ID()
  ],
  "paged" => $paged
];

$args = apply_filters('udesly/posts/products-max-3-where-id-ne-current-sorted-by-random', $args);
        
        $query = new WP_Query($args);
?>
<div id="w-node-ffa86c75-f896-198a-f483-766bd5ccfff7-aa2582b1" class="w-dyn-list" udy-collection="product">
                  <?php if ( $query->have_posts() ) : ?><div role="list" class="product-grid w-dyn-items">
                    <?php while ($query->have_posts()) : $query->the_post(); global $post, $product, $variant; ?><div role="listitem" class="large-collection-item w-dyn-item">
                      <a href="<?php the_permalink() ?>" class="product-card w-inline-block">
                        <div class="card-image-wrapper">
                          <div class="card-image extra-large">
                            <div class="background-product">
                              <div class="image-card one" style="<?php echo "background-image: url('" . udesly_get_custom_post_field( $post->ID, "card-image-one", "ImageRef" )->src . "')" ?>"></div>
                              <div class="image-card two" style="<?php echo "background-image: url('" . udesly_get_custom_post_field( $post->ID, "card-image-two", "ImageRef" )->src . "')" ?>"></div>
                              <?php if (udesly_get_custom_post_field( $post->ID, "card-image-three", "Bool" )) : ?><div class="image-card three" style="<?php echo "background-image: url('" . udesly_get_custom_post_field( $post->ID, "card-image-three", "ImageRef" )->src . "')" ?>"></div><?php endif  ?>
                            </div>
                            <div class="image-indicator">
                              <div class="indicator-fill"></div>
                            </div>
                          </div>
                        </div>
                        <div class="card-info">
                          <div id="w-node-ffa86c75-f896-198a-f483-766bd5cd0004-aa2582b1" class="product-card-top">
                            <div class="product-card-info">
                              <div class="product-tags">
                                <?php if (udesly_get_custom_post_field( $post->ID, "new-product", "Bool" )) : ?><div class="new-tag">
                                  <div class="label-text">New</div>
                                </div><?php endif  ?>
                                <?php if (udesly_get_custom_post_field( $post->ID, "discount-percentage", "PlainText" )) : ?><div class="discount-label"><img src="<?php echo udesly_get_image(_u('i152f9a01', 'img'))->src ?>" loading="lazy" id="w-node-ffa86c75-f896-198a-f483-766bd5cd000b-aa2582b1" alt="<?php echo udesly_get_image(_u('i152f9a01', 'img'))->alt ?>" class="tag-icon" data-img="i152f9a01" srcset="<?php echo udesly_get_image(_u('i152f9a01', 'img'))->srcset ?>">
                                  <div class="label-text discount"><?php echo udesly_get_custom_post_field( $post->ID, "discount-percentage", "PlainText" ) ?></div>
                                </div><?php endif  ?>
                              </div>
                              <div class="stacked-product-title">
                                <h3 class="product-title"><?php the_title() ?></h3>
                                <div class="subtitle small"><?php echo udesly_get_custom_post_field( $post->ID, "color", "PlainText" ) ?></div>
                              </div>
                            </div>
                            <div class="dynamic-price">
                              <div data-commerce-type="variation-price" class="price-text" data-variation-prop-type="CommercePrice" data-variation-prop="display_price_html"><?php echo udesly_get_price() ?></div>
                              <div class="discount-text" data-variation-prop-type="CommercePrice" data-variation-prop="display_regular_price_html"><?php echo udesly_get_compare_at_price() ?></div>
                            </div>
                          </div>
                        </div>
                      </a>
                    </div><?php endwhile; ?>
                  </div>
                  <?php else : ?><div class="w-dyn-empty">
                    <div>No items found.</div>
                  </div><?php endif; ?>
                </div>
<?php wp_reset_postdata(); ?>
 