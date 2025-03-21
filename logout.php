<?php
//destroy session
session_start();
unset($_SESSION['username']);
unset($_SESSION['usertype']);

//redirect to the login page
header("location: login.php");
?>