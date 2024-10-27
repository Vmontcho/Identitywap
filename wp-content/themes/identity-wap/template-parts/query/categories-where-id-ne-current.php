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
  "taxonomy" => "product_cat",
  "hide_empty" => 0,
  "exclude" => [
    get_queried_object_id()
  ],
  "paged" => $paged
];

$args = apply_filters('udesly/terms/categories-where-id-ne-current', $args);
        
        $query = new WP_Term_Query($args);
?>
<div class="w-dyn-list" udy-collection="category">
                    <?php if ( ! empty($query->terms) ) : ?><div role="list" class="category-item-wrapper w-dyn-items">
                      <?php foreach ($query->get_terms() as $term) : ?><div role="listitem" class="w-dyn-item">
                        <a href="<?php echo get_term_link($term) ?>" class="category-item w-inline-block">
                          <div class="button-text-wrapper">
                            <div class="button-text"><?php echo $term->name; ?></div>
                          </div>
                        </a>
                      </div><?php endforeach ?>
                    </div>
                    <?php else : ?><div class="w-dyn-empty">
                      <div>No items found.</div>
                    </div><?php endif; ?>
                  </div>
 