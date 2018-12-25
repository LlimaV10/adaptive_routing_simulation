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
	require_once("../info_package.class.php");
	
	session_start();
	// $net = $_SESSION['net'];
	// Workstation::$workstations = $_SESSION['workstations'];
	// Connection::$connections = $_SESSION['connections'];
	// Message::$messages = $_SESSION['messages'];
	
	//$s = "";
	Message::$is_message_on_connection = $_SESSION['is_message_on_connection'];
	Message::$is_message_on_connection_duplex = $_SESSION['is_message_on_connection_duplex'];
	$messages_left = array();
	$messages_top = array();
	$info_packages_done = array();

	//Message::$lock = 0;
	$z = 0;
	for ($i = 0; $i < $_POST['speed']; $i++) {
		foreach ($_SESSION['messages'] as $message) {
			if ($_POST['type_of_canal'] == "half-duplex")
			{
				if ($_POST['virtual'] != 1)
					$z = $message->get_next_coords();
				else
					$z = $message->get_next_coords_virtual();
			}
			else
			{
				if ($_POST['virtual'] != 1)
					$z = $message->get_next_coords_duplex();
				else
					$z = $message->get_next_coords_virtual_duplex();
			}
		}
		foreach ($_SESSION['info_packages'] as $info) {
			if ($info->arrived && !$info->stop_dublicating_please) {
				if ($info->count_arrives < 1)
				{
					$info->arrived = 0;
					$dest = $info->destination_workstation;
					$info->destination_workstation = $info->first_node;
					$info->first_node = $dest;
					$dest = $dest->get_conns()[0]->get_another_node($dest);

					$info->dest_node = $dest;
					$info->from_node = $dest;
					$info->x = $dest->x;
					$info->y = $dest->y;
					$info->curr_connection = 0;
					$info->weight_done = -1;
					$info->arrived = 0;
					$info->dont_go_to_node = -1;

					$info->count_arrives++;
				}
			}
		}
	}
	foreach ($_SESSION['info_packages'] as $info) {
		if ($info->arrived && !$info->stop_dublicating_please) {
			if ($info->count_arrives >= 1)
			{
				$info->stop_dublicating_please = 1;
				$info_packages_done[] = $info->get_id();
			}
		}
	}

	$_SESSION['is_message_on_connection'] = Message::$is_message_on_connection;
	$_SESSION['is_message_on_connection_duplex'] = Message::$is_message_on_connection_duplex;
	foreach ($_SESSION['messages'] as $message) {
		$messages_left[] = $message->x."px";
		$messages_top[] = $message->y."px";
	}
	if ($_POST['messages_sent'] < $_POST['count_messages'])
		$all_sent = 0;
	else
		$all_sent = Message::all_messages_sent($_SESSION['messages']);

	// if ($all_sent == 1)
	// 	$_SESSION['messages'] = array();

	$result = array(
    	//'messages' => $s,
    	'messages_left' => $messages_left,
    	'messages_top' => $messages_top,
    	'all_sent' => $all_sent,
    	'z' => $z,
    	'info_packages_done' => $info_packages_done
    );

	// $_SESSION['net'] = $net;
 //    $_SESSION['workstations'] = Workstation::$workstations;
 //    $_SESSION['connections'] = Connection::$connections;
 //    $_SESSION['messages'] = Message::$messages;

	echo json_encode($result);
}
?>