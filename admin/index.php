<?php
$username = "admin94563264377";
$password = "Super100Admin93Hkksd7109";
$nonsense = "supercalifragilisticexpialidocious";
if (isset($_COOKIE['PrivatePageLogin'])) {
   if ($_COOKIE['PrivatePageLogin'] == md5($password.$nonsense)) {
   	  require_once("admin.php");
      exit;
   } else {
      echo "Bad Cookie.";
   }
}
function validateCaptcha($response){

	$data = array(
            'secret' => "6Lcx1gsUAAAAAAoUApAE7bo0_X4zg0E2mGqWB-eG",
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

if (isset($_GET['p']) && $_GET['p'] == "login") {
   if ($_POST['user'] != $username) {
      echo "Sorry, that username does not match.";
      exit;
   } else if ($_POST['keypass'] != $password) {
      echo "Sorry, that password does not match.";
      exit;
   } else if (!isset($_POST['g-recaptcha-response']) || !validateCaptcha($_POST['g-recaptcha-response'])){
   	  echo "Sorry, wrong captcha.";
      exit;
   } else if ($_POST['user'] == $username && $_POST['keypass'] == $password) {
      setcookie('PrivatePageLogin', md5($_POST['keypass'].$nonsense));
      header("Location: $_SERVER[PHP_SELF]");
   } else {
      echo "Sorry, you could not be logged in at this time.";
   }
}
?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?p=login" method="post">
<label><input type="text" name="user" id="user" /> Name</label><br />
<label><input type="password" name="keypass" id="keypass" /> Password</label><br />
<div class="g-recaptcha" data-sitekey="6Lcx1gsUAAAAACXepJn3uQy3ljSvl8fOBAfvyMlg"></div>
<input type="submit" id="submit" value="Login" />
</form>