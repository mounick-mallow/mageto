<?php
if ($_POST['order_id']){

$url = 'https://erp.theluxuryunlimited.com/api/return-exchange-buyback/create';

//Initiate cURL.
$ch = curl_init($url);

$customerEmail = $_POST['customer_email'];
$orderId = $_POST['order_id'];
$website = $_POST['website'];
$type = $_POST['type'];
$langCode = $_POST['lang_code'];
// echo $customerEmail.'=='.$orderId.'=='.$sku;exit;
//The JSON data.
$newjsonData = array(
  'customer_email' => $customerEmail,
  'website' => $website,
  'order_id'   => $orderId,
  'type' => $type,
  'lang_code' => $langCode
);

//Encode the array into JSON.
$jsonDataEncoded = json_encode($newjsonData);

//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);

//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

// Return response instead of outputting
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//Execute the request
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
}
?>
