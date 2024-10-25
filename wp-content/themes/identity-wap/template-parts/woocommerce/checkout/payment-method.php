<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
?>
<?php $item = udesly_wc_itemize_gateway($gateway); ?>
                    
                   
                   <li><label class="w-commerce-commercecheckoutshippingmethoditem shipping-method <?php echo "wc_payment_method payment_method_" . esc_attr($item->id) . ( $item->chosen ? " chosen" : ""); ?>"><input required="" class="radio-button input-radio" type="radio" name="payment_method" id="<?php echo "payment_method_" . esc_attr($item->id); ?>" value="<?php echo esc_attr($item->id); ?>" <?php echo checked( $item->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $item->order_button_text ); ?>">
                        <div class="w-commerce-commercecheckoutshippingmethoddescriptionblock shipping-title">
                          <div class="w-commerce-commerceboldtextblock shipping-text"><?php echo $item->name; ?></div>
                          <div class="body-display small"><?php echo $item->description; ?></div>
                        </div>
                        <div class="price"><?php echo $item->price; ?></div>
                      </label></li>
 