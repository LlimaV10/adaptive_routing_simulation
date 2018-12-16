<?php
	require_once("weights.class.php");
	require_once("cons.class.php");
	require_once("network.class.php");
	require_once("node.class.php");
	require_once("region.class.php");
	require_once("workstation.class.php");
	require_once("workstation.class.php");
	require_once("message.class.php");

	$width = 1200;
	$height = 550;

	// $weights = new Weights(array(2, 4, 5, 9, 10, 12, 18, 21, 23, 26, 28, 32));

	// $net = new Network(3.5, $weights, 2);
	// $net->add_region(new Region(8));
	// $net->add_region(new Region(8));
	// $net->add_region(new Region(8));

	// $net->connect_all();
	// $net->get_nodes_ways();

	session_start();
	// $_SESSION['net'] = $net;
	// $_SESSION['workstations'] = Workstation::$workstations;
	// $_SESSION['connections'] = Connection::$connections;
	
	$net = $_SESSION['net'];
	Workstation::$workstations = $_SESSION['workstations'];
	foreach ($_SESSION['connections'] as $connection) {
		$connection->is_message_on_connection = 0;
	}
	Connection::$connections = $_SESSION['connections'];
	// require_once("data/save_data.php");
	//require_once("data/load_data.php");
?>