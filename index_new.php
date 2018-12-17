<?php
	require_once("main.php");

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
	include("index_draw.php");
?>