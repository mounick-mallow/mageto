<?php
// $fullname = explode(" ", $_REQUEST['yourfullname']);
// if(@$fullname[1] == "")
// {
// 	$fullname[1] = $fullname[0];
// }
$filterarray = array(
    "referrer_first_name" => $_REQUEST['referrer_first_name'],
    "referrer_last_name" => $_REQUEST['referrer_last_name'],
    "referrer_email" => $_REQUEST['referrer_email'],
    "referrer_phone" => $_REQUEST['referrer_phone'],
    "referee_first_name" => $_REQUEST['referee_first_name'],
    "referee_last_name" => $_REQUEST['referee_last_name'],
    "referee_email" => $_REQUEST['referee_email'],
    "referee_phone" => $_REQUEST['referee_phone'],
    "website" => $_REQUEST['website'],
    "lang_code" => $_REQUEST['lang_code']

);
//$logger->info($filterarray);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://erp.theluxuryunlimited.com/api/friend/referral/create");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($filterarray));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
// Receive server response ...
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
try{
  $result = curl_exec($ch);
  // $result = 'success';
  $err = curl_error($ch);
  curl_close($ch);

  // $logger->log(Zend\Log\Logger::INFO, $result.date('Y-m-d H:i:s'));
}
catch(Exception $e){
  // $logger->log(Zend\Log\Logger::INFO, $e.'=='.date('Y-m-d H:i:s'));
}

// $server_output = curl_exec($ch);
// $serverOutput = json_decode($server_output);
// echo $serverOutput->message;
// echo "<pre>";
// print_r($serverOutput);
// if ($serverOutput->status == "success") {
//     $data_id = $serverOutput->data;
//     $msg = json_encode($serverOutput);
//     $status = 0;
//     $array_pass = array("status" => 1, "msg" => $msg);
//     echo json_encode($array_pass);
// } else {
//     $msg = "Unable to create ticket";
//     $status = 0;
//     $array_pass = array("status" => 0, "msg" => $msg);
//     echo json_encode($array_pass);
// }
// exit();
