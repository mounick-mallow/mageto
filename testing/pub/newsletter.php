<?php
if ($_POST['email']){

$url = 'https://erp.theluxuryunlimited.com/api/mailinglist/add';

//Initiate cURL.
$ch = curl_init($url);

$customerEmail = $_POST['email'];
$website = $_POST['website'];
$storeName = $_POST['store_name'];
//The JSON data.
$newjsonData = array(

  'website' => $website,
  'email' => $customerEmail,
  'lang_code' => $storeName
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
