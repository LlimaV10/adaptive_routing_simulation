<?php
	//require("index.php");

	if (isset($_POST["message_size"]) && isset($_POST["package_size"]) && isset($_POST["from_station"]) && isset($_POST["to_station"]) && $_POST["message_size"] && $_POST["package_size"] && $_POST["from_station"] != $_POST["to_station"]) {

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

		if ($_POST["message_size"] % $_POST["package_size"] == 0)
			$count_packages = $_POST["message_size"] / $_POST["package_size"];
		else
			$count_packages = floor($_POST["message_size"] / $_POST["package_size"]) + 1;
		// // Формируем массив для JSON ответа
		$workstation = Workstation::get_station_by_id($_POST["from_station"]);
		$first_node = $workstation->get_conns()[0]->get_another_node($workstation);

		$s = "";
		for ($i = 0; $i < $count_packages; $i++) {
			new Message($first_node, Workstation::get_station_by_id($_POST["to_station"]));
			$message = Message::$messages[$i];
			$s .= "<div id='p".$i."' class='message' style='left: ".$message->x."px; top: ".$message->y."px;'></div>";
		}
	    $result = array(
	    	'messages' => $s,
	    	'packages_count' => $count_packages,
	    ); 
	    $_SESSION['messages'] = Message::$messages;
	    // Переводим массив в JSON
	    echo json_encode($result); 
	}
	else
		echo json_encode(0); 
?>