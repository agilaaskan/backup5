<?php

require_once 'config.php';
require_once 'magentoConfig.php'; 
$path = $_SERVER['DOCUMENT_ROOT'];

$cron_startdate = date('Y-m-d H:i:s');
$insert_log = "insert into ulamart_cron_log set cron_name = 'Order Delivered Status Cron', start_date = '$cron_startdate', end_date = '$cron_startdate', cron_status = 'N'  ";
$conn->set_charset("utf8");
$getstock_result = $conn->query($insert_log);

$last_insert_log_id = $conn->insert_id;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

try  // try starts
{
	echo date("Y-m-d H:i:s")." -- CRON STARTED -- \n\n";

	$current_date = date('Y-m-d');
	$shippment_from_date_orders = date('Y-m-d',(strtotime( '-15 day', strtotime($current_date))));
	
	$shippment_to_date_orders = date('Y-m-d',(strtotime( '-5 day', strtotime($current_date))));

	$sql_select_orders = "SELECT sales_order_grid.increment_id, sales_order_grid.entity_id, ps_partial_orders_custom.consignment_no, ps_partial_orders_custom.courier_type  FROM sales_order_grid inner join ps_partial_orders_custom on ps_partial_orders_custom.order_id = sales_order_grid.entity_id  WHERE DATE_FORMAT(sales_order_grid.created_at, '%Y-%m-%d')  between '$shippment_from_date_orders' AND '$shippment_to_date_orders' AND ps_partial_orders_custom.courier_type = 'D' AND LOWER(sales_order_grid.status) = 'shipped'  ";   
	
	//$sql_select_orders = "SELECT sales_order_grid.increment_id, sales_order_grid.entity_id, ps_partial_orders_custom.consignment_no, ps_partial_orders_custom.courier_type  FROM sales_order_grid inner join ps_partial_orders_custom on ps_partial_orders_custom.order_id = sales_order_grid.entity_id  WHERE ps_partial_orders_custom.courier_type = 'D'  AND sales_order_grid.increment_id IN ('000037074')";   

	$result_select_orders = $conn->query($sql_select_orders);

	//echo "<pre>";
	//print_r($result_select_orders);
	//exit;
	

	if($result_select_orders->num_rows > 0)
	{
		while($row = $result_select_orders->fetch_assoc()) {
			
			$order_id = $row['increment_id'];
			$entity_id = $row['entity_id'];
			$trackingid = $row['consignment_no'];
			$courier_type = $row['courier_type'];		
			
			echo "\n".date('[H:i:s]')." Fetch order = $order_id "; 
			echo "\n".date('[H:i:s]')." Fetch Tracking Id = $trackingid "; 
			
			$res_dtdc = getDTDC($trackingid);
			

			if(strtolower($res_dtdc['strAction']) == 'delivered') { 
			
							
				$delivered_date = $res_dtdc['strActionDate'];
				
				$d = substr( $delivered_date, 0, 2 );
				$m = substr( $delivered_date, 2, 2 );
				$y = substr( $delivered_date, 4, 4 );
				
				$changed_date = $y."-".$m."-".$d;				
				
				$delivered_name = $res_dtdc['sTrRemarks'];				
											

				$order_result = check_order_information($conn, $magento_url, $accessToken, $order_id);	
				
				$delivered_date = date("jS F Y",strtotime($changed_date));

				$order_result['delivered_date']	= $delivered_date;		

				if(strlen($delivered_name)>4 && strpos(strtolower($delivered_name), 'sign') === false && strpos(strtolower($delivered_name), 'nill') === false) {
					$order_result['delivered_name']	= ucwords($delivered_name);
				} else {
					$order_result['delivered_name']	= "you";
				}	
											
				update_order_status($conn, $magento_url, $accessToken, $entity_id);
				
				send_email($order_result, $conn, $order_id);
				
			}		
				
		}
		
	}
	
	$cron_enddate = date('Y-m-d H:i:s');

	$update_log = "update ulamart_cron_log set  end_date = '$cron_enddate', cron_status = 'Y' where id = '$last_insert_log_id' ";
	$update_log_res = $conn->query($update_log);
	
	
	echo date("Y-m-d H:i:s")."\n\n -- CRON COMPLETED SUCCESSFULLY -- \n\n";

} catch(Exception $e) { // cache starts

	$update_log = "update ulamart_cron_log set  is_error = 1 where id = '$last_insert_log_id' ";
	$conn->set_charset("utf8");
	$getstock_result = $conn->query($update_log);	
	$msg = "Order delivered status cron Fails. ".$e->getMessage();
	$headers = "From: <support@ulamart.com>\n" ;
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=utf-8\n";
	$headers .= "Return-Path: <support@ulamart.com> \n";
	$headers .= "X-Mailer: PHP/" . phpversion();
	$ms=html_entity_decode($msg, ENT_QUOTES); 
	mail("ganesh.askan@gmail.com","Order delivered status Cron".$ms,$headers);
} 

