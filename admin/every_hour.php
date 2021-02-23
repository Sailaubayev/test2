<?php
set_include_path(dirname(__FILE__));
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
	require_once("conf/config.php");
	require_once("util/util.php");
	ini_set('max_execution_time', 50*60);
	$query="SELECT * FROM `our_clients`";
	$q="SELECT * FROM `other_clients` WHERE `delivery`=1 AND DATEDIFF(NOW(),`addition_date`)<30";
	$result=mysql_query($query);
	$res=array();
	while ($row=mysql_fetch_assoc($result)){
		$res[]=$row;
	}
	$resultOther=mysql_query($q);
	while ($row=mysql_fetch_assoc($resultOther)) {
		$res[]=$row;
	}
	$ind=0;
	foreach ($res as $key => $value) {
		$ind++;
		sleep(rand(0,3));
		processRow($value);
		if($ind==10){
	//		exit();
		}
	}
?>