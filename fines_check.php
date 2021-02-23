<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
	$rus=array("А", "В", "Е", "К", "М", "Н", "О", "Р", "С", "Т", "У","Х");
	$lat=array("A", "B", "E", "K", "M", "H", "O", "P", "C", "T", "Y","X");

	if(isset($_GET['img'])){
		header("Content-Type: image/jpeg");
		$url = 'http://check.gibdd.ru/proxy/captcha.jpg?';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_REFERER, $url);
				curl_setopt($ch, CURLOPT_COOKIESESSION, true);
	 	curl_setopt($ch, CURLOPT_COOKIEJAR, dirname( $_SERVER['SCRIPT_FILENAME'] ).'/cookies/'.str_replace(".","_",$_SERVER['REMOTE_ADDR']));

		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.1 Safari/537.11');
		$res = curl_exec($ch);
		$rescode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		curl_close($ch) ;
		echo $res;
		exit();
	}

	if(isset($_POST['get_fines'])){
		header("Content-Type: application/json");
		$reg=mb_strtoupper($_POST['regnum']);
		$regnum=explode(' ',str_replace($rus,$lat,urldecode($reg)))[0];
		$regreg=explode(' ',str_replace($rus,$lat,urldecode($reg)))[1];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://check.gibdd.ru/proxy/check/fines");
		// откуда пришли на эту страницу
		curl_setopt($ch, CURLOPT_REFERER, "http://www.gibdd.ru/check/fines/");
		// cURL будет выводить подробные сообщения о всех производимых действиях
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_ENCODING ,"UTF-8");
		curl_setopt($ch, CURLOPT_COOKIESESSION, true);
		curl_setopt($ch, CURLOPT_COOKIEFILE, dirname( $_SERVER['SCRIPT_FILENAME'] ).'/cookies/'.str_replace(".","_",$_SERVER['REMOTE_ADDR']));
;


		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query(array('regnum' =>  $regnum,'regreg' => $regreg ,'stsnum' =>  str_replace($rus,$lat,mb_strtoupper($_POST['stsnum'])),'captchaWord' => $_POST['cpt'] )));
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (Windows; U; Windows NT 5.0; En; rv:1.8.0.2) Gecko/20070306 Firefox/1.0.0.4");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result=curl_exec($ch);
		echo $result;
		exit();
	}
	die("No valid param")
		


  
?>
