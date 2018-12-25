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

		$package_size = $_POST["package_size"] - 8;

		if ($_POST["message_size"] % $package_size == 0)
			$count_packages = $_POST["message_size"] / $package_size;
		else
			$count_packages = floor($_POST["message_size"] / $package_size) + 1;

		$info_package = Info_package::get_info_package_by_id($_POST['info_id']);
		$first_node = $info_package->destination_workstation->get_conns()[0]->get_another_node($info_package->destination_workstation);
		$to_station = $info_package->first_node;

		$s = "";
		for ($i = 0; $i < $count_packages; $i++) {
			new Message($first_node, $to_station);
			$message = Message::$messages[$i + $_POST['packages_count']];
			$s .= "<div id='p".($i + $_POST['packages_count'])."' class='message' style='left: ".$message->x."px; top: ".$message->y."px;'></div>";
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