<?php
	require_once("node.class.php");

	class Region
	{
		static private $curr_id = 0;
		private $id;
		private $nodes = array();

		function __construct($nodes_count) {
			$this->id = Region::$curr_id++;
			for ($i = 0; $i < $nodes_count; $i++) {
				$this->nodes[] = new Node();
			}
		}

		public function get_nodes() {
			return ($this->nodes);
		}

		public function get_random_node($fnode) {
			if (!$fnode)
				return ($this->nodes[rand(0, count($this->nodes) - 1)]);
			while ($fnode == ($rnode = $this->nodes[rand(0, count($this->nodes) - 1)]))
				;
			return ($rnode);
		}
		// public function add_node($node) {
		// 	$this->nodes[] = $node;
		// }
	}
?>