<?php
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

	$count_packages = count(Message::$messages) + count(Info_package::$info_packages);
	$s = "";
	if ($_POST['type_of_routing'] == "Datagram")
	{
		$s .= "<tr><td>Датаграмний режим</td></tr>";
		$size_of_header = 20;
	}
	else if ($_POST['type_of_routing'] == "Logical")
	{
		$s .= "<tr><td>Режим логічного каналу</td></tr>";
		$size_of_header = 8;
	}
	else if ($_POST['type_of_routing'] == "Virtual")
	{
		$s .= "<tr><td>Режим віртуального каналу</td></tr>";
		$size_of_header = 8;
	}
	$s .= "<tr><td>Час роботи:</td><td>".$_POST['time']." тактів</td></tr>";
	$s .= "<tr><td>Повідомлень:</td><td>".$_POST['messages']."</td></tr>";
	$s .= "<tr><td>   за 1 такт:</td><td>".($_POST['messages'] / $_POST['time'])."</td></tr>";
	$s .= "<tr><td>Пакетів:</td><td>".$count_packages."</td></tr>";
	$s .= "<tr><td>   за 1 такт:</td><td>".($count_packages / $_POST['time'])."</td></tr>";
	$s .= "<tr><td>   на 1 пов.:</td><td>".($count_packages / $_POST['messages'])."</td></tr>";
	$s .= "<tr><td>Байтів:</td><td>".($_POST['messages'] * $_POST['message_size'] + $count_packages * $size_of_header)."</td></tr>";
	$s .= "<tr><td>   за 1 такт:</td><td>".(($_POST['messages'] * $_POST['message_size']) / $_POST['time'] )."</td></tr>";
	//$s .= "<tr><td>Каналів коммутовано:</td><td>ХЗ</td></tr>";

	$result = array(
		'table' => $s
    ); 
    echo json_encode($result); 
?>