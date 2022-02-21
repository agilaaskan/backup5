<?php

$curl = curl_init();
function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
$realip=getUserIpAddr();
curl_setopt_array($curl, array(
//   CURLOPT_URL => "http://ip-api.com/json/".$realip,
CURLOPT_URL => "http://ip-api.com/json/",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
));
$response = curl_exec($curl);
echo "<pre>";
print_r($response);
curl_close($curl);
// $response_value=json_decode($response);
// $countryCode=$response_value->countryCode;
// $country=$response_value->country;
// echo json_encode(true);
// if($country=='United Kingdom'){
//     echo json_encode(true);
// }else if($countryCode=='GB'){
//     echo json_encode(true);
// }else if($country=='India'){
//     echo json_encode(true);
// }else if($countryCode=='IN'){
//     echo json_encode(true);
// }else{
//      echo json_encode(false);
// }
// echo json_encode(true);
// if($country=='United Kingdom'|| $country=='India'){
//     echo json_encode(true);
// }else if($countryCode=='IN' || $countryCode=='GB'){
//     echo json_encode(true);
// }else{ 
//     echo json_encode(false);
// }

