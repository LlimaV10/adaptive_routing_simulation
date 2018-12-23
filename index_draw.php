<!DOCTYPE html>
<html>
<head>
	<title>Network</title>
	<style type="text/css">
		body {
			background-color: gray;
		}
		.node {
			position: absolute;
			border-radius: 50%;
			padding: 5px 10px;
			background-color: black;
			font-size: 13px;
			color: white;
		}
		.workstation {
			position: absolute;
			padding: 10px 12px;
			margin-top: -25px;
			background-image: url('pc.png');
			background-size: cover;
			font-size: 13px;
			color: yellow;
		}
		.region {
			display: none;
			opacity: 0.5;
			position: absolute;
			border-radius: 10px;
			padding: 10px 15px;
			background-color: red;
			color: black;
		}
		.canv {
			position: absolute;
			left: 15px;
			top: 10px;
		}
		.form {
			opacity: 0.999;
			z-index: 2;
			position: absolute;
			color: black;
			background-color: lightgray;
			padding: 10px;
			border-radius: 10px;
			top: 350px;
			text-align: center;
			font-size: 18px;
		}
		.speed_slider {
			opacity: 0.999;
			z-index: 2;
			position: absolute;
			left: 50px;
			top: 170px;
			/*bottom: 50px;*/
			font-size: 18px;
		}
		.message {
			position: absolute;
			background-color: #00FF00;
			background-image: url("message.png");
			background-size: cover;
			margin-top: 3px;
			width: 30px;
			height: 20px;
			opacity: 0.5;
			z-index: 10;
			transition: 1s;
		}
		.menu_container {
			position: absolute;
			left: 20px;
			top: 50px;
			opacity: 0.9999;
			z-index: 20;
		}
		.menu_button {
			background-color: black;
			color: white;
			padding: 20px;
			text-align: center;
			border-radius: 10px;
			text-decoration: none;
			border: none;
			cursor: pointer;
		}
		.menu_button:hover {
			color: black;
			background-color: cyan;
			transition: 0.4s;
		}
		.stop_simulation {
			position: absolute;
			top: 380px;
			left: 25px;
			opacity: 0.9999;
			z-index: 20;
		}
		.sending_message_info {
			font-size: 20px;
			position: absolute;
			top: 440px;
			left: 20px;
			opacity: 0.9999;
			z-index: 20;
		}
	</style>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/script.js"></script>
	<script type="text/javascript">
		var sending_message = 0;
		var time = 0;
		var previous_message_time = 0;
		var messages_sent = 0;
		var packages_count = 0;
		var frequency;
		var count_messages;
		var type_of_routing;
	</script>
