<?php
	require_once("node.class.php");
	require_once("cons.class.php");
	require_once("way.class.php");

	class Workstation extends Node
	{
		public static $workstations = array();
		// array: (0 - array of nodes, 1 - weights)
		//private $ways = array(0 => array(), 1 => array());
		// array: (0 - min weight nodes way, )
		// private $ways = array(0 => array)
		// private $min_weight_way = array();
		// private $min_weight_way_weight = array();
		// private $min_transit_way = array();
		// private $min_transit_way_weight = array();
		// private $way;

		function __construct($node) {
			parent::__construct();
			$connection = new Connection(new Weights(array(0)), $node, $this);
			$node->add_conn($connection);
			$this->add_conn($connection);
			self::$workstations[] = $this;
		}

		public static function get_station_by_id($id) {
			foreach (Workstation::$workstations as $workstation) {
				if ($workstation->get_id() == $id)
					return $workstation;
			}
			return Workstation::$workstations[0];
		}

		// public function initialize_ways() {
		// }

		// private function go_to_another_nodes($node, $weight, $arr_nodes, $workstation, $visited) {
		// 	$visited[] = $node;
		// 	$arr_nodes[] = $node->get_id();
		// 	if ($node == $workstation) {
		// 		if ((count($this->min_weight_way[$workstation->get_id()]) == 0)
		// 			|| $this->min_weight_way_weight[$workstation->get_id()] > $weight) {
		// 			$this->min_weight_way[$workstation->get_id()] = $arr_nodes;
		// 			$this->min_weight_way_weight[$workstation->get_id()] = $weight;
		// 		}
		// 		if ((count($this->min_transit_way[$workstation->get_id()]) == 0)
		// 			|| count($this->min_transit_way[$workstation->get_id()]) > count($arr_nodes)) {
		// 			$this->min_transit_way[$workstation->get_id()] = $arr_nodes;
		// 			$this->min_transit_way_weight[$workstation->get_id()] = $weight;
		// 		}
		// 	}
		// 	foreach ($node->get_conns() as $connection) {
		// 		$next_node = $connection->get_another_node($node);
		// 		if (in_array($next_node, $visited))
		// 			continue;
		// 		$this->go_to_another_nodes($next_node, $weight + 
		// 			$connection->get_weight(), $arr_nodes, $workstation, $visited);
		// 	}
		// }

		// public function calculate_ways() {
		// 	foreach (Workstation::$workstations as $workstation)
		// 		if ($workstation != $this) {
		// 			$this->min_weight_way[$workstation->get_id()] = array();
		// 			$this->min_transit_way[$workstation->get_id()] = array();
		// 			$visited = array($this);
		// 			$this->go_to_another_nodes($this, 0, array(), $workstation, $visited);
		// 		}
		// }

		// public function get_min_weight_way() {
		// 	return $this->min_weight_way;
		// }
		// public function get_min_weight_way_weight() {
		// 	return $this->min_weight_way_weight;
		// }
		// public function get_min_transit_way() {
		// 	return $this->min_transit_way;
		// }
		// public function get_min_transit_way_weight() {
		// 	return $this->min_transit_way_weight;
		// }
	}
?>