function check_order_information($conn, $magento_url, $accessToken, $orderid) {
	
try  // try starts
{
	echo date("Y-m-d H:i:s")." -- Order information function -- \n";	

	$order_information = array();

    $sql_sales_order = "SELECT * FROM sales_order_grid where increment_id = '$orderid' ";
	$conn->set_charset("utf8");
	$result_sales_order = $conn->query($sql_sales_order);
	if ($result_sales_order->num_rows > 0)
	{
		$row_order_grid = $result_sales_order->fetch_assoc();				
		
		$customer_id = $row_order_grid['customer_id'];
		$order_information['customer_name'] = $row_order_grid['customer_name'];
		$order_information['customer_email'] = $row_order_grid['customer_email'];			
		$order_information['order_id'] = $row_order_grid['increment_id'];
		
		$entity_id = $row_order_grid['entity_id'];
		$order_information['entity_id'] = $entity_id;
		
		$genderid = 0;
		$email_date = date("jS F Y");
		$sql_cutomer_entity = "SELECT * FROM `customer_entity` WHERE `entity_id` =  '$customer_id'";
		$conn->set_charset("utf8");
		$result_cutomer_entity = $conn->query($sql_cutomer_entity);
		$customer_firstname = '';
		$customer_lastname = '';
		$customer_email = '';
		if ($result_cutomer_entity->num_rows > 0)
		{
			$row_cutomer_entity = $result_cutomer_entity->fetch_assoc();
			$genderid = $row_cutomer_entity['gender'];
			$customer_firstname = $row_cutomer_entity['firstname'];
			$customer_lastname = $row_cutomer_entity['lastname'];
			$customer_email = $row_cutomer_entity['email'];	
			
			$order_information['genderid'] = $genderid;
			
		}
		$salutation = 'Mr/Ms.';
		
		if($genderid == 1 || $genderid == '1') {
			$salutation ='Mr.';
		}
		else if($genderid == 2 || $genderid == '2') {
			$salutation ='Ms.';
		}
	
	    $order_information['salutation'] = $salutation;			
		
		$sql_select_partial_orders_custom = "SELECT * FROM ps_partial_orders_custom WHERE  order_id = '$entity_id' LIMIT 1";
		$result_partial_orders_custom = $conn->query($sql_select_partial_orders_custom);
		if ($result_partial_orders_custom->num_rows > 0)
		{
			$row = $result_partial_orders_custom->fetch_assoc();
			$order_number = $row['order_id'];
            $tracking = $row['consignment_no'];
            $user_id = $row['user_id'];
            $total_wt = $row['total_kg'];
            $total_bx = $row['box_count'];
            $ship_name = $row['user_name'];
            $attachment = $row['pictures'];
            $subject = "Tracking Information";
            $reassign_order = $row['reassign_order_id'];	
			
			$order_information['consignment_no'] = $tracking;
			$order_information['user_id'] = $user_id;
			$order_information['total_wt'] = $total_wt;
			$order_information['total_bx'] = $total_bx;
			$order_information['ship_name'] = $ship_name;
			$order_information['subject'] = $subject;
			$order_information['reassign_order'] = $reassign_order;		
			
		}
		
		
	}	

	return $order_information;
}  catch(Exception $e) { // cache starts

	$update_log = "update ulamart_cron_log set  is_error = 1 where id = '$last_insert_log_id' ";
	$conn->set_charset("utf8");
	$getstock_result = $conn->query($update_log);	
	$msg = "Order delivered status cron Fails. ".$e->getMessage();
	$headers = "From: <support@ulamart.com>\n" ;
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=utf-8\n";
	$headers .= "Return-Path: <support@ulamart.com> \n";
	$headers .= "X-Mailer: PHP/" . phpversion();
	$ms=html_entity_decode($msg, ENT_QUOTES); 
	mail("ganesh.askan@gmail.com","Order delivered status Cron".$ms,$headers);
} 	
	
}



