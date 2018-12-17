<!DOCTYPE html>
<html>
<head>
	<title>Course work</title>
	<style type="text/css">
		body {
			background-color: gray;
			background-image: url("net5.jpg");
			/*background-size: cover;*/
		}
		.menu_container {
			width: 100%;
			margin-top: 30vh;
			text-align: center;
		}
		.menu_button {
			background-color: black;
			color: white;
			padding: 20px;
			text-align: center;
			border-radius: 10px;
			text-decoration: none;
		}
		.menu_button:hover {
			color: black;
			background-color: cyan;
			transition: 0.4s;
		}
	</style>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/script.js"></script>
</head>
<body>
	<div class="menu_container">
		<a class="menu_button" href="index_new.php">New Network</a>
		<br><br><br><br><br>
		<a class="menu_button" href="index_load.php">Load Network from Session</a>
	</div>
</body>
</html>