<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
?>
<?php $type = "error"; $notices = isset($notices) ? $notices : wc_get_notices($type); ?>
            <div data-node-type="commerce-checkout-error-state" class="w-commerce-commercecheckouterrorstate <?php echo $type ?>" <?php echo count($notices) > 0 ? "" : "style=\"display: none;\"" ?>>
                    <?php foreach ($notices as $notice) : ?><div aria-live="polite" class="w-checkout-error-msg <?php echo $type ?>"><?php echo $notice['notice'] ?></div><?php endforeach ?>
                  </div>
 