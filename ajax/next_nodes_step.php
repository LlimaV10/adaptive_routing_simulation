<?php
	require_once("../weights.class.php");
	require_once("../cons.class.php");
	require_once("../network.class.php");
	require_once("../node.class.php");
	require_once("../region.class.php");
	require_once("../workstation.class.php");
	require_once("../workstation.class.php");
	require_once("../message.class.php");
	require_once("../graph.class.php");

	session_start();
	$net = $_SESSION['net'];
	Workstation::$workstations = $_SESSION['workstations'];
	Connection::$connections = $_SESSION['connections'];
	Message::$messages = $_SESSION['messages'];
	
	$s = "";
	foreach (Message::$messages as $message) {
		$z = $message->get_next_coords();
		$s .= "<div class='message' style='left: ".$message->x."px; top: ".$message->y."px;'></div>";
	}

	$all_sent = Message::all_messages_sent();

	if ($all_sent == 1)
		Message::$messages = array();

	$result = array(
    	'messages' => $s,
    	'all_sent' => $all_sent,
    	'z' => $z
    );

	$_SESSION['net'] = $net;
    $_SESSION['workstations'] = Workstation::$workstations;
    $_SESSION['connections'] = Connection::$connections;
    $_SESSION['messages'] = Message::$messages;

	echo json_encode($result);
?>