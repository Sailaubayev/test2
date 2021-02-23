<?php  
$host="31.31.196.209";
$username="u0665099_usersag";
$password="ssLEYPhDyrX9GxH";
$db="u0665099_usersaga-servis";
$connection=mysql_connect($host,$username,$password);
mysql_select_db($db,$connection);


function check_dbconn() {
	global $connection;
	$host="31.31.196.209";
	$username="u0665099_usersag";
	$password="ssLEYPhDyrX9GxH";
	$db="u0665099_usersaga-servis";

    if (!mysql_ping($connection)) {
        mysql_close($connection);
		$connection=mysql_connect($host,$username,$password);
		mysql_select_db($db,$connection);
        
    } 
}

?>