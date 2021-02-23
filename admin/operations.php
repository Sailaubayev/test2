<?php
require_once("conf/config.php");
require_once("util/util.php");



function process_new_row($id,$table){

	$query="SELECT * FROM `$table` WHERE `id`=$id";
	$result=mysql_query($query);
	$res=mysql_fetch_assoc($result);
	$vehicle_number=$res['number_vehicle'];
	
	$pass=getCurl($vehicle_number);
	$status_map=array( 'ACTIVE'=> 'Активный', 
                'EXPIRED'=> 'Закончился', 
                'CANCELED'=> 'АННУЛИРОВАН');

	$dateStart=date("Y-m-d",strtotime($pass->dateStart));
	$dateEnd=date("Y-m-d",strtotime($pass->dateEnd));
	$restriction_zone=$pass->zone;
	$pass_number=$pass->passInfo;
	$status=$status_map[$pass->status];
	$date1 =  DateTime::createFromFormat("Y-m-d",$dateStart);
	$date2 =  DateTime::createFromFormat("Y-m-d",$dateEnd);
	$interval = date_diff($date2, $date1);
	$pass_type=($interval->days/30)>1?"Годовой":"Разовый";
	if($pass_number==""){
		$pass_type="Нет пропуска";
	}	

	$query="UPDATE `$table` SET `restriction_zone`='$restriction_zone',`pass_number`='$pass_number',`pass_type`='$pass_type',`status`='$status',`start_date`='$dateStart',`end_date`='$dateEnd' WHERE `id`=$id";
	mysql_query($query);


}


function process_new_reestr($id,$table){
	$query="SELECT * FROM `$table` WHERE `id`=$id";
	$result=mysql_query($query);
	$res=mysql_fetch_assoc($result);
	$vehicle_number=$res['number_vehicle'];
	
	$pass=getCurl($vehicle_number);
	$status_map=array( 'ACTIVE'=> 'Активный', 
                'EXPIRED'=> 'Закончился', 
                'CANCELED'=> 'АННУЛИРОВАН');

	$dateStart=date("Y-m-d",strtotime($pass->dateStart));
	$dateEnd=date("Y-m-d",strtotime($pass->dateEnd));
	$restriction_zone=$pass->zone;
	$pass_number=$pass->passInfo;
	$status=$status_map[$pass->status];
	$date1 = new DateTime();
	$date2 =  DateTime::createFromFormat("Y-m-d",$dateEnd);
	$interval = date_diff($date1, $date2);
	$active_till=($status=="Активный" && $interval->days!=0 && $interval->invert!=1)?$dateEnd:"Нет активного пропуска";

	$query="UPDATE `$table` SET `active_till`='$active_till' WHERE `id`=$id";
	mysql_query($query);
}

function send_init_message($vehicle_number,$phone){
	$pass=getCurl($vehicle_number);
	if($pass->status=="ACTIVE"){
		$message="У вас есть пропуск на ".$pass->zone." с ".$pass->dateStart." по ".$pass->dateEnd." - ".$vehicle_number;
		sendSms($message,$phone,false);
	}
	else{
		$message="Для автомобиля ".$vehicle_number." не найдено действующих пропусков";
		sendSms($message,$phone,false);
	}

}

if(isset($_GET['delete'])){
	$ids=$_POST['ids'];
	$table=$_POST['table'];
	for ($i=0; $i < count($ids); $i++) { 
		$id=$ids[$i];
		$query="DELETE FROM `$table` WHERE `id`=$id;";
		$result=mysql_query($query);
	}
	exit();
}

if(isset($_GET['move_to'])){

	$ids=$_POST['ids'];
	$table=$_POST['table'];
	$target_table=$_POST['action'];
	for ($i=0; $i < count($ids); $i++) { 
		$id=$ids[$i];
		$query="INSERT INTO `$target_table` (`name`,`phone`,`number_vehicle`) SELECT `name`,`phone`,`number_vehicle` FROM `$table` WHERE `id`=$id;";
		mysql_query($query);
		$result=mysql_insert_id();
		$query="DELETE FROM `$table` WHERE `id`=$id";
		mysql_query($query);
		process_new_row($result,$target_table);
	}
	exit();
}

