<?php
	require_once("graph.class.php");

	class Message extends Graph
	{
		public static $messages = array();
		private $from_node;
		private $dest_node;
		private $curr_connection;
		private $destination_workstation;
		private $visited_nodes;
		private $previous_visited_node;
		private $weight_done;
		public $arrived;

		function __construct($node, $dest) {
			$this->dest_node = $node;
			$this->from_node = $node;
			$this->destination_workstation = $dest;
			$this->x = $node->x;
			$this->y = $node->y;
			$this->curr_connection;
			$this->visited_nodes = array();
			$this->weight_done = -1;
			$this->arrived = 0;
			Message::$messages[] = $this;
		}

		private function get_coords() {
			if ($this->curr_connection->get_weight() != 0) {
				$this->x = $this->from_node->x +
					($this->dest_node->x - $this->from_node->x) / $this->curr_connection->get_weight() *
					$this->weight_done;
				$this->y = $this->from_node->y +
					($this->dest_node->y - $this->from_node->y) / $this->curr_connection->get_weight() *
					$this->weight_done;
			}
		}

		private function get_next_node() {
			$this->visited_nodes[] = $this->dest_node;
			//$this->previous_visited_node = $this->dest_node;
			$this->from_node = $this->dest_node;
			if ($this->curr_connection)
				$this->curr_connection->is_message_on_connection = 0;
			if ($this->dest_node == $this->destination_workstation) {
				$this->arrived = 1;
				// if ($this->curr_connection)
				// 	$this->curr_connection->is_message_on_connection = 0;
				return;
			}
			$ways = $this->from_node->get_ways($this->destination_workstation->get_id());
			$ways_connections = $this->from_node->get_ways_connections($this->destination_workstation->get_id());

			$z = 0;
			foreach ($this->from_node->get_ways_weight($this->destination_workstation->get_id())
				as $k => $v) {
				$z++;
				if (/*!in_array($ways[$k], $this->visited_nodes)*/ 
					/*(!$this->previous_visited_node || $this->previous_visited_node != $ways[$k])
						&&*/ $ways_connections[$k]->is_message_on_connection == 0) {
					$this->dest_node = $ways[$k];
					$this->curr_connection = $ways_connections[$k];
					$this->curr_connection->is_message_on_connection = 1;
					$this->weight_done = 1;
					$this->get_coords();
					return;
				}
			}
			$this->weight_done = -1;

			return $z;
		}

		public function get_next_coords() {
			if ($this->weight_done == -1 || $this->weight_done >= $this->curr_connection->get_weight()) {
				return $this->get_next_node();
			}
			else {
				$this->weight_done += 1;
				$this->get_coords();
			}
		}

		public static function all_messages_sent() {
			foreach (Message::$messages as $message) {
				if ($message->arrived == 0)
					return 0;
			}
			return 1;
		}
	}
?>