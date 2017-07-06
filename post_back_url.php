<?php
 require('includes/application_top.php');


echo		$refno = $_REQUEST['refno'];

echo		$customers_email_address = $_REQUEST['customeremail'];
		


echo		$order_total = $_REQUEST['total'];

echo		$cc_type = $_REQUEST['cardtype'];
echo		$orders_status = $_REQUEST['order'];

			  $payment_method = "PAYSOLUTIONS";
			   $payment_module_code = "paysolutions";

$date_purchased = date("YmdHis");

			   echo		$products_name = $_REQUEST['productdetail'];

 
$sql = "insert into orders				   (orders_id, 
											
											customers_name,
											customers_company,
											order_total,
											cc_type,
											customers_email_address,
											customers_address_format_id,
											payment_method,
											payment_module_code,
											billing_address_format_id,
											date_purchased,
											currency,
											currency_value,
											order_tax,
											orders_status)

										values ('' ,  
											
											'$refno' ,
											'$products_name',
											 '$order_total' , 
											 '$cc_type',
										    '$customers_email_address',
											'1',
											'$payment_method',
											'$payment_module_code',
											 '1',
											'$date_purchased',
											'TH',
											'1',
											'0',
											 '$orders_status')";


          $db->Execute($sql);


$orders_products_id = mysql_insert_id();

$sql1 = "insert into orders_products	   (orders_products_id, 
											orders_id,
											products_name,
											products_price,
											final_price

											)

										values ('' ,  
											'$orders_products_id',
											'$products_name',
											'$order_total',
											'$order_total'

											)";


          $db->Execute($sql1);

		  $sql2 = "insert into orders_status_history	   (orders_status_history_id, 
											orders_id,
											orders_status_id,
											date_added,
											customer_notified

											)

										values ('' ,  
											'$orders_products_id',
											'5',
											'$date_purchased',
											'1'

											)";


          $db->Execute($sql2);

		   $sql3 = "insert into orders_total	   (orders_total_id, 
											orders_id,
											title,
											text,
											value,
											class

											)

										values ('' ,  
											'$orders_products_id',
											'Total:',
											'฿$order_total',
											'$order_total',
											'ot_total'
											)";


          $db->Execute($sql3);

?>