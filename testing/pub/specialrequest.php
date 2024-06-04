<?php
$url = 'https://erp.theluxuryunlimited.com/api/ticket/create';

//Initiate cURL.
$ch = curl_init($url);

$name = $_POST['name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$type_of_inquiry = $_POST['type_of_inquiry'];
$subject = $_POST['subject'];
$message = $_POST['message'];
$source_of_ticket = $_POST['source_of_ticket'];
$phone_no = $_POST['phone_no'];
$brand = $_POST['brand'];
$style = $_POST['style'];
$keyword = $_POST['keyword'];
$image = $_POST['image'];
$langCode = $_POST['lang_code'];

//The JSON data.
$newjsonData = array(
  'name' => $name,
  'last_name' => $last_name,
  'email' => $email,
  'type_of_inquiry' => $type_of_inquiry,
  'subject' => $subject,
  'message' => $message,
  'source_of_ticket' => $source_of_ticket,
  'phone_no' => $phone_no,
  'brand' => $brand,
  'style' => $style,
  'keyword' => $keyword,
  'image' => $image,
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
  // print_r($result);exit;
  // $result = 'success';
  $err = curl_error($ch);

  curl_close($ch);

  // $logger->log(Zend\Log\Logger::INFO, $result.date('Y-m-d H:i:s'));
}
catch(Exception $e){
  // $logger->log(Zend\Log\Logger::INFO, $e.'=='.date('Y-m-d H:i:s'));
}
?>
