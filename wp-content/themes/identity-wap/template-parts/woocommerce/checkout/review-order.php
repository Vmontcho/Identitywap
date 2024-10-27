<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
?>
<div class="w-commerce-commercecheckoutordersummarywrapper order-summary woocommerce-checkout-review-order-table">
                      <fieldset class="w-commerce-commercecheckoutblockcontent block-content">
                        <div class="w-commerce-commercecheckoutsummarylineitem">
                          <div class="summary-text">Sous total</div>
                          <div class="summary-text dark"><?php wc_cart_totals_subtotal_html(); ?></div>
                        </div>
                        <script type="text/x-wf-template" id="wf-template-61a7f3f99c97ebc4ce4a9a270000000000a0">%3Cdiv%20class%3D%22w-commerce-commercecheckoutordersummaryextraitemslistitem%22%3E%3Cdiv%20bind%3D%2261a7f3f99c97ebc4ce4a9a270000000000a2%22%20class%3D%22summary-text%22%3E%3C%2Fdiv%3E%3Cdiv%20bind%3D%2261a7f3f99c97ebc4ce4a9a270000000000a3%22%20class%3D%22summary-text%20dark%22%3E%3C%2Fdiv%3E%3C%2Fdiv%3E</script>
                        <div class="w-commerce-commercecheckoutordersummaryextraitemslist" data-wf-collection="database.commerceOrder.extraItems" data-wf-template-id="wf-template-61a7f3f99c97ebc4ce4a9a270000000000a0">
                          <?php foreach (udesly_wc_get_order_review_extra_items() as $item) : ?><div class="w-commerce-commercecheckoutordersummaryextraitemslistitem">
                            <div class="summary-text"><?php echo $item->name; ?></div>
                            <div class="summary-text dark"><?php echo $item->price; ?></div>
                          </div><?php endforeach ?>
                        </div>
                        <div class="w-commerce-commercecheckoutsummarylineitem">
                          <div class="summary-text">Total</div>
                          <div class="w-commerce-commercecheckoutsummarytotal summary-text dark-bold"><?php wc_cart_totals_order_total_html(); ?></div>
                        </div>
                      </fieldset>
                    </div>
 