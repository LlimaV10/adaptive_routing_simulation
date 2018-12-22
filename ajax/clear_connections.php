<?php
	require_once("../weights.class.php");
	require_once("../cons.class.php");
	require_once("../network.class.php");
	require_once("../node.class.php");
	require_once("../region.class.php");
	require_once("../workstation.class.php");
	require_once("../workstation.class.php");
	require_once("../message.class.php");
	
	session_start();
	Connection::$connections = $_SESSION['connections'];

	$is_message_on_connection = array();
	foreach (Connection::$connections as $connection) {
		$is_message_on_connection[$connection->get_id()] = 0;
	}
	$_SESSION['is_message_on_connection'] = $is_message_on_connection;
	$_SESSION['messages'] = array();
	echo json_encode(0);
?>