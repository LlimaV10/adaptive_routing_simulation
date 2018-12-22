<?php
	require_once("main.php");
	
	$net = $_SESSION['net'];
	Workstation::$workstations = $_SESSION['workstations'];
	Connection::$connections = $_SESSION['connections'];

	$is_message_on_connection = array();
	foreach (Connection::$connections as $connection) {
		$is_message_on_connection[$connection->get_id()] = 0;
	}
	$_SESSION['is_message_on_connection'] = $is_message_on_connection;
	
	include("index_draw.php");
?>