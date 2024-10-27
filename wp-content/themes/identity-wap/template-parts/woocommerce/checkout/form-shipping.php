<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
?>
<?php if ( true === WC()->cart->needs_shipping_address() ) : ?>
<div data-node-type="commerce-checkout-shipping-address-wrapper" class="w-commerce-commercecheckoutshippingaddresswrapper checkout-component shipping_address woocommerce-shipping-fields" style="display: none;">
                  <div class="w-commerce-commercecheckoutblockheader block-header">
                    <h4>Adresse de livraison</h4>
                    <div class="field-label red">* Requis</div>
                  </div>
                  <?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?><fieldset class="w-commerce-commercecheckoutblockcontent block-content"><?php foreach ( $checkout->get_checkout_fields( 'shipping' ) as $key => $field ) {
            $field['label_class'][] = "w-commerce-commercecheckoutlabel field-label";
            $field['input_class'][] = "w-commerce-commercecheckoutshippingfullname field";
            woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
        } ?></fieldset><?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
                </div>
<?php endif; ?>
 