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
  "taxonomy" => "product_cat",
  "hide_empty" => 0,
  "paged" => $paged
];

$args = apply_filters('udesly/terms/categories', $args);
        
        $query = new WP_Term_Query($args);
?>
<div class="w-dyn-list" udy-collection="category">
                      <?php if ( ! empty($query->terms) ) : ?><div role="list" class="footer-categories w-dyn-items">
                        <?php foreach ($query->get_terms() as $term) : ?><div role="listitem" class="w-dyn-item">
                          <a href="<?php echo get_term_link($term) ?>" class="footer-link w-inline-block">
                            <div class="button-text-wrapper">
                              <div class="button-text"><?php echo $term->name; ?></div>
                            </div>
                            <div class="arrow-clip"><img src="<?php echo udesly_get_image(_u('in21b2a3e2', 'img','global'))->src ?>" loading="lazy" alt="<?php echo udesly_get_image(_u('in21b2a3e2', 'img','global'))->alt ?>" class="arrow-icon" data-img="in21b2a3e2" srcset="<?php echo udesly_get_image(_u('in21b2a3e2', 'img','global'))->srcset ?>"></div>
                          </a>
                        </div><?php endforeach ?>
                      </div>
                      <?php else : ?><div class="w-dyn-empty">
                        <div>No items found.</div>
                      </div><?php endif; ?>
                    </div>
 