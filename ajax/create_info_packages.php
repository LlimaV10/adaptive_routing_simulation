<?php
	if (isset($_POST["message_size"]) && isset($_POST["package_size"]) && isset($_POST["frequency"]) && isset($_POST["count_messages"]) && $_POST["message_size"] && $_POST["package_size"] && $_POST["frequency"] != $_POST["count_messages"]) {
		require_once("../weights.class.php");
		require_once("../cons.class.php");
		require_once("../network.class.php");
		require_once("../node.class.php");
		require_once("../region.class.php");
		require_once("../workstation.class.php");
		require_once("../workstation.class.php");
		require_once("../message.class.php");
		require_once("../info_package.class.php");

		session_start();
		$net = $_SESSION['net'];
		Workstation::$workstations = $_SESSION['workstations'];
		Connection::$connections = $_SESSION['connections'];
		Message::$messages = $_SESSION['messages'];
		Info_package::$info_packages = $_SESSION['info_packages'];
		Info_package::$curr_id = $_SESSION['info_curr_id'];

		$from_station = random_int(Workstation::$workstations[0]->get_id(), Workstation::$workstations[count(Workstation::$workstations) - 1]->get_id());
		$to_station = $from_station;
		while ($to_station == $from_station)
			$to_station = random_int(Workstation::$workstations[0]->get_id(), Workstation::$workstations[count(Workstation::$workstations) - 1]->get_id());

		$workstation = Workstation::get_station_by_id($from_station);
		$workstation_connection = $workstation->get_conns()[0];
		$first_node = $workstation_connection->get_another_node($workstation);

		$message = new Info_package($first_node, Workstation::get_station_by_id($to_station), $workstation);
		$s = "<div id='p".($_POST['packages_count'])."' class='message' style='left: ".$message->x."px; top: ".$message->y."px; background-color: blue;'></div>";

		$result = array(
	    	'messages' => $s,
	    	'packages_count' => 1,
	    );
	    $_SESSION['info_packages'] = Info_package::$info_packages;
	    $_SESSION['info_curr_id'] = Info_package::$curr_id;
		$_SESSION['messages'] = Message::$messages;
	    // Переводим массив в JSON
	    echo json_encode($result); 
	}
	else
		echo json_encode(0); 
?>