<?php
//define database connection
define('DB_SERVER', '127.0.0.1');
define('DB_USERNAME', 'jefferson');
define('DB_PASSWORD', 'palceso');
define('DB_NAME', 'itc127-cs2b-2025');
//attempt to connect to the database
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
//check if the connection is unsuccessful
if($link === false)
{
	die("ERROR: Could not connect, " . mysqli_connect_error());
}
//set time zone
date_default_timezone_set("Asia/Manila");
?>