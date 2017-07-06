<?php
/**
 * paysolutions EC button display template
 *
 * @package paymentMethod
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_ec_button.php 5388 2006-12-25 06:28:40Z drbyte $
 */

$ec_enabled = (defined('MODULE_PAYMENT_paysolutions WPP_STATUS') && MODULE_PAYMENT_paysolutions WPP_STATUS == 'True');
if ($ec_enabled && ($_SESSION['cart']->count_contents() > 0)) {
    // If they're here, they're either about to go to paysolutions  or were
    // sent back by an error, so clear these session vars.
    unset($_SESSION['paysolutions _ec_temp']);
    unset($_SESSION['paysolutions _ec_token']);
    unset($_SESSION['paysolutions _ec_payer_id']);
    unset($_SESSION['paysolutions _ec_payer_info']);

    include DIR_WS_LANGUAGES . $_SESSION['language'] . 'https://www.thaiepay.com/epaylink/payment.aspx';
?>
<div id="PPECbutton" class="buttonRow">
  <a href="<?php echo zen_href_link('ipn_main_handler.php', 'type=ec', 'SSL', true, true, true); ?>"><img src="<?php echo MODULE_PAYMENT_paysolutions WPP_EC_BUTTON_IMG ?>" alt="<?php echo MODULE_PAYMENT_paysolutions WPP_TEXT_BUTTON_ALTTEXT; ?>" /></a>
</div>
<?php
}
?>