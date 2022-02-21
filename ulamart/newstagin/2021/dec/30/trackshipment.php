<?php
if($_POST['consignment_no'] != ''){
    $consignment_no = trim($_POST['consignment_no']);
    $first_letter = substr($consignment_no,0,1);
    if($first_letter == 'D' || $first_letter == 'C'){
        $res = dtdc($consignment_no);
    }else{
        $res = shiprocket($consignment_no);
    }
    echo $res;
}
if($_POST['o_id'] != ''){
    $o_id = trim($_POST['o_id']);
    $servername = "localhost";
    $username = "ulamart_newlive";
    $password = "Qe&+EAmUugih";
    $dbname = "ulamart_newlive";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    $sql_select_partial_orders_custom = "SELECT * FROM ps_partial_orders_custom WHERE order_id = $o_id LIMIT 1";

    $result_partial_orders_custom = $conn->query($sql_select_partial_orders_custom);
    if ($result_partial_orders_custom->num_rows > 0){
     while($row = $result_partial_orders_custom->fetch_assoc()) {
        $consignment_no = trim($row['consignment_no']);
        $first_letter = substr($consignment_no,0,1);
        if($first_letter == 'D' || $first_letter == 'C'){
            $res = dtdc($consignment_no);
        }else{
            $res = shiprocket($consignment_no);
        }
        echo json_encode(array('res'=>$res,'consignment_no'=>$consignment_no));
     }
    }else{
        $res = '<h2>Sorry! No Data Found</h2>';
        echo json_encode(array('res'=>$res,'consignment_no'=>''));
    }
}
function shiprocket($consignment_no){
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://apiv2.shiprocket.in/v1/external/auth/login',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{
        "email": "arune@askantech.com",
        "password": "Shiprocket@123"
    }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    $result = json_decode($response);
    $token = $result->token;
    if($token != ''){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/courier/track/awb/$consignment_no",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer $token"
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        $output = json_decode($response, true);
        $html = '<table><tr><th>S.No.</th><th>Date</th><th>Location</th><th>Status</th></tr>';
        if (is_array($output['tracking_data']['shipment_track_activities']) || is_object($output['tracking_data']['shipment_track_activities']))
        {
            foreach($output['tracking_data']['shipment_track_activities'] as $k=>$res){
                $sno = $k + 1;
                $date = $res['date'];
                $status = $res['activity'];
                $location = $res['location'];
                $html .= "<tr><td>$sno</td><td>$date</td><td>$location</td><td>$status</td></tr>";
            }
            $html .= "</table>";
            return $html;
        }else{
            return '<h2>Sorry! No Data Found</h2>';
        }
        
        
    }else{
        return '<h2>Sorry! No Data Found</h2>';
    }
}

function dtdc($consignment_no){
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://blktracksvc.dtdc.com/dtdc-api/rest/JSONCnTrk/getTrackDetails',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{
        "trkType":"cnno",
        "strcnno":"'.$consignment_no.'",
        "addtnlDtl": "Y"
    }',
      CURLOPT_HTTPHEADER => array(
        'X-Access-Token: CF2261_CF2261C001_trk:d18875f640cdc83a761b88883f261e30',
        'Content-Type: application/json',
        'Cookie: JSESSIONID=81D28B92517910D0942CA98817B8F8F0'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    $output = json_decode($response, true);
    if($output['statusCode'] == '200'){
        $html = '<table><tr><th>S.No.</th><th>Date</th><th>Location</th><th>Status</th></tr>';
        foreach($output['trackDetails'] as $k=>$res){
            $sno = $k + 1;
            $date = $res['strActionDate'];
            $d = substr($date,0,2);
            $m = substr($date,2,2);
            $y = substr($date,4,8);
            $time = $res['strActionTime'];
            $h = substr($time,0,2);
            $i = substr($time,2,2);
            $final_date = $y.'-'.$m.'-'.$d.' '.$h.':'.$i.':00';
            $status = $res['strAction'];
            $location = $res['strDestination'];
            $html .= "<tr><td>$sno</td><td>$final_date</td><td>$location</td><td>$status</td></tr>";
        }
        $html .= "</table>";
        return $html;
    }else{
        return '<h2>Sorry! No Data Found</h2>';
    }

}