<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
?>
<?php 
$formatted_destination    = isset( $formatted_destination ) ? $formatted_destination : WC()->countries->get_formatted_address( $package['destination'], ', ' );
$has_calculated_shipping  = ! empty( $has_calculated_shipping );
$show_shipping_calculator = ! empty( $show_shipping_calculator );
$calculator_text          = '';


$items = [];

if ($available_methods) {
    foreach ( $available_methods as $method ) {
        $items[] = (object) [
            'type' =>      1 < count( $available_methods ) ? "radio" : "hidden",
            'input_name' => sprintf('shipping_method[%1$d]', $index),
            'id' => sprintf('shipping_method_%1$d_%2$s', $index, esc_attr( sanitize_title( $method->id ) )),
            'value' => esc_attr( $method->id ),
            'checked' => checked( $method->id, $chosen_method, false ),
            'name' => $method->get_label(),
            'price' => $method->cost > 0 ? wc_price($method->cost) : "",
            'description' => "",
            'method' => $method,
            'index' => $index
        ];
    }
}

$empty_message = (! $has_calculated_shipping || ! $formatted_destination) ? wp_kses_post( apply_filters( 'woocommerce_shipping_may_be_available_html', __( 'Enter your address to view shipping options.', 'woocommerce' ) ) ) : wp_kses_post( apply_filters( 'woocommerce_no_shipping_available_html', __( 'There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'woocommerce' ) ) );
?>

<fieldset class="block-content">
                    
                    <div data-node-type="commerce-checkout-shipping-methods-list" class="w-commerce-commercecheckoutshippingmethodslist shipping-method-list" data-wf-collection="database.commerceOrder.availableShippingMethods" data-wf-template-id="wf-template-61a7f3f99c97ebc4ce4a9a27000000000042"><?php if (count($items) > 0) : ?><?php foreach ($items as $item) : ?><label class="w-commerce-commercecheckoutshippingmethoditem shipping-method"><input required="" class="radio-button shipping_method" type="<?php echo $item->type; ?>" name="<?php echo $item->input_name; ?>" data-index="<?php echo $item->index; ?>" value="<?php echo $item->value; ?>" <?php echo $item->checked; ?>><?php do_action( 'woocommerce_after_shipping_rate', $item->method, $item->index ); ?>
                        <div class="w-commerce-commercecheckoutshippingmethoddescriptionblock shipping-title">
                          <div class="w-commerce-commerceboldtextblock shipping-text"><?php echo $item->name; ?></div>
                          <div class="body-display small"><?php echo $item->description; ?></div>
                        </div>
                        <div class="price"><?php echo $item->price; ?></div>
                      </label><?php endforeach ?></div>
                    <?php else :  ?><div data-node-type="commerce-checkout-shipping-methods-empty-state" class="w-commerce-commercecheckoutshippingmethodsemptystate empty-state">
                      <div class="body-display small"><?php echo $empty_message ?></div>
                    </div><?php endif; ?>
                  </fieldset>
 