if(isset($_GET['update'])){

	$ids=$_POST['ids'];
	$table=$_POST['table'];
	for ($i=0; $i < count($ids); $i++) { 
		$id=$ids[$i];
		$query="SELECT * FROM `$table` WHERE `id`=$id";
		$result=mysql_fetch_assoc(mysql_query($query));
		processRow($result,$table);
	}
	exit();
}

if(isset($_GET['change_field'])){

	$id=$_POST['id'];
	$table=$_POST['table'];
	$field=$_POST['field'];
	$value=$_POST['value'];
	$query="UPDATE `$table` SET `$field`='$value' WHERE `id`=$id";
	$result=mysql_query($query);
	exit();
}

if(isset($_GET['new_client'])){

	$name=$_POST['name'];
	$phone=$_POST['phone'];
	$number_vehicle=mb_strtoupper($_POST['number_vehicle']);
	$query="INSERT INTO `our_clients` (`name`,`phone`,`number_vehicle`) VALUES('$name','$phone','$number_vehicle'); ";
	mysql_query($query);
	$result=mysql_insert_id();
	process_new_row($result,"our_clients");
	sendSms("Ваш автомобиль ".$number_vehicle." добавлен в систему контроля срока действия пропусков. сайт: www.пропуск-мкад-москва.рф телефон: 84993993688",$phone);
	exit();
}

if(isset($_GET['get_dump'])){
	require_once("template/get_clients_dump.php");
	exit();
}
if(isset($_GET['new_reestr'])){
	$name=$_POST['name'];
	$phone=$_POST['phone'];
	$number_vehicle=mb_strtoupper($_POST['number_vehicle']);
	$query="INSERT INTO `application_reestr` (`client_name`,`client_phone`,`number_vehicle`) VALUES('$name','$phone','$number_vehicle'); ";
	mysql_query($query);
	$result=mysql_insert_id();
	process_new_reestr($result,"application_reestr");
	exit();
}

if(isset($_GET['reestr_to_client'])){
	$ids=$_POST['ids'];
	$table="application_reestr";
	$target_table="our_clients";
	for ($i=0; $i < count($ids); $i++) { 
		$id=$ids[$i];
		$query="INSERT INTO `$target_table` (`name`,`phone`,`number_vehicle`,`application_status`,`DK`,`payment`,`color`,`created_by`,`comment`) SELECT `client_name`,`client_phone`,`number_vehicle`,`status`,`neeed_dk`,`price`,`color`,`who_works`, CONCAT_WS('\n',`where_need`,`what_term`,`info`) FROM `$table` WHERE `id`=$id;";
		echo $query;
		mysql_query($query);
		$result=mysql_insert_id();
		$query="DELETE FROM `$table` WHERE `id`=$id";
		mysql_query($query);
		process_new_row($result,$target_table);
	}
	exit();
}
if(isset($_GET['client_to_reestr'])){
	$ids=$_POST['ids'];
	$table="our_clients";
	$target_table="application_reestr";
	for ($i=0; $i < count($ids); $i++) { 
		$id=$ids[$i];
		$query="INSERT INTO `$target_table` (`client_name`,`client_phone`,`number_vehicle`,`status`,`neeed_dk`,`price`,`color`,`who_works`,`info`,`active_till`)  SELECT `name`,`phone`,`number_vehicle`,`application_status`,`DK`,`price`,`color`,`created_by`,`comment`,`end_date` FROM `$table` WHERE `id`=$id;";
		echo $query;
		mysql_query($query);
		$result=mysql_insert_id();
		$query="DELETE FROM `$table` WHERE `id`=$id";
		mysql_query($query);
		process_new_row($result,$target_table);
	}
	exit();
}
?>