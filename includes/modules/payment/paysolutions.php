<?php


  class paysolutions {
    var $code;
    
    var $title;
    
    var $description;
    
    var $enabled;

// class constructor
    function paysolutions () {
		 global $order;
		 
      $this->code = 'paysolutions';
      $this->title = MODULE_PAYMENT_PAYSOLUTIONS_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_PAYSOLUTIONS_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_PAYSOLUTIONS_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_PAYSOLUTIONS_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_PAYSOLUTIONS_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_PAYSOLUTIONS_ORDER_STATUS_ID;
      }
	  
	    if (is_object($order)) $this->update_status();
		
          $this->form_action_url = 'https://www.thaiepay.com/epaylink/payment.aspx';//prod

 //$this->form_action_url = 'https://www.thaiepay.com/api.test/test_bloo1.php';//prod
    }
    
 function update_status() {
      global $db;
     global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_PAYSOLUTIONS_ZONE > 0) ) {
        $check_flag = false;
        $check = $db->Execute("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_PAYSOLUTIONS_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
        while (!$check->EOF) {
          if ($check->fields['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check->fields['zone_id'] == $order->billing['zone_id']) {
            $check_flag = true;
            break;
          }
          $check->MoveNext();
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

// class methods
    function javascript_validation() {
      return false;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return false;
    }

    function process_button() {
      global $db, $order, $currencies;
	  $inv  = date("YmdHis");

       $process_button_string = 			
		                      				zen_draw_hidden_field('biz',MODULE_PAYMENT_PAYSOLUTION_ID) .
											zen_draw_hidden_field('merchantid',MODULE_PAYMENT_PAYSOLUTIONS_ACID) .

											zen_draw_hidden_field('productdetail', "Payment zencart by : Paysolutions.com") .
											zen_draw_hidden_field('customeremail', $order->customer['email_address']) .
								//zen_draw_hidden_field('customerid', $order->customer['id']) .
											zen_draw_hidden_field('refno', date('Ymdhis')) .
								
								//zen_draw_hidden_field('amount', number_format($order->info['total'] * $currencies->get_value($sec_currency), $currencies->currencies[$sec_currency]['decimal_places'], '.', '')) .
	zen_draw_hidden_field('bill_name', $order->billing['firstname'] . ' ' . $order->billing['lastname']) .
	zen_draw_hidden_field('bill_addr_1', $order->billing['street_address']) .
	zen_draw_hidden_field('bill_addr_2', $order->billing['suburb']) .
	zen_draw_hidden_field('bill_city', $order->billing['city']) .

        zen_draw_hidden_field('bill_state', $order->billing['state']) .
        zen_draw_hidden_field('bill_post_code', $order->billing['postcode']) .
        zen_draw_hidden_field('bill_country', $order->billing['country']['title']) .
        zen_draw_hidden_field('bill_tel', $order->customer['telephone']) .
        zen_draw_hidden_field('bill_email', $order->customer['email_address']) .
        zen_draw_hidden_field('ship_name', $order->delivery['firstname'] . ' ' . $order->delivery['lastname']) .
        zen_draw_hidden_field('ship_addr_1', $order->delivery['street_address']) .
        zen_draw_hidden_field('ship_addr_2', $order->delivery['suburb']) .
        zen_draw_hidden_field('ship_city', $order->delivery['city']) .
        zen_draw_hidden_field('ship_state', $order->delivery['state']) .
        zen_draw_hidden_field('ship_post_code', $order->delivery['postcode']) .
        zen_draw_hidden_field('ship_country', $order->delivery['country']['title']) .							
		//zen_draw_hidden_field('returnurl', tep_href_link(FILENAME_CHECKOUT_PROCESS , '', 'SSL', true)) .
		//zen_draw_hidden_field('callback', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', false) . ';' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code, 'SSL', false)) .
//zen_draw_hidden_field(tep_session_name(), tep_session_id()) .
     //   zen_draw_hidden_field('options', 'test_status=' . $test_status . ',dups=false,cb_post=true,cb_flds=' . tep_session_name());

								//zen_draw_hidden_field('total', number_format($order->info['total'] * $currencies->get_value($sec_currency),	$currencies->currencies[$sec_currency]['decimal_places'], '.', '')) .
							   //zen_draw_hidden_field('biz',STORE_OWNER_EMAIL_ADDRESS) .
		                       //zen_draw_hidden_field('receiverid', MODULE_PAYMENT_PAYSHOP_REID) .
                               zen_draw_hidden_field('total', number_format(($order->info['total']) * $currencies->currencies['USD']['value'], $currencies->currencies['USD']['decimal_places'])) .
								//zen_draw_hidden_field('ap_currency', 'USD') .
								//zen_draw_hidden_field('ap_purchasetype', 'Item') .
                               zen_draw_hidden_field('itm', STORE_NAME) .
							   zen_draw_hidden_field('inv', $inv).
							   zen_draw_hidden_field('currencyCode', '840').
							   zen_draw_hidden_field('postURL', zen_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL')) ;
								//zen_draw_hidden_field('cancelurl', zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
      return $process_button_string;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function output_error() {
      return false;
    }

    function check() {
      global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYSOLUTION_STATUS'");
        $this->_check = $check_query->RecordCount();
      }
      return $this->_check;
    }

    function install() {
      global $db;
  //    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable PAYSOLUTIONS Module', 'MODULE_PAYMENT_PAYSOLUTIONS_STATUS', 'True', 'Do you want to accept Paysolutions Payments?', '6', '3', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
   
 $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Paysolutions Module', 'MODULE_PAYMENT_PAYSOLUTION_STATUS', 'True', 'Do you want to accept paysolutions Payments?', '6', '3', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
   $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_PAYSOLUTIONS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('E-Mail Address', 'MODULE_PAYMENT_PAYSOLUTION_ID', 'you@yourbusiness.com', 'The e-mail address to use for the Paysolution service', '6', '4', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Account Id', 'MODULE_PAYMENT_PAYSOLUTIONS_ACID', 'Your paysolutions account id', 'Enter your paysolutions account id ', '6', '4', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('UserId', 'MODULE_PAYMENT_PAYSOLUTIONS_REID', 'Your paysolutions user id', 'Enter your paysolutions user id ', '6', '4', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_PAYSOLUTIONS_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'zen_get_zone_class_title', 'zen_cfg_pull_down_zone_classes(', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_PAYSOLUTIONS_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'zen_cfg_pull_down_order_statuses(', 'zen_get_order_status_name', now())");
	 //$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Mode for Paysolutions web services<br /><br />Default:(<br /><code>www.thaiepay.com</code><br />or<br /><code>www.thaiepay.com</code><br />', 'MODULE_PAYMENT_PAYSOLUTIONS_HANDLER', 'www.thaiepay.com', 'Choose the URL for Thaiepay live processing', '6', '73', '', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Mode for Paysolutions web services<br /><br />Default:(<br /><code>www.thaiepay.com</code><br />or<br /><code>www.thaiepay.com</code><br />', 'MODULE_PAYMENT_PAYSOLUTIONS_HANDLER', 'www.thaiepay.com', 'Choose the URL for Paysolutions live processing', '6', '73', '', now())");
    }
    function remove() {
      global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_PAYSOLUTION_STATUS', 'MODULE_PAYMENT_PAYSOLUTIONS_SORT_ORDER', 'MODULE_PAYMENT_PAYSOLUTION_ID',  'MODULE_PAYMENT_PAYSOLUTIONS_ACID',  'MODULE_PAYMENT_PAYSOLUTIONS_REID', 'MODULE_PAYMENT_PAYSOLUTIONS_ZONE', 'MODULE_PAYMENT_PAYSOLUTIONS_ORDER_STATUS_ID',
	   'MODULE_PAYMENT_PAYSOLUTION_HANDLER');
    }
  }
?>
