<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
?>
<div data-node-type="commerce-checkout-billing-address-wrapper" class="w-commerce-commercecheckoutbillingaddresswrapper checkout-component">
                  <div class="w-commerce-commercecheckoutblockheader block-header">
                    <?php if ( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?><h4>Adresse de facturation &amp;amp Adresse de livraison</h4><?php else : ?><h4>Adresse de facturation</h4><?php endif; ?>
                    <div class="field-label red">* Requis</div>
                  </div>
                  <?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?><fieldset class="w-commerce-commercecheckoutblockcontent block-content"><?php foreach ( $checkout->get_checkout_fields( 'billing' ) as $key => $field ) {
            $field['label_class'][] = "w-commerce-commercecheckoutlabel field-label";
            $field['input_class'][] = "w-commerce-commercecheckoutbillingfullname field";
            woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
        } ?><?php if (true === WC()->cart->needs_shipping_address()) : ?><div class="w-commerce-commercecheckoutbillingaddresstogglewrapper billing-address-toggle" id="ship-to-different-address"><input id="ship-to-different-address-checkbox" data-node-type="commerce-checkout-billing-address-toggle-checkbox" class="w-commerce-commercecheckoutbillingaddresstogglecheckbox checkbox" type="checkbox" value="1" name="ship_to_different_address" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?>><label for="ship-to-different-address-checkbox" class="w-commerce-commercecheckoutbillingaddresstogglelabel checkbox-label"><?php esc_html_e( 'Ship to a different address?', 'woocommerce' ); ?></label></div><?php endif  ?></fieldset><?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
                </div>
 