function getDTDC($trackingid) {
	
try  // try starts
{
	echo "\n".date('[H:i:s]')." -- DTDC function tracking Id = $trackingid -- \n";
	
	 $url = 'https://blktracksvc.dtdc.com/dtdc-api/rest/JSONCnTrk/getDetails?strcnno='.$trackingid.'&TrkType=cnno&addtnlDtl=Y&apikey=CF2261_CF2261C001_trk:d18875f640cdc83a761b88883f261e30';

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);

	// execute and return string (this should be an empty string '')
	$str = curl_exec($curl);

	curl_close($curl);

	$result2 = json_decode($str,true);
		
	$result =  end($result2['trackDetails']);
	
	$rep_result = array("location"=>"","detail"=>"","status"=>"failure","date"=>"","time"=>"");
	
	if(isset($result2['status']) && strtolower($result2['status']) == 'success') {
		
		echo "\n".date('[H:i:s]')." -- DTDC success -- \n";

		if(count($result2['trackDetails'])>0) {

			if(isset($result['strAction']) && strtolower($result['strAction']) == 'delivered') {
				
				echo date("Y-m-d H:i:s")." -- DTDC delivered  -- \n";
				
				$response = $result;
				
			} else {
				
				$response = $result;
				
			}
			
		}
		
	} else { 
	
		echo "\n".date('[H:i:s]')." -- DTDC failed  -- \n";
		
		$response = $rep_result;
	}
	
	return $response;
	
	
	
}  catch(Exception $e) { // cache starts

	$update_log = "update ulamart_cron_log set  is_error = 1 where id = '$last_insert_log_id' ";
	$conn->set_charset("utf8");
	$getstock_result = $conn->query($update_log);	
	$msg = "Order delivered status cron Fails. ".$e->getMessage();
	$headers = "From: <support@ulamart.com>\n" ;
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=utf-8\n";
	$headers .= "Return-Path: <support@ulamart.com> \n";
	$headers .= "X-Mailer: PHP/" . phpversion();
	$ms=html_entity_decode($msg, ENT_QUOTES); 
	mail("ganesh.askan@gmail.com","Order delivered status Cron".$ms,$headers);
} 		


}

function update_order_status($conn, $magento_url, $accessToken, $entity_id) {

try {	

	echo date("Y-m-d H:i:s")." -- Order update status function called -- \n";
	
	$order_status = 'complete';	
		
	//  echo json_encode(["id" =>   $orderid , "status" => $order_status]);
	//  exit;
		
	$request_url=$magento_url.'rest/V1/orders';
	$data_json1 = [
		"entity"=> [
		 "entity_id" => $entity_id,
		 "state" => $order_status,
		 "status" => $order_status,
		]
	];
	
	
	
	$data_string = json_encode($data_json1);
	$ch = curl_init($request_url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Authorization: Bearer '.$accessToken,
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data_string))
	);
	$response = curl_exec($ch);
	curl_close($ch);
	if($response) {
		echo date("Y-m-d H:i:s")." -- Order updated successfully entity id = $entity_id  -- \n";
	}
	
	$curlres = $response."- Error : ".curl_error($ch);	
	
	return json_encode( $curlres);
	
}  catch(Exception $e) { // cache starts

	$update_log = "update ulamart_cron_log set  is_error = 1 where id = '$last_insert_log_id' ";
	$conn->set_charset("utf8");
	$getstock_result = $conn->query($update_log);	
	$msg = "Order delivered status cron Fails. ".$e->getMessage();
	$headers = "From: <support@ulamart.com>\n" ;
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=utf-8\n";
	$headers .= "Return-Path: <support@ulamart.com> \n";
	$headers .= "X-Mailer: PHP/" . phpversion();
	$ms=html_entity_decode($msg, ENT_QUOTES); 
	mail("ganesh.askan@gmail.com","Order delivered status Cron".$ms,$headers);
} 		


	
}


