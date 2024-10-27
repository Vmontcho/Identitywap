<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
?>
<?php global $wp;

        if ( isset($wp->query_vars['order-received']) ) {
            $order_id = absint($wp->query_vars['order-received']); // The order ID
            $order    = wc_get_order( $order_id ); // The WC_Order object
        } ?>
        <div id="w-node-_61a7f3f99c97eb119a4a9a2dan-aa2582b6" data-node-type="commerce-order-confirmation-wrapper" data-wf-order-query="" data-wf-page-link-href-prefix="" class="w-commerce-commerceorderconfirmationcontainer checkout-form">
            <div class="checkout-grid">
              <div class="w-commerce-commercelayoutmain checkout-left">
                <div class="w-commerce-commercecheckoutcustomerinfosummarywrapper checkout-component">
                  <div class="w-commerce-commercecheckoutsummaryblockheader block-header">
                    <h4>Informations du client</h4>
                  </div>
                  <fieldset class="w-commerce-commercecheckoutblockcontent block-content">
                    <div class="w-commerce-commercecheckoutrow">
                      <div class="w-commerce-commercecheckoutcolumn">
                        <div class="w-commerce-commercecheckoutsummaryitem confirmation-block"><label class="w-commerce-commercecheckoutsummarylabel field-label">Email</label>
                          <div><?php echo $order->get_billing_email() ?></div>
                        </div>
                      </div>
                      <div class="w-commerce-commercecheckoutcolumn">
                        <div class="w-commerce-commercecheckoutsummaryitem confirmation-block"><label class="w-commerce-commercecheckoutsummarylabel field-label">Adresse de livraison</label>
                          <div><?php echo $order->get_formatted_shipping_full_name() ?></div>
                          <div><?php echo $order->get_shipping_address_1() ?></div>
                          <div><?php echo $order->get_shipping_address_2() ?></div>
                          <div class="w-commerce-commercecheckoutsummaryflexboxdiv">
                            <div class="w-commerce-commercecheckoutsummarytextspacingondiv"><?php echo $order->get_shipping_city() ?></div>
                            <div class="w-commerce-commercecheckoutsummarytextspacingondiv"><?php echo $order->get_shipping_city() ?></div>
                            <div class="w-commerce-commercecheckoutsummarytextspacingondiv"><?php echo $order->get_shipping_postcode() ?></div>
                          </div>
                          <div><?php echo $order->get_shipping_country() ?></div>
                        </div>
                      </div>
                    </div>
                  </fieldset>
                </div>
                <?php $shipping_methods = udesly_wc_get_shipping_methods($order); ?><?php if (count($shipping_methods) > 0) : ?><div class="w-commerce-commercecheckoutshippingsummarywrapper checkout-component">
                  <div class="w-commerce-commercecheckoutsummaryblockheader block-header">
                    <h4>Méthode de livraison</h4>
                  </div>
                  <fieldset class="w-commerce-commercecheckoutblockcontent block-content">
                    <div class="w-commerce-commercecheckoutrow">
                      <div class="w-commerce-commercecheckoutcolumn">
                        <?php foreach ($shipping_methods as $shipping_method) : ?><div class="w-commerce-commercecheckoutsummaryitem confirmation-block">
                          <div><?php echo $shipping_method->name ?></div>
                          <div><?php echo $shipping_method->price ?></div>
                        </div><?php endforeach ?>
                      </div>
                    </div>
                  </fieldset>
                </div><?php endif  ?>
                <div class="w-commerce-commercecheckoutpaymentsummarywrapper checkout-component">
                  <div class="w-commerce-commercecheckoutsummaryblockheader block-header">
                    <h4>Informations de paiements</h4>
                  </div>
                  <fieldset class="w-commerce-commercecheckoutblockcontent block-content">
                    <div class="w-commerce-commercecheckoutrow">
                      <div class="w-commerce-commercecheckoutcolumn">
                        <div class="w-commerce-commercecheckoutsummaryitem confirmation-block"><label class="w-commerce-commercecheckoutsummarylabel field-label">Infos de paiement</label>
                          <div class="w-commerce-commercecheckoutsummaryflexboxdiv">
                            <div class="w-commerce-commercecheckoutsummarytextspacingondiv"><?php echo $order->get_payment_method_title() ?></div>
                            <div class="w-commerce-commercecheckoutsummarytextspacingondiv"><?php echo ''; ?></div>
                          </div>
                          <div class="w-commerce-commercecheckoutsummaryflexboxdiv" style="display: none;">
                            
                            <div> / </div>
                            
                          </div>
                        </div>
                      </div>
                      <div class="w-commerce-commercecheckoutcolumn">
                        <div class="w-commerce-commercecheckoutsummaryitem confirmation-block"><label class="w-commerce-commercecheckoutsummarylabel field-label">Adresse de facturation</label>
                          <div><?php echo $order->get_formatted_billing_full_name() ?></div>
                          <div><?php echo $order->get_billing_address_1() ?></div>
                          <div><?php echo $order->get_billing_address_2() ?></div>
                          <div class="w-commerce-commercecheckoutsummaryflexboxdiv">
                            <div class="w-commerce-commercecheckoutsummarytextspacingondiv"><?php echo $order->get_billing_city() ?></div>
                            <div class="w-commerce-commercecheckoutsummarytextspacingondiv"><?php echo $order->get_billing_city() ?></div>
                            <div class="w-commerce-commercecheckoutsummarytextspacingondiv"><?php echo $order->get_billing_postcode() ?></div>
                          </div>
                          <div><?php echo $order->get_billing_country() ?></div>
                        </div>
                      </div>
                    </div>
                  </fieldset>
                </div>
              </div>
              <div class="w-commerce-commercelayoutsidebar checkout-right">
                <div class="sticky-checkout-sidebar">
                  <div class="w-commerce-commercecheckoutorderitemswrapper order-items">
                    <div class="w-commerce-commercecheckoutsummaryblockheader block-header">
                      <h4>Résumé d'achat</h4>
                    </div>
                    <fieldset class="w-commerce-commercecheckoutblockcontent block-content">
                      <script type="text/x-wf-template" id="wf-template-d92215cc-f65c-a6bd-a288-14dbcfe2b878"><div data-node-type="checkout-order-item" class="order-item w-commerce-commercecheckoutorderitem"><div class="order-image"><img data-node-type="cart-item-image" class="order-item-image w-commerce-commercecartitemimage" template-part-bind="d92215cc-f65c-a6bd-a288-14dbcfe2b87b" src="<%= it.image %>" alt="<%= it.image %> image"></div><div class="order-details"><div data-node-type="checkout-order-item-description-wrapper" class="cart-item-top small w-commerce-commercecheckoutorderitemdescriptionwrapper"><div class="cart-item-title"><div data-node-type="cart-product-name" class="cart-title small w-commerce-commercecartproductname" template-part-bind="d92215cc-f65c-a6bd-a288-14dbcfe2b87f"><%= it.title %></div><ul data-node-type="checkout-order-item-option-list" class="option-list w-commerce-commercecheckoutoptionlist" template-part-bind="d92215cc-f65c-a6bd-a288-14dbcfe2b880"><% Object.entries(it.options).forEach( data =>  { %><li data-node-type="checkout-order-item-option-list-item"><span data-node-type="checkout-order-item-option-list-item-label"><%= data[0] %></span><span>: </span><span data-node-type="checkout-order-item-option-list-item-value"><%= data[1] %></span></li><% }) %></ul></div></div><div class="cart-item-bottom"><div class="price" template-part-bind="d92215cc-f65c-a6bd-a288-14dbcfe2b887"><%= it.rowTotal %></div><div data-node-type="checkout-order-item-quantity-wrapper" class="order-quantity w-commerce-commercecheckoutorderitemquantitywrapper"><div>Quantité: </div><div class="quantity-number" template-part-bind="d92215cc-f65c-a6bd-a288-14dbcfe2b88b"><%= it.quantity %></div></div></div></div></div></script>
                      <div role="list" class="w-commerce-commercecheckoutorderitemslist order-item-list" data-wf-collection="database.commerceOrder.userItems" data-wf-template-id="wf-template-d92215cc-f65c-a6bd-a288-14dbcfe2b878"></div>
                    </fieldset>
                  <?php udesly_wc_get_order_items_script($order); ?></div>
                  <div class="w-commerce-commercecheckoutordersummarywrapper">
                    <fieldset class="w-commerce-commercecheckoutblockcontent block-content">
                      <div class="w-commerce-commercecheckoutsummarylineitem">
                        <div class="summary-text">Sous total</div>
                        <div class="summary-text dark"><?php echo wc_price($order->get_subtotal()) ?></div>
                      </div>
                      <script type="text/x-wf-template" id="wf-template-61a7f3f99c97eb119a4a9a2d00000000006b">%3Cdiv%20class%3D%22w-commerce-commercecheckoutordersummaryextraitemslistitem%22%3E%3Cdiv%20bind%3D%2261a7f3f99c97eb119a4a9a2d00000000006d%22%20class%3D%22summary-text%22%3E%3C%2Fdiv%3E%3Cdiv%20bind%3D%2261a7f3f99c97eb119a4a9a2d00000000006e%22%20class%3D%22summary-text%20dark%22%3E%3C%2Fdiv%3E%3C%2Fdiv%3E</script>
                      <div class="w-commerce-commercecheckoutordersummaryextraitemslist" data-wf-collection="database.commerceOrder.extraItems" data-wf-template-id="wf-template-61a7f3f99c97eb119a4a9a2d00000000006b">
                        <?php foreach (udesly_wc_get_order_extra_items($order) as $extra_item) : ?><div class="w-commerce-commercecheckoutordersummaryextraitemslistitem">
                          <div class="summary-text"><?php echo $extra_item->name ?></div>
                          <div class="summary-text dark"><?php echo $extra_item->price ?></div>
                        </div><?php endforeach ?>
                      </div>
                      <div class="w-commerce-commercecheckoutsummarylineitem">
                        <div class="summary-text">Total</div>
                        <div class="w-commerce-commercecheckoutsummarytotal summary-text dark-bold"><?php echo wc_price($order->get_total()) ?></div>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </div>
            </div>
          </div>
 