<?php
$query="SELECT * FROM `our_clients` WHERE `delivery`=1";
require_once("conf/config.php");
	$result=mysql_query($query);
	$res=array();
		while ($row=mysql_fetch_assoc($result)) {
				$res[]=$row;
		}
	foreach ($res as $key => $value) {
		$date1 =  DateTime::createFromFormat("Y-m-d",$value['end_date']);
		$date2 = new DateTime();
		$interval = date_diff($date1, $date2);
		if($interval->days == 30){ 
     		$message="Осталось 30 дней у ропуска с номером ".$pass->passInfo."у человека с номером: ".$value['phone'];
     		sendSms($message,'logUser.txt');
				if ($pass_type=="Годовой") {
     				$message="Осталось 30 дней у ропуска с номером ".$pass->passInfo."у человека с номером: ".$value['phone'];
     				sendSms($message,'logAdmin.txt');
				}
		}
		if ($interval->days == 0) {
			$message="Сегодня истекает пропуск с номером ".$pass->passInfo."у человека с номером: ".$value['phone'];
     		sendSms($message,'logUser.txt');
				if ($pass_type=="Годовой") {
     				$message="Сегодня истекает пропуск с номером ".$pass->passInfo."у человека с номером: ".$value['phone'];
     				sendSms($message,'logAdmin.txt');
				}
		}	
					
	} 
			
		
	

?>