</head>
<body>

	<canvas id="canv" class="canv" width=<?php echo '"'.$width.'"' ?>
		height=<?php echo '"'.$height.'"' ?>>
	</canvas>
	<canvas id="canv2" class="canv" width=<?php echo '"'.$width.'"' ?>
		height=<?php echo '"'.$height.'"' ?>>
	</canvas>
	<div id="messages">
	</div>
	<div class="speed_slider">
		Режим маршрутизації:<br>
		<input type="radio" name="type_of_routing" value="Datagram" checked> Датаграмний<br>
		<input type="radio" name="type_of_routing" value="Logical"> Логічного каналу<br>
		<input type="radio" name="type_of_routing" value="Virtual"> Віртуального каналу<br>
		Встановити максимальну<br> швидкість симуляції:
		<input type="checkbox" name="max_speed" id="max_speed">
		<div>Швидкість:<span id="speed">1</span></div>
		<input type="range" min="1" max="10" value="1" id="speed_range">
	</div>
	<div class="menu_container">
		<a class="menu_button" href="index.php">Back to main menu</a>
		<br><br><br><br>
		<a class="menu_button" href="index_load.php">Reload</a>
	</div>
	<button id="stop_simulation_button" class="menu_button stop_simulation" style="margin-left: 40px; display: none;">Stop Simulation</button>
	<div id="sending_message_info" class="sending_message_info" style="display: none;">
	</div>

	<div class="form" id="send_message_form_div">
		<form id="send_message_form">
			Розмір повідомлення:<br>
			<input type="number" name="message_size" id="message_size"><br>
			Максимальний розмір пакету:<br>
			<input type="number" name="package_size" id="package_size"><br>
			Частота появи повідомлень:<br>
			<input type="number" name="frequency" id="frequency"><br>
			Кількість повідомлень:<br>
			<input type="number" name="count_messages" id="count_messages"><br>
			<br><br>
			<input type="button" id="send" value="Надіслати"><br>
		</form>
	</div>
	<script type="text/javascript">
		var c = document.getElementById("canv");
		var ctx = c.getContext("2d");
		ctx.strokeStyle = '#FFFFFF';
		var c = document.getElementById("canv2");
		var ctx2 = c.getContext("2d");
		ctx2.fillStyle = "#8F0000";
		ctx2.font = "italic 12pt Arial";
	</script>
	<?php
		$regions = $net->get_regions();
		$reg_x_def = $width / (count($regions) + 1);
		$reg_ys = array();
		$reg_xs = array();
		for ($i = 0; $i < count($regions); $i++) {
			$reg_xs[$i] = ($reg_x_def * ($i + 1));
			if ($i % 2 == 0)
				$reg_ys[$i] = $height / 2 - sqrt(-pow($width / 2, 2) +
					$width * $reg_xs[$i] + pow($width / 2, 2)
					- pow($reg_xs[$i], 2));
			else
				$reg_ys[$i] = $height / 2 + sqrt(-pow($width / 2, 2) +
					$width * $reg_xs[$i] + pow($width / 2, 2)
					- pow($reg_xs[$i], 2));
			$reg_ys[$i] = ($reg_ys[$i] + $height + 500) * $height / $width / 2.2;
		}
		for ($i = 0; $i < count($regions); $i++) {
			echo '<div class="region" style="left: '.($reg_xs[$i] - 15 - $height / 3).'px; top: '.$reg_ys[$i].'px;">Region '.$i.'</div>';
		}
		$vis_rad = $height / 5;
		for ($i = 0; $i < count($regions); $i++) {
			$nodes = $regions[$i]->get_nodes();
			$node_xdif = $vis_rad * 2 / (count($nodes));
			for ($j = 0; $j < count($nodes); $j++) {
				$node_x = $reg_xs[$i] - $vis_rad + $node_xdif * ($j ) + 1;
				if ($j % 2 == 0)
					$node_y = $reg_ys[$i] - sqrt(-pow($reg_xs[$i], 2) +
						2 * $reg_xs[$i] * $node_x + pow($vis_rad, 2) - pow($node_x, 2));
				else
					$node_y = $reg_ys[$i] + sqrt(-pow($reg_xs[$i], 2) +
						2 * $reg_xs[$i] * $node_x + pow($vis_rad, 2) - pow($node_x, 2));
				$nodes[$j]->x = $node_x;
				$nodes[$j]->y = $node_y;
				echo '<div id="n'.$nodes[$j]->get_id().'" class="node" style="left: '.$node_x.'px; top: '.$node_y.'px;">'.$nodes[$j]->get_id().'</div>';
			}
		}
		foreach (Workstation::$workstations as $workstation) {
			$node = $workstation->get_conns()[0]->get_another_node($workstation);
			$workstation->x = $node->x;
			$workstation->y = $node->y - 30;
			echo '<div id="n'.$workstation->get_id().'" class="workstation" style="left: '.$workstation->x.'px; top: '.$workstation->y.'px;">'.$workstation->get_id().'</div>';
		}
		?><script>
			var node1;
			var node2;
			var snode1;
			var snode2;
			<?php
		foreach (Connection::$connections as $conn) {
			?>
				
				node1 = document.getElementById(<?php echo '"n'.$conn->get_node1()->get_id().'"' ?>);
				node2 = document.getElementById(<?php echo '"n'.$conn->get_node2()->get_id().'"' ?>);
				snode1 = getComputedStyle(node1);
				snode2 = getComputedStyle(node2);
				ctx.moveTo(parseInt(snode1.left), parseInt(snode1.top));
				ctx.lineTo(parseInt(snode2.left), parseInt(snode2.top));
				<?php if ($conn->get_weight() != 0) {?>
					ctx2.fillText(<?php echo '"'.$conn->get_weight().'"' ?>, (parseInt(snode1.left) + parseInt(snode2.left)) / 2 - 6,
						(parseInt(snode1.top) + parseInt(snode2.top)) / 2);	
				<?php }?>
				//console.log(snode1.left + " " + snode1.top);
			<?php
		}
		?>
			ctx.stroke();
		</script>
</body>
</html>