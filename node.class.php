<?php
	require_once("graph.class.php");

	class Node extends Graph
	{
		static private $curr_id = 0;
		private $id;
		private $cons = array();
		private $ways = array();
		private $ways_connections = array();
		private $ways_weight = array();
		private $ways_transit = array();

		function __construct(){
			parent::__construct();
			$this->id = Node::$curr_id++;
		}

		public function get_id() {
			return ($this->id);
		}

		public function add_conn($conn) {
			$this->cons[] = $conn;
		}

		public function get_count_conns() {
			return (count($this->cons));
		}

		public function get_conns() {
			return ($this->cons);
		}

		public function have_connection_with($node) {
			foreach ($this->cons as $conn) {
				if (($conn->get_node1() == $this && $conn->get_node2() == $node) ||
					($conn->get_node2() == $this && $conn->get_node1() == $node))
					return (1);
			}
			return (0);
		}

		private function go_to_another_nodes($node, $weight, $arr_nodes, $workstation, $first_connection) {
			$arr_nodes[] = $node;
			if ($node == $workstation) {
				$this->ways[$workstation->get_id()][] = $arr_nodes[1];
				$this->ways_connections[$workstation->get_id()][] = $first_connection[0];
				$this->ways_weight[$workstation->get_id()][] = $weight;
				$this->ways_transit[$workstation->get_id()][] = count($arr_nodes) - 1;
			}
			foreach ($node->get_conns() as $connection) {
				$next_node = $connection->get_another_node($node);
				if (in_array($next_node, $arr_nodes))
					continue;
					//$first_connection[0] = $connection;
				if (count($first_connection) == 0)
					$this->go_to_another_nodes($next_node,
						$weight + $connection->get_weight(), $arr_nodes, $workstation, array($connection));
				else
					$this->go_to_another_nodes($next_node,
						$weight + $connection->get_weight(), $arr_nodes, $workstation, $first_connection);
			}
		}

		public function calculate_ways() {
			foreach (Workstation::$workstations as $workstation) {
				$this->go_to_another_nodes($this, 0, array(), $workstation, array());
				asort($this->ways_weight[$workstation->get_id()]);
				asort($this->ways_transit[$workstation->get_id()]);
			}
		}

		public function get_ways_weight($id) {
			return $this->ways_weight[$id];
		}
		public function get_ways_transit($id) {
			return $this->ways_transit[$id];
		}
		public function get_ways($id) {
			return $this->ways[$id];
		}
		public function get_ways_connections($id) {
			return $this->ways_connections[$id];
		}
	}
?>