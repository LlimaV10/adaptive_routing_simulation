<?php
	class Connection
	{
		private static $curr_id = 0;
		private $id;
		private $node1;
		private $node2;
		private $weight;
		//public  $is_message_on_connection;
		public static $connections = array();

		function __construct($weights, $n1, $n2) {
			$this->node1 = $n1;
			$this->node2 = $n2;
			$this->weight = $weights->get_weight();
			$this->id = Connection::$curr_id++;
			//$this->is_message_on_connection = 0;
			Connection::$connections[] = $this;
		}

		public function get_node1() {
			return ($this->node1);
		}
		
		public function get_node2() {
			return ($this->node2);
		}
		public function get_another_node($node) {
			if ($this->node1 == $node)
				return $this->node2;
			else
				return $this->node1;
		}
		public function get_weight() {
			return $this->weight;
		}
		public function get_id() {
			return $this->id;
		}
	}
?>