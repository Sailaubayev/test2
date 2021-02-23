<?php 
require_once("operations.php");
function validateCaptcha($response){

	$data = array(
            'secret' => "6Lf1JwkUAAAAAPyYZj9xH1CzesvRDR3jPGOG2Z6C",
            'response' => $response,
            'remoteip'=>$_SERVER['REMOTE_ADDR']
     );

	$verify = curl_init();
	curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
	curl_setopt($verify, CURLOPT_POST, true);
	curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($verify);
	return json_decode($response)->success;
}
if (!isset($_POST['g-recaptcha-response']) || !validateCaptcha($_POST['g-recaptcha-response'])){
   	  $error=array("message"=>"Неверно введена капча. Обновите страницу и попробуйте снова.");
   	  header("Content-type:application/json");
   	  echo json_encode($error);
   	  exit();
}
else{
	require_once "conf/config.php";
	$name=mysql_real_escape_string($_POST['name']);
	$phone=mysql_real_escape_string($_POST['phone']);
	$number_vehicle=mb_strtoupper(mysql_real_escape_string($_POST['vehicle_number']));
	$query="INSERT INTO `our_applications` (`name`,`phone`,`number_vehicle`) VALUES('$name','$phone','$number_vehicle'); ";
	$result=mysql_query($query);
	send_init_message($number_vehicle,$phone);
	$message=array("message"=>"Заявка на рассылку отправлена.");
   	header("Content-type:application/json");
   	echo json_encode($message);
	exit();
}
?>