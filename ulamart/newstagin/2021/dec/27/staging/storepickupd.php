<?php
	//custom whatsapp api code
	$date = date_default_timezone_set('Asia/Kolkata');
	$currentdate = date("jS F Y");
	$event = "StorePickup";
	$telephone ="9150664156";
	$checkem="kbaji.1998@gmail.com";
	$checkname="Agila";
	$order_number='000038923';
	$tracking="22117022700";
	$total_bx= 1;
	$totalboxwt= 1.03;
	$whtimg="https://dev.ulamart.com/API/ulatools/Magento/sample.jpg";
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
		"event": "'.$event.'",
	"traits": {
		"email": "'.$checkem.'",
		"orderCreatedBy":"'.$checkname.'",
		"orderNumber": "'.$order_number.'",
		"tracking": "'.$tracking.'",
		"totalbox": "'.$total_bx.'",
		"totalboxwt": "'.$totalboxwt.'",
		"shippedAt": "' . $currentdate . '",
		"image":"'.$whtimg.'"
		}
	}',
	CURLOPT_HTTPHEADER => array(
		'Content-Type: application/json',
		'Authorization: Basic UzQteTBVSmZvbzBsX2k4NFhWMUo1UXRfOExua2ZKd0ZRajNSNHZCLTM5STo='
	),
	));
		
	$response = curl_exec($curl);
	echo $response;
	curl_close($curl);

	//custom whatsapp api code
?>