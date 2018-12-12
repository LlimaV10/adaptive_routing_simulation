<?php
	if (isset($_POST["node_id"])) {
		require_once("../weights.class.php");
		require_once("../cons.class.php");
		require_once("../network.class.php");
		require_once("../node.class.php");
		require_once("../region.class.php");
		require_once("../workstation.class.php");
		require_once("../workstation.class.php");
		require_once("../message.class.php");

		session_start();
		$net = $_SESSION['net'];
		Workstation::$workstations = $_SESSION['workstations'];
		Connection::$connections = $_SESSION['connections'];
		Message::$messages = $_SESSION['messages'];
		$node = $net->get_node_by_id($_POST["node_id"]);
		
	}
?>