<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
?>
<?php
            if (function_exists('udesly_set_frontend_editor_data') && wp_doing_ajax()) {
              udesly_set_frontend_editor_data('taxonomy-product_cat');
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
  "posts_per_page" => 12,
  "paged" => $paged,
  "order" => "rand",
  "ordeby" => "ASC",
  "tax_query" => [
    "0" => [
      "taxonomy" => "product_cat",
      "field" => "id",
      "operator" => "IN",
      "terms" => [
        get_queried_object_id()
      ]
    ],
    "relation" => "AND"
  ]
];

$args = apply_filters('udesly/posts/products-max-12-where-category-idin-current-sorted-by-random-paginated', $args);
        
        $query = new WP_Query($args);
?>
<div id="w-node-_355f5000-32a2-9e14-df96-1cda1d318b48-aa2582b0" class="shop-collection-list w-dyn-list" udy-collection="product">
              <?php if ( $query->have_posts() ) : ?><div role="list" class="shop-grid w-dyn-items">
                <?php while ($query->have_posts()) : $query->the_post(); global $post, $product, $variant; ?><div role="listitem" class="w-dyn-item">
                  <a href="<?php the_permalink() ?>" class="product-card w-inline-block">
                    <div class="card-image-wrapper">
                      <div class="card-image large">
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
                      <div id="w-node-_355f5000-32a2-9e14-df96-1cda1d318b55-aa2582b0" class="product-card-top">
                        <div class="product-card-info">
                          <div class="product-tags">
                            <?php if (udesly_get_custom_post_field( $post->ID, "new-product", "Bool" )) : ?><div class="new-tag">
                              <div class="label-text">New</div>
                            </div><?php endif  ?>
                            <?php if (udesly_get_custom_post_field( $post->ID, "discount-percentage", "PlainText" )) : ?><div class="discount-label"><img src="<?php echo udesly_get_image(_u('i152f9a01', 'img'))->src ?>" loading="lazy" id="w-node-_355f5000-32a2-9e14-df96-1cda1d318b5c-aa2582b0" alt="<?php echo udesly_get_image(_u('i152f9a01', 'img'))->alt ?>" class="tag-icon" data-img="i152f9a01" srcset="<?php echo udesly_get_image(_u('i152f9a01', 'img'))->srcset ?>">
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
              <?php if ($query->max_num_pages > 0) : ?><div role="navigation" aria-label="List" class="w-pagination-wrapper pagination" data-query="products-max-12-where-category-idin-current-sorted-by-random-paginated" data-paged="<?php echo $paged; ?>">
                <div class="pagnation-wrapper">
                  <a href="<?php echo add_query_arg("p_id", $paged-1); ?>" aria-label="Previous Page" class="w-pagination-previous pagination-button left" <?php echo $paged == 1 ? "style=\"display: none;\"": "" ?>>
                    <div class="pagination-text"><img id="w-node-_355f5000-32a2-9e14-df96-1cda1d318b6b-aa2582b0" alt="" src="<?php echo get_template_directory_uri(); ?>/assets/images/arrow-left24x242x-7.svg?v=1719838006" loading="lazy" class="pagination-arrow">
                      <div class="button-text-wrapper">
                        <div class="button-text">Prev</div>
                      </div>
                    </div>
                  </a>
                  <a href="<?php echo add_query_arg("p_id", $paged+1); ?>" aria-label="Next Page" class="w-pagination-next pagination-button right" <?php echo $paged == $query->max_num_pages ? "style=\"display: none;\"": "" ?>>
                    <div class="pagination-text">
                      <div class="button-text-wrapper">
                        <div class="button-text">Next</div>
                      </div><img alt="" loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/arrow-right24x242x-8.svg?v=1719838006" class="pagination-arrow">
                    </div>
                  </a>
                </div>
              </div><?php endif  ?>
            </div>
<?php wp_reset_postdata(); ?>
 