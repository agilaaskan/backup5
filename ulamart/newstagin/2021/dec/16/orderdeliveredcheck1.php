<?php
//custom whatsapp api code
		$date = date_default_timezone_set('Asia/Kolkata');
		$currentdate = date("jS F Y");
   
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
		   "phoneNumber": "9080742240",
		   "event": "OrderDelivered",
		  "traits": {
			  "email": "gomathy.askan@gmail.com",
			  "orderCreatedBy":"Agila",
			  "orderNumber": "000038943",
			  "DeliveredAt": "'.$currentdate.'",
			  "DeliveredName": "Gomathy"
			  }
			}',
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Authorization: Basic UzQteTBVSmZvbzBsX2k4NFhWMUo1UXRfOExua2ZKd0ZRajNSNHZCLTM5STo='
		  ),
		  ));
			
		  $response = curl_exec($curl);
			
          curl_close($curl);
          echo $response;
		//custom whatsapp api code
?>