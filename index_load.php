<?php
	require_once("main.php");
	
	$net = $_SESSION['net'];
	Workstation::$workstations = $_SESSION['workstations'];
	Connection::$connections = $_SESSION['connections'];
	include("index_draw.php");
?>