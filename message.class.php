<?php
	require_once("graph.class.php");

	class Message extends Graph
	{
		public static $messages = array();
		public $from_node;
		public $dest_node;
		public $curr_connection;
		public $destination_workstation;
		private $previous_visited_node;
		public $weight_done;
		public $arrived;
		public static $is_message_on_connection = array();
		public static $is_message_on_connection_duplex = array();
		public $dont_go_to_node;


		function __construct($node, $dest) {
			$this->dest_node = $node;
			$this->from_node = $node;
			$this->destination_workstation = $dest;
			$this->x = $node->x;
			$this->y = $node->y;
			$this->curr_connection = 0;
			$this->weight_done = -1;
			$this->arrived = 0;
			$this->dont_go_to_node = -1;
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
			//$this->previous_visited_node = $this->dest_node;
			$prev_node = $this->from_node;
			$this->from_node = $this->dest_node;
			if ($this->curr_connection)
				Message::$is_message_on_connection[$this->curr_connection->get_id()] = 0;
			$this->curr_connection = 0;
			if ($this->dest_node == $this->destination_workstation) {
				$this->arrived = 1;
				// if ($this->curr_connection)
				// 	$this->curr_connection->is_message_on_connection = 0;
				return;
			}
			$ways = $this->from_node->get_ways($this->destination_workstation->get_id());
			$ways_connections = $this->from_node->get_ways_connections($this->destination_workstation->get_id());
			$ways_weights = $this->from_node->get_ways_weight($this->destination_workstation->get_id());
			foreach ($ways_weights as $k => $weight) {
				$first_key = $k;
				break;
			}
			$z = 0;
			foreach ($ways_weights as $k => $weight) {
				$z++;
				if ($this->dont_go_to_node == $ways[$k]->get_id())
					continue;
				//if ($ways_connections[$k]->is_message_on_connection == 0) {
				if (Message::$is_message_on_connection[$ways_connections[$k]->get_id()] == 0) {
					if ($k == $first_key || $weight <= $ways_weights[$first_key] + $ways_connections[$first_key]->get_weight() || $this->dont_go_to_node == $ways[$first_key]->get_id())
					{
						$this->dest_node = $ways[$k];
						$this->curr_connection = $ways_connections[$k];
						//$this->curr_connection->is_message_on_connection = 1;
						Message::$is_message_on_connection[$this->curr_connection->get_id()] = 1;
						$this->weight_done = 1;
						$this->get_coords();
						$this->dont_go_to_node = -1;
						if ($this->dest_node->get_id() == $prev_node->get_id())
							$this->dont_go_to_node = $this->from_node->get_id();
						return;
					}
					else
						break;
				}
			}
			$this->weight_done = -1;
			//$this->dont_go_to_node = -1;
			return $z;
		}

		public function get_next_coords() {
			if ($this->weight_done == -1 || !$this->curr_connection || $this->weight_done >= $this->curr_connection->get_weight()) {
				$this->get_next_node();
			}
			else {
				$this->weight_done += 1;
				$this->get_coords();
			}
			return 0;
		}

		private function get_next_node_virtual() {
			$this->previous_visited_node = $this->dest_node;
			$prev_node = $this->from_node;
			$this->from_node = $this->dest_node;
			if ($this->curr_connection)
				Message::$is_message_on_connection[$this->curr_connection->get_id()] = 0;
			$this->curr_connection = 0;
			if ($this->dest_node == $this->destination_workstation) {
				$this->arrived = 1;
				return;
			}
			$z = 0;
			$ways = $this->from_node->get_ways($this->destination_workstation->get_id());
			$ways_connections = $this->from_node->get_ways_connections($this->destination_workstation->get_id());
			$ways_weights = $this->from_node->get_ways_weight($this->destination_workstation->get_id());
			foreach ($ways_weights as $k => $weight) {
				if (Message::$is_message_on_connection[$ways_connections[$k]->get_id()] == 0) {
					$this->dest_node = $ways[$k];
					$this->curr_connection = $ways_connections[$k];
					Message::$is_message_on_connection[$this->curr_connection->get_id()] = 1;
					$this->weight_done = 1;
					$this->get_coords();
					return;
				}
				break;
			}
			$this->weight_done = -1;
			return $z;
		}

		public function get_next_coords_virtual() {
			if ($this->weight_done == -1 || !$this->curr_connection || $this->weight_done >= $this->curr_connection->get_weight()) {
				$this->get_next_node_virtual();
			}
			else {
				$this->weight_done += 1;
				$this->get_coords();
			}
			return 0;
		}

		public static function all_messages_sent($messages) {
			foreach ($messages as $message) {
				if ($message->arrived == 0)
					return 0;
			}
			return 1;
		}

		// public function set_dest_workstation($dest) {
		// 	$this->destination_workstation = $dest;
		// }

		private function get_next_node_duplex() {
			if ($this->curr_connection)
				Message::$is_message_on_connection_duplex[$this->curr_connection->get_id()][$this->from_node->get_id()] = 0;
			$prev_node = $this->from_node;
			$this->from_node = $this->dest_node;
			$this->curr_connection = 0;
			if ($this->dest_node == $this->destination_workstation) {
				$this->arrived = 1;
				return;
			}
			$ways = $this->from_node->get_ways($this->destination_workstation->get_id());
			$ways_connections = $this->from_node->get_ways_connections($this->destination_workstation->get_id());
			$ways_weights = $this->from_node->get_ways_weight($this->destination_workstation->get_id());
			foreach ($ways_weights as $k => $weight) {
				$first_key = $k;
				break;
			}
			$z = 0;
			foreach ($ways_weights as $k => $weight) {
				$z++;
				if ($this->dont_go_to_node == $ways[$k]->get_id())
					continue;
				if (!isset(Message::$is_message_on_connection_duplex[$ways_connections[$k]->get_id()][$this->from_node->get_id()]) || Message::$is_message_on_connection_duplex[$ways_connections[$k]->get_id()][$this->from_node->get_id()] == 0) {
					if ($k == $first_key || $weight <= $ways_weights[$first_key] + $ways_connections[$first_key]->get_weight() || $this->dont_go_to_node == $ways[$first_key]->get_id())
					{
						$this->dest_node = $ways[$k];
						$this->curr_connection = $ways_connections[$k];
						Message::$is_message_on_connection_duplex[$this->curr_connection->get_id()][$this->from_node->get_id()] = 1;
						$this->weight_done = 1;
						$this->get_coords();
						$this->dont_go_to_node = -1;
						if ($this->dest_node->get_id() == $prev_node->get_id())
							$this->dont_go_to_node = $this->from_node->get_id();
						return;
					}
					else
						break;
				}
			}
			$this->weight_done = -1;
			//$this->dont_go_to_node = -1;
			return $z;
		}

		private function get_next_node_virtual_duplex() {
			if ($this->curr_connection)
				Message::$is_message_on_connection_duplex[$this->curr_connection->get_id()][$this->from_node->get_id()] = 0;
			$this->previous_visited_node = $this->dest_node;
			$prev_node = $this->from_node;
			$this->from_node = $this->dest_node;
			$this->curr_connection = 0;
			if ($this->dest_node == $this->destination_workstation) {
				$this->arrived = 1;
				return;
			}
			$z = 0;
			$ways = $this->from_node->get_ways($this->destination_workstation->get_id());
			$ways_connections = $this->from_node->get_ways_connections($this->destination_workstation->get_id());
			$ways_weights = $this->from_node->get_ways_weight($this->destination_workstation->get_id());
			foreach ($ways_weights as $k => $weight) {
				if (!isset(Message::$is_message_on_connection_duplex[$ways_connections[$k]->get_id()][$this->from_node->get_id()]) || Message::$is_message_on_connection_duplex[$ways_connections[$k]->get_id()][$this->from_node->get_id()] == 0) {
					$this->dest_node = $ways[$k];
					$this->curr_connection = $ways_connections[$k];
					Message::$is_message_on_connection_duplex[$this->curr_connection->get_id()][$this->from_node->get_id()] = 1;
					$this->weight_done = 1;
					$this->get_coords();
					return;
				}
				break;
			}
			$this->weight_done = -1;
			return $z;
		}

		public function get_next_coords_duplex() {
			if ($this->weight_done == -1 || !$this->curr_connection || $this->weight_done >= $this->curr_connection->get_weight()) {
				$this->get_next_node_duplex();
			}
			else {
				$this->weight_done += 1;
				$this->get_coords();
			}
			return 0;
		}

		public function get_next_coords_virtual_duplex() {
			if ($this->weight_done == -1 || !$this->curr_connection || $this->weight_done >= $this->curr_connection->get_weight()) {
				$this->get_next_node_virtual_duplex();
			}
			else {
				$this->weight_done += 1;
				$this->get_coords();
			}
			return 0;
		}

		public static function get_routing_tables($routing_table_station) {
			$routing_station = Workstation::get_station_by_id($routing_table_station);
			if (!$routing_station)
				return "";
			$s = "";
			foreach (Workstation::$workstations as $workstation) {
				if ($workstation != $routing_station) {
					$s .= "Workstation ".$routing_table_station;
					$node = $routing_station->get_conns()[0]->get_another_node($routing_station);
					//$s .= "->Node ".$node->get_id();
					while ($node != $workstation) {
						$s .= "->Node ".$node->get_id();
						foreach ($node->get_ways_weight($workstation->get_id()) as $k => $v) {
							$node = $node->get_ways($workstation->get_id())[$k];
							break;
						}
					}
					$s .= "->Workstation ".$node->get_id()."<br>";
				}
			}
			return $s;
		}

	}
?>