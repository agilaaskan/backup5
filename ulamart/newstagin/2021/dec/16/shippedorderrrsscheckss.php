<?php
$date = date_default_timezone_set('Asia/Kolkata');
$currentdate = date("jS F Y");
$whtimg = "https://dev.ulamart.com/API/ulatools/Magento/uploads/sample.jpg";

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
    CURLOPT_POSTFIELDS => '{
        "phoneNumber": "9150664156",
        "event": "OrderShipped",
       "traits": {
           "email": "kbaji.1998@gmail.com",
           "orderCreatedBy":"Agi",
           "orderNumber": "000038949",
           "tracking": "C16328828",
           "totalbox": "1",
           "totalboxwt": "1.03",
           "shippedAt": "' . $currentdate . '",
           "image":"'.$whtimg.'"
           }
        }',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Basic UzQteTBVSmZvbzBsX2k4NFhWMUo1UXRfOExua2ZKd0ZRajNSNHZCLTM5STo='
    ) ,
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
?>
