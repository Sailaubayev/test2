<?php
	require_once("util/util.php");

	$query="SELECT * FROM `our_clients` WHERE `delivery`=1";
	$q="SELECT * FROM `other_clients` WHERE `delivery`=1 AND DATEDIFF(NOW(),`addition_date`)<30";
	require_once("conf/config.php");
	$result=mysql_query($query);
	$res=array();
	while ($row=mysql_fetch_assoc($result)) {
		$res[]=$row;
	}
	$resultOther=mysql_query($q);
	while ($row=mysql_fetch_assoc($resultOther)) {
		$res[]=$row;
	}
	foreach ($res as $key => $value) {
		$date1 =  DateTime::createFromFormat("Y-m-d",$value['end_date']);
		$date2 = new DateTime();
		$interval = date_diff($date2, $date1);
		if($value['delivery']==0){
			continue;
		}
		echo $value['number_vehicle']."------".$interval->days."\n";
		if($interval->days == 30){ 
     		$message="Ваш пропуск ".$value['restriction_zone']." для авто ".$value['number_vehicle']." заканчивается через 30 дней";
     		sendSms($message,$value['phone']);
		}
		if ($interval->days == 0 && $value['status']=="Разовый") {
			$message="Закончился временный пропуск. Не выезжайте на автомобиле, дождитесь продления пропуска. Спасибо";
     		sendSms($message,$value['phone']);
		}	
					
	} 
	$q="UPDATE `other_clients` SET `delivery`=0   WHERE DATEDIFF(NOW(),`addition_date`)=30";
	mysql_query($q);
		
	

?>