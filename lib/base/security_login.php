<?php

session_start();

if(!isset($_SESSION['userid']))
{
	header("Location: $dir"."login.php");
	echo "<script>window.top.location.href ='".$dir."login.php';</script>";
	exit;
}



//CEK TIMEOUT LOGIN
$time = $_SERVER['REQUEST_TIME'];

$timeout_duration = $expired_login;

if (isset($_SESSION['LAST_ACTIVITY']) && 
   ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    if(session_destroy())
	{
		header("Location: $dir"."login.php");
		exit;
	}
}

$_SESSION['LAST_ACTIVITY'] = $time;

if(isset($_GET['f']))
{

	if(!in_array($_GET['f'], $_SESSION['roleForm']))
	{
		if(session_destroy())
		{
			header("Location: $dir"."login.php");
			echo "<script>window.top.location.href ='".$dir."login.php';</script>";
			exit;
		}
		
	}
}





?>