<?php
	class Way
	{
		private $nodes_array = array();
		private $weight;

		function __construct($curr_arr, $curr_weight) {
			$this->nodes_array = $curr_arr;
			$this->weight = $curr_weight;
		}
	}
?>