<?php 


$sender = "MENYA";
$receiver = "25".$phone;
$message = $sms;


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://menya.app/api/v1/sms/send',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('sender' => "$sender",'receivers' => "$receiver",'message' => "$message",'API_KEY' => '00001'),
));

$response = curl_exec($curl);

curl_close($curl);
// echo $response;

?>
