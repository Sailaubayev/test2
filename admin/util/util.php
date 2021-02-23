<?php
ini_set('max_execution_time', 50*60);

$admin_number="89260169090";
$status_map=array( 'ACTIVE'=> 'Активный', 
				 'EXPIRING'=> 'Активный', 
                'EXPIRED'=> 'Закончился', 
                'CANCELED'=> 'АННУЛИРОВАН');


function pass_cmp($a,$b){
	if($a->status=="ACTIVE" && $b->status!="ACTIVE"){
		return -63072000;
	}
	if($a->status!="ACTIVE" && $b->status=="ACTIVE"){
		return 63072000;
	}
    return strtotime($b->dateEnd)-strtotime($a->dateEnd);

}

function covert_data($data){
	$out=array();
	for ($i=0; $i < count($data) ; $i++) { 
		$row=array();
		$row['zone']=$data[$i]->propusktype;
		$row['passInfo']=$data[$i]->seriya;
		$row['dateStart']=date("d.m.Y",strtotime($data[$i]->datestart));
		$row['dateEnd']=date("d.m.Y",strtotime($data[$i]->dateend));
		$row['status']=$data[$i]->colorstatus;
		array_push($out,(object)$row);
	}
	return $out;
}

function getNewData($vehicle_number){
	$curl = curl_init();
	$vehicle_number=$name = str_replace(' ', '', $vehicle_number);
	$post="string=".$vehicle_number;
	curl_setopt_array($curl, array(
	  CURLOPT_URL => "http://proverit-propusk.ru/serch_number22.php",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => $post,
	  CURLOPT_HTTPHEADER => array(
	    "cache-control: no-cache",
	    "content-type: application/x-www-form-urlencoded"
	  ),
	));


	$result = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	}
	$result=json_decode($result);
	if (isset($result->isnotfound)){
        return array();
    }
	$result=covert_data($result);
	usort($result, "pass_cmp");
	$pass=$result[0];
	return $pass;

}

function getCurl($vehicle_number){
	return getNewData($vehicle_number);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://50.7.143.29/external/pass_finder/do_search");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "vehicle_number=".$vehicle_number);
	curl_setopt($ch, CURLOPT_POST, 1);

	$headers = array();
	$headers[] = "Pragma: no-cache";
	$headers[] = "Accept-Encoding: gzip, deflate";
	$headers[] = "Accept-Language: ru,en-US;q=0.8,en;q=0.6,uk;q=0.4";
	$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36";
	$headers[] = "Content-Type: application/x-www-form-urlencoded; charset=UTF-8";
	$headers[] = "Accept: */*";
	$headers[] = "Cache-Control: no-cache";
	$headers[] = "Connection: keep-alive";
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch,CURLOPT_FRESH_CONNECT,1);

	$result = curl_exec($ch);
	if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	}
	curl_close ($ch);
	$result=json_decode($result);
	usort($result->pass_array, "pass_cmp");
	$pass=$result->pass_array[0];
	return $pass;
}

function sendSms($text,$number){
	$sms_login="propusk_rwifamily";
	$sms_password="rwifamily";
	$sms_addr="http://web2.smsgorod.ru/sendsms.php";
	$sms_name="RWIFamily";
	$data=array("user"=>$sms_login,"pwd"=>$sms_password,"sadr"=>$sms_name,"text"=>$text,"dadr"=>$number);
	$res=file_get_contents($sms_addr."?".http_build_query($data));
	echo "LOG::SENDMESSAGE---num:".$number."---msg:".$text."---result:".$res."\n";
}


function processRow($value){
	global $status_map;
	global $admin_number;
	$pass=getCurl($value['number_vehicle']);
	//$date_invert=date_diff(DateTime::createFromFormat("Y-m-d",$value['start_date']),
							//DateTime::createFromFormat("Y-m-d",date("Y-m-d",strtotime($pass->dateStart))))->invert;
	$date_invert=0;	
	echo $value['pass_number']."!=".$pass->passInfo."----".($value['pass_number']!=$pass->passInfo)."\n";
	if($value['pass_number']!=$pass->passInfo && isset($pass->status)){
		$restriction_zone=$pass->zone;
		$pass_number=$pass->passInfo;
		$status=$status_map[$pass->status];
		$dateStart=date("Y-m-d",strtotime($pass->dateStart));
		$dateEnd=date("Y-m-d",strtotime($pass->dateEnd));
		$date1 =  DateTime::createFromFormat("Y-m-d",$dateStart);
		$date2 =  DateTime::createFromFormat("Y-m-d",$dateEnd);
		$interval = date_diff($date2, $date1);
		$pass_type=($interval->days/30)>1?"Годовой":"Разовый";
		$table="our_clients";
		$admin_message="Временный ".$value['number_vehicle']." с ".$dateStart." по ".$dateEnd;
		$message="Вам выдан временный пропуск на ".$value['number_vehicle']." СК с ".$dateStart." по ".$dateEnd.". сайт: www.rwiauto.ru телефон: 84993993688";
		if($pass_type=="Годовой"){
			$admin_message="Годовой ".$value['number_vehicle']." с ".$dateStart." по ".$dateEnd;
			$message="Вам выдан постоянный пропуск на ".$value['number_vehicle']." ".$restriction_zone." с ".$dateStart." по ".$dateEnd.". За вами закреплена персональная скидка 1000 руб., которой могу воспользоваться ваши друзья. Звоните! 
сайт: www.rwiauto.ru телефон: 84993993688";
		}
		if($value['delivery']==1){
			sendSms($message,$value['phone']);
	    	sendSms($admin_message,"+79037389078");
	    	sendSms($admin_message,"+79037388633");
		}
		$id=$value['id'];		
		$query="UPDATE `$table` SET `restriction_zone`='$restriction_zone',`pass_number`='$pass_number',`pass_type`='$pass_type',`status`='$status',`start_date`='$dateStart',`end_date`='$dateEnd', `DK`='$message' WHERE `id`=$id";	
		echo $query."\n";	
		check_dbconn();	
		mysql_query($query);
	} else{
       	if($value['status']!=$status_map[$pass->status] && $status_map[$pass->status]=="АННУЛИРОВАН" && isset($pass->status)){
			$status=$status_map[$pass->status];
			$table="our_clients";
			$admin_message="Аннулированный ".$value['number_vehicle'];
			$message="Внимание! Пропуск на ".$value['number_vehicle']." - АННУЛИРОВАН! Необходимо прекратить движение и срочно связаться с личным менеджером!
сайт: www.rwiauto.ru телефон: 84993993688";
			if($value['delivery']==1){
				sendSms($message,$value['phone']);
		    	sendSms($admin_message,"+79037389078");
		    	sendSms($admin_message,"+79037388633");
			}
			$id=$value['id'];		
			$query="UPDATE `$table` SET `status`='$status', `DK`='$message' WHERE `id`=$id";
			echo $query."\n";	
			check_dbconn();	
			mysql_query($query);		 
    	}
    }
}

?>