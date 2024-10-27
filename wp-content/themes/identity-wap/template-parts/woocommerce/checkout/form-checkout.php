<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
?>
<?php global $checkout;
$checkout = $args['checkout'];
        
do_action( 'woocommerce_before_checkout_form', $checkout );         
?>
        
<form name="checkout" method="post" data-node-type="commerce-checkout-form-container" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" class="checkout woocommerce-checkout w-commerce-commercecheckoutformcontainer checkout-form">

            <div class="checkout-grid">
              <div class="w-commerce-commercelayoutmain checkout-left">
                <div data-node-type="commerce-cart-quick-checkout-actions" class="web-payments"><?php wc_get_pay_buttons() ?></div>
                <div class="checkout-component">
                  <div data-node-type="commerce-checkout-customer-info-wrapper" class="w-commerce-commercecheckoutcustomerinfowrapper customer-wrapper">
                    <div class="w-commerce-commercecheckoutblockheader block-header">
                      <h4>Informations du client</h4>
                      <div class="field-label red">* Requis</div>
                    </div>
                    <fieldset class="w-commerce-commercecheckoutblockcontent block-content"><?php foreach ( $checkout->get_checkout_fields( 'customer' ) as $key => $field ) {
            $field['label_class'][] = "w-commerce-commercecheckoutlabel field-label";
            $field['input_class'][] = "w-commerce-commercecheckoutemailinput field no-margin";
            woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
        }
        
         udesly_wc_account_fields($checkout, "w-commerce-commercecheckoutlabel field-label", "w-commerce-commercecheckoutemailinput field no-margin");
         ?></fieldset>
                  </div>
                </div>
                <?php do_action( 'woocommerce_checkout_billing' ); do_action('woocommerce_checkout_shipping'); ?>
                <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?><div data-node-type="commerce-checkout-shipping-methods-wrapper" class="w-commerce-commercecheckoutshippingmethodswrapper checkout-component"><?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
                  <div class="w-commerce-commercecheckoutblockheader block-header">
                    <h4>Méthode de livraison</h4>
                  </div>
                  <?php wc_cart_totals_shipping_html(); ?>
                <?php do_action( 'woocommerce_review_order_after_shipping' ); ?></div><?php endif  ?>
                <?php woocommerce_checkout_payment(); ?>
                
              </div>
              <div class="w-commerce-commercelayoutsidebar checkout-right">
                <div class="sticky-checkout-sidebar">
                  <div class="w-commerce-commercecheckoutorderitemswrapper order-items">
                    <div class="w-commerce-commercecheckoutsummaryblockheader block-header">
                      <h4>Résumé d'achat</h4>
                    </div>
                    <fieldset class="w-commerce-commercecheckoutblockcontent block-content">
                      <script type="text/x-wf-template" id="wf-template-61a7f3f99c97ebc4ce4a9a27000000000086"><div data-node-type="checkout-order-item" class="order-item w-commerce-commercecheckoutorderitem"><div class="order-image"><img data-node-type="cart-item-image" class="order-item-image w-commerce-commercecartitemimage" template-part-bind="61a7f3f99c97ebc4ce4a9a27000000000088" src="<%= it.image %>" alt="<%= it.image %> image"></div><div class="order-details"><div data-node-type="checkout-order-item-description-wrapper" class="cart-item-top small w-commerce-commercecheckoutorderitemdescriptionwrapper"><div class="cart-item-title"><div data-node-type="cart-product-name" class="cart-title small w-commerce-commercecartproductname" template-part-bind="61a7f3f99c97ebc4ce4a9a2700000000008a"><%= it.title %></div><ul data-node-type="checkout-order-item-option-list" class="option-list w-commerce-commercecheckoutoptionlist" template-part-bind="61a7f3f99c97ebc4ce4a9a2700000000008f"><% Object.entries(it.options).forEach( data =>  { %><li data-node-type="checkout-order-item-option-list-item"><span data-node-type="checkout-order-item-option-list-item-label"><%= data[0] %></span><span>: </span><span data-node-type="checkout-order-item-option-list-item-value"><%= data[1] %></span></li><% }) %></ul></div></div><div class="cart-item-bottom"><div class="price" template-part-bind="61a7f3f99c97ebc4ce4a9a27000000000095"><%= it.rowTotal %></div><div data-node-type="checkout-order-item-quantity-wrapper" class="order-quantity w-commerce-commercecheckoutorderitemquantitywrapper"><div>Quantité: </div><div class="quantity-number" template-part-bind="61a7f3f99c97ebc4ce4a9a2700000000008e"><%= it.quantity %></div></div></div></div></div></script>
                      <div role="list" class="w-commerce-commercecheckoutorderitemslist order-item-list" data-wf-collection="database.commerceOrder.userItems" data-wf-template-id="wf-template-61a7f3f99c97ebc4ce4a9a27000000000086"></div>
                    </fieldset>
                  </div>
                  <?php if (wc_coupons_enabled()) : ?><div data-node-type="commerce-checkout-discount-form" class="w-commerce-commercecheckoutdiscounts discounts"><input data-node-type="commerce-checkout-discount-input" class="w-commerce-commercecheckoutdiscountsinput discount-code w-input" maxlength="256" name="coupon_code" data-name="field" placeholder="Mettre code promo" type="text" id="field" form="coupon_form">
                    <div class="discount-button-wrapper"><button aria-label="Apply Discount" class="w-commerce-commercecheckoutdiscountsbutton apply-button" name="apply_coupon" form="coupon_form">Soumettre</button></div>
                  </div><?php endif  ?>
                  <div class="summary-wrapper">
                    <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                    <?php do_action( 'woocommerce_review_order_before_submit' ); ?><button href="#" value="Confirmer commande" data-node-type="commerce-checkout-place-order-button" class="w-commerce-commercecheckoutplaceorderbutton submit-button" data-loading-text="Veuillez patienter s'il vous plait..." style="width: 100%;" type="submit">Confirmer commande</button><?php do_action( 'woocommerce_review_order_after_submit' ); ?><?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?><?php if (!is_ajax()) { do_action( 'woocommerce_review_order_after_payment' ); } ?>
                  </div>
                  <?php get_template_part('template-parts/woocommerce/checkout/checkout-errors') ?><?php wc_get_template( 'checkout/terms.php' ); ?>
                </div>
              </div>
            </div>
          
</form>
<?php if (wc_coupons_enabled()) : ?><form class="checkout_coupon woocommerce-form-coupon" id="coupon_form" method="post" style="display:none"></form><?php endif; ?>
<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
 