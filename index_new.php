<?php
	require_once("main.php");

	session_destroy();
	session_start();
	$weights = new Weights(array(2, 4, 5, 9, 10, 12, 18, 21, 23, 26, 28, 32));

	$net = new Network(3.5, $weights, 2);
	$net->add_region(new Region(8));
	$net->add_region(new Region(8));
	$net->add_region(new Region(8));

	$net->connect_all();
	$net->get_nodes_ways();

	$_SESSION['net'] = $net;
	$_SESSION['workstations'] = Workstation::$workstations;
	$_SESSION['connections'] = Connection::$connections;

	$is_message_on_connection = array();
	foreach (Connection::$connections as $connection) {
		$is_message_on_connection[$connection->get_id()] = 0;
	}
	$_SESSION['is_message_on_connection'] = $is_message_on_connection;

	include("index_draw.php");
?>