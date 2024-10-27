<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
?>
<?php 
        if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
return;
}

$message = esc_html__( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'woocommerce' );
$redirect = wc_get_checkout_url();
?>      

<div class="woocommerce-form-login-toggle woocommerce-form-login-toggle w-commerce-commercecheckoutformcontainer checkout-form">
    <div class="w-commerce-commercecheckoutsummaryblockheader block-header">
        <h4 class=""><?php echo apply_filters( 'woocommerce_checkout_login_message', esc_html__( 'Returning customer?', 'woocommerce' ) ); ?></h4>
        <div><a href="#" class="showlogin "><?php echo esc_html__( 'Click here to login', 'woocommerce' ); ?></a></div>
    </div>
<form class="woocommerce-form woocommerce-form-login login w-commerce-commercecheckoutblockcontent block-content" method="post" style="display:none;">

<?php do_action( 'woocommerce_login_form_start' ); ?>

<?php echo ( $message ) ? wpautop( wptexturize( $message ) ) : ''; // @codingStandardsIgnoreLine ?>

<p class="form-row form-row-first">
<label for="username" class=""><?php esc_html_e( 'Username or email', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
<input type="text" class="input-text w-commerce-commercecheckoutdiscountsinput discount-code w-input" name="username" id="username" autocomplete="username" />
</p>
<p class="form-row form-row-last">
<label for="password" class=""><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
<input class="input-text w-commerce-commercecheckoutdiscountsinput discount-code w-input" type="password" name="password" id="password" autocomplete="current-password" />
</p>
<div class="clear"></div>

<?php do_action( 'woocommerce_login_form' ); ?>

<p class="form-row">
<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme ">
<input class="woocommerce-form__input woocommerce-form__input-checkbox w-commerce-commercecheckoutadditionalcheckbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
</label>
<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ); ?>" />
<button type="submit" class="woocommerce-button button woocommerce-form-login__submit w-commerce-commercecheckoutplaceorderbutton submit-button" name="login" value="<?php esc_attr_e( 'Login', 'woocommerce' ); ?>"><?php esc_html_e( 'Login', 'woocommerce' ); ?></button>
</p>
<p class="lost_password">
<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class=""><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
</p>

<div class="clear"></div>

<?php do_action( 'woocommerce_login_form_end' ); ?>

</form>
</div>
 