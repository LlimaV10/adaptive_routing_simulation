<?php
	if (isset($_POST['speed'])) {
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
	// $net = $_SESSION['net'];
	// Workstation::$workstations = $_SESSION['workstations'];
	// Connection::$connections = $_SESSION['connections'];
	// Message::$messages = $_SESSION['messages'];
	
	//$s = "";
	Message::$is_message_on_connection = $_SESSION['is_message_on_connection'];
	$messages_left = array();
	$messages_top = array();
	//Message::$lock = 0;
	for ($i = 0; $i < $_POST['speed']; $i++) {
		foreach ($_SESSION['messages'] as $message) {
			$z = $message->get_next_coords();
			//$s .= "<div class='message' style='left: ".$message->x."px; top: ".$message->y."px;'></div>";
		}
	}
	$_SESSION['is_message_on_connection'] = Message::$is_message_on_connection;
	foreach ($_SESSION['messages'] as $message) {
		$messages_left[] = $message->x."px";
		$messages_top[] = $message->y."px";
	}
	if ($_POST['messages_sent'] < $_POST['count_messages'])
		$all_sent = 0;
	else
		$all_sent = Message::all_messages_sent($_SESSION['messages']);

	if ($all_sent == 1)
		$_SESSION['messages'] = array();

	$result = array(
    	//'messages' => $s,
    	'messages_left' => $messages_left,
    	'messages_top' => $messages_top,
    	'all_sent' => $all_sent,
    	'z' => $z
    );

	// $_SESSION['net'] = $net;
 //    $_SESSION['workstations'] = Workstation::$workstations;
 //    $_SESSION['connections'] = Connection::$connections;
 //    $_SESSION['messages'] = Message::$messages;

	echo json_encode($result);
}
?>