function send_email($order_result, $conn, $orderid)
{		
		//custom whatsapp api code
		$date = date_default_timezone_set('Asia/Kolkata');
		$currentdate = date("jS F Y");
		$corder_id = $order_result['order_id'];
		$dperson = $order_result['delivered_name'];
		$ptn = "/^0/";
		$sqls1 = "SELECT sales_order_address.telephone,sales_order_address.firstname,sales_order_address.lastname,sales_order_address.email FROM `sales_order_grid` INNER JOIN sales_order_address ON sales_order_grid.entity_id = sales_order_address.parent_id WHERE sales_order_grid.increment_id = '$corder_id' LIMIT 1";
		$results1 = $conn->query($sqls1);
		if ($results1->num_rows > 0) {
			while ($rows1 = $results1->fetch_assoc()) {
			$telephone1 = $rows1["telephone"];
			$telephone = preg_replace($ptn,'', $telephone1);
			$cusname = $rows1["firstname"];
			$checkname =  "$cusname";
			$cemail = $rows1["email"];
			$checkem =  "$cemail";
		   }
		}
   
		$curl = curl_init();
   
   
		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api.interakt.ai/v1/public/track/events/',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>'{
		   "phoneNumber": "'.$telephone.'",
		   "event": "OrderDelivered",
		  "traits": {
			  "email": "'.$checkem.'",
			  "orderCreatedBy":"'.$checkname.'",
			  "orderNumber": "'.$corder_id.'",
			  "DeliveredAt": "'.$currentdate.'",
			  "DeliveredName": "'.$dperson.'"
			  }
			}',
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Authorization: Basic UzQteTBVSmZvbzBsX2k4NFhWMUo1UXRfOExua2ZKd0ZRajNSNHZCLTM5STo='
		  ),
		  ));
			
		  $response = curl_exec($curl);
			
		  curl_close($curl);
		//custom whatsapp api code

	// Load Composer's autoloader
		require 'vendor/autoload.php';		
		
		// Instantiation and passing `true` enables exceptions
		$mail = new PHPMailer(true);
		
		//echo "<pre>";
		//print_r($order_result);
		
		
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: support@ulamart.com" . "\r\n";

		$customer_name = 'Dear '.$order_result['salutation'].' '.$order_result['customer_name'];
		
		$subject ="Your Ulamart.com order # ".$order_result['order_id']." is delivered";

		$message = "<p>$customer_name,</p>
<p>Greetings from Ulamart.com</p>
<p>We would like to let you know that your order # ".$order_result['order_id']." has been delivered to ".$order_result['delivered_name']." in person on ".$order_result['delivered_date']."</p>
<p>We request you to kindly shoot the unboxing event, just in case if the product is damaged during the shipment this would help you to claim back.</p>		
<p>If you are happy with our service kindly leave your feedback on the respective product pages or on Google here https://bit.ly/3ydM5v6</p>		
<p>We are very excited to hear from you. In case if you would like to reach out to the key people of Ulamart.com please reach here https://www.ulamart.com/contact-us</p>		
<p>Also we are social if you would like to join us and to know the trending offers please follow us on instagram here https://www.instagram.com/ulamartindia/</p>								
<p>Have a good day!</p><br/>
<p>Logistic Manager<br/>
".$order_result['ship_name']."</p>";
								
		//echo $message."<br><br><br><br>";		
		
		//exit;

		$to = trim($order_result['customer_email']);
		//$to = 'ganesh.askan@gmail.com';
		//$cc = 'support@ulamart.com,sankar@ulamart.com'; 
		$cc1 = 'support@ulamart.com'; 
		$cc2 = 'sankar@ulamart.com'; 
		//$cc = 'mani@askantech.com'; 
		
        $mail->SMTPDebug = 0;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'email-smtp.ap-south-1.amazonaws.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'AKIA36UKPHUGUPNMEFXP';                     // SMTP username
        $mail->Password   = 'BMFPWIBIUweaR/mHcURUeMHfnpU/eDbV1XOqSrkp2GFx';                              // SMTP password
        $mail->SMTPSecure = 'TLS';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
  
        //Recipients
        $mail->setFrom('support@ulamart.com', 'Ulamart');
        $mail->addAddress($to);   
        $mail->addCC($cc1); 
		$mail->addCC($cc2); 	
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;
        if($mail->send()) {
			echo "\n".date('[H:i:s]')." Email sent successfully to $to \n";
		} else {
			echo "\n".date('[H:i:s]')." Email fails for $to \n";
		}
	

    
}

?>