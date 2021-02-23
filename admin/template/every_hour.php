<?php
$query="SELECT * FROM `our_clients` WHERE `delivery`=1 ";
$flag=false;
require_once("conf/config.php");
	$result=mysql_query($query);
	$res=array();
		while ($row=mysql_fetch_assoc($result)) {
				$res[]=$row;
		}

require_once("util/util.php");
	$pass=getCurl();
	foreach ($res as $key => $value) {
		if($value['pass_number']!=$pass->passInfo ){
			$dateStart=date("Y-m-d",strtotime($pass->dateStart));
			$dateEnd=date("Y-m-d",strtotime($pass->dateEnd));
			$date1 =  DateTime::createFromFormat("Y-m-d",$dateStart);
			$date2 =  DateTime::createFromFormat("Y-m-d",$dateEnd);
			$interval = date_diff($date2, $date1);
			$pass_type=$interval->m>1?"Годовой":"Разовый";
			$message="Новый пропуск с номером ".$pass->passInfo."у человека с номером: ".$value['phone'];
     		sendSms($message,'logUser.txt');
			
			if($pass_type=='Годовой'){
     			  	$message="Новый пропуск с номером ".$pass->passInfo."у человека с номером: ".$value['phone'];
     			  	sendSms($message,'logAdmin.txt');
			} 

			$id=$value['id'];		
			$query="UPDATE `our_clients` SET `restriction_zone`='$restriction_zone',`pass_number`='$pass_number',`pass_type`='$pass_type',`status`='$status',`start_date`='$dateStart',`end_date`='$dateEnd' WHERE `id`=$id";
			mysql_query($query);
			$flag=true;

		} else{
     			  if($value['status']!=$pass->status && $status_map[$pass->status]=="АННУЛИРОВАН"){
     			  	$message="Новый пропуск с номером ".$pass->passInfo."у человека с номером: ".$value['phone'];
     				sendSms($message,'logUser.txt');
					$dateStart=date("Y-m-d",strtotime($pass->dateStart));
					$dateEnd=date("Y-m-d",strtotime($pass->dateEnd));
					$date1 =  DateTime::createFromFormat("Y-m-d",$dateStart);
					$date2 =  DateTime::createFromFormat("Y-m-d",$dateEnd);
					$interval = date_diff($date2, $date1);
					$pass_type=$interval->m>1?"Годовой":"Разовый";
							if($pass_type=='Годовой'){
								$message="Новый пропуск с номером ".$pass->passInfo."у человека с номером: ".$value['phone'];
     			  				sendSms($message,'logAdmin.txt');
							}
					$id=$value['id'];		
					$query="UPDATE `our_clients` SET `restriction_zone`='$restriction_zone',`pass_number`='$pass_number',`pass_type`='$pass_type',`status`='$status',`start_date`='$dateStart',`end_date`='$dateEnd' WHERE `id`=$id";

					mysql_query($query);	
					$flag=true;	 
     			  }
     		}
	}
	if (!$flag) {
		$query="SELECT * FROM `our_clients`";
		foreach ($res as $key => $value) {
				if($value['pass_number']!=$pass->passInfo ){
					$dateStart=date("Y-m-d",strtotime($pass->dateStart));
					$dateEnd=date("Y-m-d",strtotime($pass->dateEnd));
					$date1 =  DateTime::createFromFormat("Y-m-d",$dateStart);
					$date2 =  DateTime::createFromFormat("Y-m-d",$dateEnd);
					$interval = date_diff($date2, $date1);
					$pass_type=$interval->m>1?"Годовой":"Разовый";
					$message="Новый пропуск с номером ".$pass->passInfo."у человека с номером: ".$value['phone'];
     				sendSms($message,'logUser.txt');
					if($pass_type=='Годовой'){
		     			  	$message="Новый пропуск с номером ".$pass->passInfo."у человека с номером: ".$value['phone'];
     					  	sendSms($message,'logAdmin.txt');
					} 
					$id=$value['id'];		
					$query="UPDATE `our_clients` SET `restriction_zone`='$restriction_zone',`pass_number`='$pass_number',`pass_type`='$pass_type',`status`='$status',`start_date`='$dateStart',`end_date`='$dateEnd' WHERE `id`=$id";
					mysql_query($query);

			}
		}
	}

?>