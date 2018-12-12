<?php
	$file = fopen("network_data", 'w');
	$s = serialize($net);
	fwrite($file, $s);
	fclose($file);

	// $file = fopen("network_data", 'w');
	// $s = serialize($net);
	// fwrite($file, $s);
	// fclose($file);
?>