<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
?>
<?php 
if ( ! is_ajax() ) {
  do_action( 'woocommerce_review_order_before_payment' );
}

$empty_message = apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) ;

?>

<div class="w-commerce-commercecheckoutpaymentinfowrapper checkout-component woocommerce-checkout-payment" id="payment">
                  <div class="w-commerce-commercecheckoutblockheader block-header">
                    <h4>Informations de paiements</h4>
                    <div class="field-label red">* Requis</div>
                  </div>
                  <fieldset class="block-content">
                    
                    <div data-node-type="commerce-checkout-shipping-methods-list" class="w-commerce-commercecheckoutshippingmethodslist shipping-method-list payment_methods" data-wf-collection="database.commerceOrder.availableShippingMethods" data-wf-template-id="wf-template-61a7f3f99c97ebc4ce4a9a27000000000042"><?php if ( ! empty( $available_gateways ) ) : ?><?php foreach ($available_gateways as $gateway) : ?><?php wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) ); ?><?php endforeach ?></div>
                    <?php else :  ?><div data-node-type="commerce-checkout-shipping-methods-empty-state" class="w-commerce-commercecheckoutshippingmethodsemptystate empty-state">
                      <div class="body-display small"><?php echo $empty_message ?></div>
                    </div><?php endif; ?>
                  </fieldset>
                </div>
 