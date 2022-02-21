<?php
require_once 'config.php';
require_once 'magentoConfig.php'; 
$path = $_SERVER['DOCUMENT_ROOT'];


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$data = json_decode(file_get_contents("php://input"));
$page = $_POST['page'];



if ($page == 1)
{
    $target_dir1 = array();
    $target_dir_weight = array();
    $weight = $_POST['wt_type'];
    $decode_weight = json_decode($weight);
    $uniid = uniqid();
    for($k =0; $k < count($decode_weight) ; $k++)
    {
        if (isset($_FILES['image'.$k]) && isset($_FILES['image'.$k]['tmp_name']) && !empty($_FILES['image'.$k]['tmp_name']) && $_FILES['image'.$k]['tmp_name'] !== null && $_FILES['image'.$k]['tmp_name'] !== "undefined" && $_FILES["image".$k]["size"] > 1)
        {
            $urls = "/uploads/".$uniid."_box".$k.".jpeg";
            array_push($target_dir1,  $urls);
            move_uploaded_file($_FILES['image'.$k]['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/API/ulatools/Magento/" . $urls);
        }
        else 
        {
            array_push($target_dir1,  '');
        }
        if (isset($_FILES['weight_image'.$k]) && isset($_FILES['weight_image'.$k]['tmp_name']) && !empty($_FILES['weight_image'.$k]['tmp_name']) && $_FILES['weight_image'.$k]['tmp_name'] !== null && $_FILES['weight_image'.$k]['tmp_name'] !== "undefined" && $_FILES["weight_image".$k]["size"] > 1)
        {
            $urls = "/uploads/".$uniid."_box_weight".$k.".jpeg";
            array_push($target_dir_weight,  $urls);
            move_uploaded_file($_FILES['weight_image'.$k]['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/API/ulatools/Magento/" . $urls);
        }
        else 
        {
            array_push($target_dir_weight,  '');
        }
    }
    $orderid = $_POST['orderid'];
    $reassing_id = $_POST['reassing_id'];
    $partial_order_id = $_POST['partial_order_id'];
    $createdon = date("Y-m-d H:i:s");

    $sql_order = "SELECT * FROM ps_partial_orders_custom WHERE order_id = '$orderid'";
    $conn->set_charset("utf8");
    $result_order = $conn->query($sql_order);
    if ($result_order->num_rows > 0)
    {
        $row_partial = $result_order->fetch_assoc();
        $pictures = [];
        $pictures_weight = [];
        $old_pics = $row_partial['pictures'];
        $old_pics_weight = $row_partial['pictures_weight'];
        $decode_pics = json_decode($old_pics);
        $decode_pics_weight = json_decode($old_pics_weight);
       
        for($jk = 0 ; $jk<count($decode_weight); $jk++)
        {
            array_push($pictures, ["picture" => $decode_weight[$jk]->image == "new" ? $target_dir1[$jk] : $decode_pics[$jk]->picture,  "weight" => $decode_weight[$jk]->weight, "cover_type" => $decode_weight[$jk]->cover_type]);

            array_push($pictures_weight, ["picture" => $decode_weight[$jk]->image_weight_status == "new" ? $target_dir_weight[$jk] : $decode_pics_weight[$jk]->picture]);
        }
    }
    $pictures = json_encode($pictures);
    $pictures_weight = json_encode($pictures_weight);
    $update_record = "UPDATE ps_partial_orders_custom
    SET pictures= '$pictures', pictures_weight = '$pictures_weight' , updated_on ='$createdon'
    WHERE id = '$partial_order_id'";
    if ($conn->query($update_record) === true)
    {
        $MSG = 'Data Updated Successfully into MySQL Database';
    }
    else
    {
        $MSG = $conn->error;
    }
}
else
{
    $orderid = $_POST['orderid'];
    $consinment = $_POST['consignment'];
    $courier_type = $_POST['courier'];
    $createdon = date("Y-m-d H:i:s");
    $partial_order_id = $_POST['partial_order_id'];

    $update_record = "UPDATE ps_partial_orders_custom SET consignment_no= '$consinment' , courier_type = '$courier_type', status = 'completed', updated_on ='$createdon'
    WHERE id = '$partial_order_id'";
    if ($conn->query($update_record) === true)
    {
        $MSG = 'Data Updated Successfully into MySQL Database';
    }
    else
    {
        $MSG = $conn->error;
    }
    $sql_select_partial_orders_custom = "SELECT * FROM ps_partial_orders_custom WHERE  id = '$partial_order_id' LIMIT 1";
    $result_partial_orders_custom = $conn->query($sql_select_partial_orders_custom);
    if ($result_partial_orders_custom->num_rows > 0)
    {
        $order_status = "Shipped";
        while ($row = $result_partial_orders_custom->fetch_assoc())
        {
            $order_number = $row['order_id'];
            $tracking = $row['consignment_no'];
            $user_id = $row['user_id'];
            $total_wt = $row['total_kg'];
            $total_bx = $row['box_count'];
            $ship_name = $row['user_name'];
            $attachment = $row['pictures'];
            $old_mail_status = $row['mail_sent'];
            $subject = "Tracking Information";
            $reassign_order = $row['reassign_order_id'];

            $update_record = "UPDATE ps_order_reassign SET `status` = 1 WHERE id = '$reassign_order'";
            if ($conn->query($update_record) === true)
            {
                $MSG = 'Data Updated Successfully into MySQL Database';
            }
            else
            {
                $MSG = $conn->error;
            }


            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: info@ulamart.com" . "\r\n";
            if ($row['courier_type'] == 'P')
            {
                $tracking_url = "https://www.tpcindia.com/Tracking2014.aspx?id=$tracking&type=0&service=0";
                $tracking_name = "Professional";
            }
            else if ($row['courier_type'] == 'D')
            {
                $tracking_url = 'https://www.dtdc.in/tracking/tracking_results.asp';
                $tracking_name = "DTDC";
            }
            else if ($row['courier_type'] == 'F')
            {
                $tracking_url = 'http://www.franchexpress.com/';
                $tracking_name = "Franch Express";
            }
            else if ($row['courier_type'] == 'S')
            {
                $tracking_url = 'https://www.shiprocket.in/shipment-tracking/';
                $tracking_name = "Ship Rocket";
            }
            else if ($row['courier_type'] == 'DH')
            {
                $tracking_url = 'https://www.delhivery.com/track/package/'.$tracking;
                $tracking_name = "Delhivery ";
            }
            if($consinment == "Store Pickup")
            {
                $order_status = "complete";
            }
            $weightList = json_decode($attachment);
            $totalboxwt = 0;
            for ($k = 0;$k < count($weightList);$k++)
            {
                $totalboxwt = $totalboxwt + floatval($weightList[$k]->weight);
            }


                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                    CURLOPT_URL => $magento_url.'/rest/V1/orders/'.$orderid,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Authorization: Bearer '.$accessToken
                    ),
                    ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $result_data = json_decode($response, true);
                    $customerid = $result_data['customer_id'];
                    $genderid = 0;
                    $email_date = date("jS F Y");
                    $sql_cutomer_entity = "SELECT * FROM `customer_entity` WHERE `entity_id` =  '$customerid'";
                    $conn->set_charset("utf8");
                    $result_cutomer_entity = $conn->query($sql_cutomer_entity);
                    if ($result_cutomer_entity->num_rows > 0)
                    {
                        $row_cutomer_entity = $result_cutomer_entity->fetch_assoc();
                        $genderid = $row_cutomer_entity['gender'];
                    }
                    $salutation = 'Mr/Ms.';
                    if($genderid == 1 || $genderid == '1')
                        $salutation ='Mr.';
                    else if($genderid == 2 || $genderid == '2')
                        $salutation ='Ms.';

                    $customer_name = 'Dear '.$salutation.' '.$result_data['customer_firstname'] . ' ' .$result_data['customer_lastname'];

                    $log_message = 'ORDER ID : '.$orderid.' - '.$customer_name."\n\r";
                    $log_file = "./my-errors.log";
                    error_log($log_message, 3, $log_file);

                    $order_id = $row['increement_id'];
                    $current_date_time = date("Y-m-d H:i:s");
                    $current_date_time = date('Y-m-d H:i:s', strtotime('+5 hour +30 minutes', strtotime($current_date_time)));
                    $customer_email = $result_data['customer_email']; //$result_data['customer_email'];//'mani@askantech.com';//'mani@askantech.com';//"arune@askantech.com";//"info@ulamart.com";//;
                    if($consinment == "Store Pickup")
                    {
                        $message = "<p>$customer_name,</p>
                                    <p>Greetings from Ulamart.com</p>
                                    <p>We would like to let you know that your order # $order_id has been delivered to you in person on ".date("jS F Y")."<br/><br/></p>
                                    <p>We request you to kindly shoot the unboxing event, just in case if the product is damaged during the shipment this would help you to claim back. </p>
                                    <p>Thanks for picking up this is from our store.</p>
                                    <p><br/><br/>
                                        TOTAL BOXES = $total_bx<br/>
                                        BOXES WEIGHT = $totalboxwt KG<br/><br/>
                                        Have a good day!</p>
                                    <p>Logistic Manager<br/>
                                    $ship_name</p>";
                        // $db_message = "$customer_name,\r\n\r\nGreetings from Ulamart.com\r\n\r\nWe would like to let you know that your order # $order_id has been delivered to you in person on ".date('jS F Y').".\r\n\r\nThanks for picking up this is from our store.\r\n\r\nTOTAL BOXES = $total_bx\r\nBOXES WEIGHT = $totalboxwt KG\r\n\r\nHave a good day!\r\n\r\nLogistic Manager\r\nYogesh";
                    }
                    else 
                    {
                             $message = "<p>$customer_name,</p>
                                        <p>Greetings from Ulamart.com</p>
                                        <p>We would like to let you know that your order # $order_id has been dispatched on $email_date with our shipment partner $tracking_name courier.</p>
                                        <p>We request you to kindly shoot the unboxing event, just in case if the product is damaged during the shipment this would help you to claim back. </p>
                                        <p>This tracking link will be activated in few hours from now:</p>
                                        <p>$tracking_url</p>
                                        <p>Tracking # $tracking<br/><br/>
                                            TOTAL BOXES = $total_bx<br/>
                                            BOXES WEIGHT = $totalboxwt KG<br/><br/>
                                            Have a good day!</p>
                                        <p>Logistic Manager<br/>
                                        $ship_name</p>";
                            // $db_message = "$customer_name,\r\n\r\nGreetings from Ulamart.com\r\n\r\nWe would like to let you know that your order no $order_number has been dispatched on $email_date with our shipment partner $tracking_name courier.\r\n\r\nThis tracking link will be activated in few hours from now:\r\n$tracking_url\r\nTracking # $tracking\r\n\r\nTOTAL BOXES = $total_bx\r\nBOXES WEIGHT = $totalboxwt KG\r\n\r\nHave a good day!\r\n\r\nLogistic Manager\r\nYogesh";
                    }
                    $ptn = "/^0/";
                    $sqls1 = "SELECT sales_order_address.telephone,sales_order_address.firstname,sales_order_address.lastname,sales_order_address.email FROM `sales_order_grid` INNER JOIN sales_order_address ON sales_order_grid.entity_id = sales_order_address.parent_id WHERE sales_order_grid.increment_id = '$order_number' LIMIT 1";
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
                    if($old_mail_status != 1)
                    {
                        $log_message = $message."\n\r";
                        $log_file = "./my-errors.log";
                        error_log($log_message, 3, $log_file);

                        if (send_email($customer_email, $attachment, $message, $order_id, $consinment, $telephone, $checkname, $checkem, $tracking ,$total_bx ,$totalboxwt))
                        {
                            $mailstatus = "Mail Sent! Order Number - # $order_id ";
                            $update_record = "UPDATE ps_partial_orders_custom SET mail_sent =1,  updated_on ='$createdon'
                            WHERE id = '$partial_order_id'";
                            if ($conn->query($update_record) === true)
                            {
                                $MSG1 = 'Data Updated Successfully into MySQL Database';
                            }
                            else
                            {
                                $MSG1 = $conn->error;
                            }
                        }
                    }


                    $request_url=$magento_url.'/rest/V1/orders/'.$orderid.'/comments';
                    $comments_message = "Tracking URL = ".$tracking_url.", Tracking No. = ". $tracking ."\n\r\n\r , TOTAL BOXES =". $total_bx ."\n\r\n\r , BOXES WEIGHT = $totalboxwt ";
                    $data_json = [
                        'statusHistory' => 
                        [
                            'comment' => $comments_message, 
                            'is_visible_on_front' => 1, 
                            'is_customer_notified' => 0, 
                        ]
                    ];
                    $data_string = json_encode($data_json);
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
                   

                    
                    $request_url=$magento_url.'/rest/V1/orders';
                    $data_json1 = [
                        "entity"=> [
                            "entity_id" => $orderid,
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
                    $curlres = $response."- Error : ".curl_error($ch);



                    // $data_json = [
                    //     "comment" => 
                    //         [
                    //             "comment" => $message,
                    //         ]
                    //     ,
                    // ];

                    // $data_string = json_encode($data_json);
                    // $request_url=$magento_url.'/rest/V1/order/'.$orderid.'/ship';
                    // $ch = curl_init($request_url);
                    // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    // curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    //     'Authorization: Bearer '.$accessToken,
                    //     'Content-Type: application/json',
                    //     'Content-Length: ' . strlen($data_string))
                    // );
                    // $response = curl_exec($ch);
                    // curl_close($curl);
                    // $result_data = json_decode($response, true);


                    
        }
    }
}
echo json_encode(["status" => "1", "msg" => $MSG, "orderid" => $orderid,'partial_order_id' => $partial_order_id, "mailstatus" => $mailstatus, "shipped" => $data_json1, "curlresponse" => $curlres , 'sqlp' =>$update_record ]);
exit;

function send_email($to, $attachment, $message, $order_number, $consinment, $telephone, $checkname, $checkem, $tracking ,$total_bx ,$totalboxwt)
{
    

    // $to ="priyanka.askan@gmail.com";
    // Load Composer's autoloader
    require 'vendor/autoload.php';
    $subject = "Your Ulamart.com order # $order_number is shipped";
    if($consinment == "Store Pickup")
    {
        $subject ="Your Ulamart.com order # $order_number is delivered";
    }
    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    // try {
        //Server settings
        $mail->SMTPDebug = 0;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'email-smtp.ap-south-1.amazonaws.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'AKIA36UKPHUGUPNMEFXP';                     // SMTP username
        $mail->Password   = 'BMFPWIBIUweaR/mHcURUeMHfnpU/eDbV1XOqSrkp2GFx';                              // SMTP password
        $mail->SMTPSecure = 'TLS';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
  
        //Recipients
        // $mail->setFrom('info@ulamart.com', 'Ulamart');
        $mail->setFrom('support@ulamart.com', 'Ulamart');
        $mail->addAddress($to);   
        $mail->addCC('info@ulamart.com');
        $mail->addCC('support@ulamart.com');
        $json = $attachment;
        $files = json_decode($json, true);
        // echo json_encode($files);
        // exit;
        
        for($j =0; $j < count($files);  $j++)
        {
            $images1 = $files[$j]['picture'];
            $mail->addAttachment($_SERVER['DOCUMENT_ROOT'] ."/API/ulatools/Magento/".$images1);
            // $f[] = $_SERVER['DOCUMENT_ROOT'] ."/API/ulatools/Magento/".$images1;
            if($j == 0) {
                $whtimg = "https://www.ulamart.com/API/ulatools/Magento/".$images1;
             }
        }
        
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;

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
            "phoneNumber": "'.$telephone.'",
            "event": "OrderShipped",
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
            
        curl_close($curl);
        //custom whatsapp api code

        return $mail->send();
}



// function send_email($to, $attachment, $message, $order_number, $courier_type)
// {

//     // Sender
//     $from = 'info@ulamart.com';
//     $fromName = 'Ulamart.com Shipment';

//     // Email subject
//     $subject = "Your Ulamart.com order # $order_number is out for delivery";
//     if($courier_type == "Store Pickup")
//     {
//         $subject ="Your Ulamart.com order # $order_number is delivered";
//     }
//     // Attachment file
//     $json = $attachment;
//     $files = json_decode($json, true);
//     for($j =0; $j < count($files);  $j++)
//     {
//         $images1 = $files[$j]['picture'];
//         $f[] = $_SERVER['DOCUMENT_ROOT'] ."/API/ulatools/Magento/".$images1;
//     }
    
//     // Email body content
//     $htmlContent = $message;

//     // Header for sender info
//     $headers = "From: $fromName" . " <" . $from . ">" . "\r\n";
//     //$headers .= 'Cc: info@ulamart.com';

//     // Boundary
//     $semi_rand = md5(time());
//     $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

//     // Headers for attachment
//     $headers .= "MIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

//     // Multipart boundary
//     $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";

//     // Preparing attachment
//     if (count($f) > 0)
//     {
//         //if(is_file($file)){
//         foreach ($f as $file)
//         {
//             $message .= "--{$mime_boundary}\n";
//             $fp = @fopen($file, "rb");
//             $data = @fread($fp, filesize($file));

//             @fclose($fp);
//             $data = chunk_split(base64_encode($data));
//             $message .= "Content-Type: application/octet-stream; name=\"" . basename($file) . "\"\n" . "Content-Description: " . basename($file) . "\n" . "Content-Disposition: attachment;\n" . " filename=\"" . basename($file) . "\"; size=" . filesize($file) . ";\n" . "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
//         }
//         //}
        
//     }
//     $message .= "--{$mime_boundary}--";
//     $returnpath = "-f" . $from;

//     // Send email
//     $mail = @mail($to, $subject, $message, $headers, $returnpath);

//     // Email sending status
//     return $mail; // $mail?"<h1>Email Sent Successfully!</h1>":"<h1>Email sending failed.</h1>";
    
// }

