<?php
	require_once("cons.class.php");
	require_once("workstation.class.php");
	//require_once("region.class.php");

	Class Network
	{
		private $regions = array();
		private $net_deg;
		private $ni_node;
		private $weights;

		function __construct($deg, $weights, $ni_node) {
			$this->net_deg = $deg;
			$this->weights = $weights;
			$this->ni_node = $ni_node;
		}

		public function add_region($reg) {
			$this->regions[] = $reg;
		}
		public function get_regions() {
			return ($this->regions);
		}
		public function get_count_sput(){
			return $this->count_sput;
		}
		private function get_average_degree() {
			$deg = 0;
			$count = 0;
			foreach ($this->regions as $reg) {
				foreach ($reg->get_nodes() as $node) {
					$deg += $node->get_count_conns();
					$count++;
				}
			}
			return ($deg / $count);
		}

		public function connect_all() {
			for ($i = 0; $i < count($this->regions); $i++) {
				for ($j = $i + 1; $j < count($this->regions); $j++) {
					$node_prev = $this->regions[$i]->get_random_node(0);
					$node_curr = $this->regions[$j]->get_random_node(0);
					$ncon = new Connection($this->weights, $node_prev, $node_curr, 0);
					$node_prev->add_conn($ncon);
					$node_curr->add_conn($ncon);
				}
			}
			foreach ($this->regions as $reg) {
				foreach ($reg->get_nodes() as $node) {
					// if ($node->get_count_conns() > 0)
					// 	continue;
					// while (($snode = $reg->get_random_node($node))->get_count_conns() > 0)
					// 	;
					while ($node->have_connection_with($snode = $reg->get_random_node($node)))
						;
					//$snode = $reg->get_random_node($node);
					$ncon = new Connection($this->weights, $node, $snode, 0);
					$node->add_conn($ncon);
					$snode->add_conn($ncon);
				}
			}
			foreach ($this->regions as $reg) {
				$i = 0;
				$nodes = $reg->get_nodes();
				while ($i < count($nodes)) {
					$w = new Workstation($nodes[$i]);
					//print($w->get_id()."<br>");
					$i += $this->ni_node;
				}
			}

			$average = $this->get_average_degree();

			$count_sput = 0;

			while ($average < $this->net_deg) {
				$reg = $this->regions[rand(0, count($this->regions) - 1)];
				$node1 = $reg->get_random_node(0);
				if ($node1->get_count_conns() >= count($reg->get_nodes()))
					continue;
				$node2 = $reg->get_random_node($node1);
				if ($node1->have_connection_with($node2) > 0)
					continue;
				if ($count_sput <= 0)
					$ncon = new Connection($this->weights, $node1, $node2, 0);
				else
				{
					$ncon = new Connection($this->weights, $node1, $node2, 1);
					$count_sput--;
				}
				$node1->add_conn($ncon);
				$node2->add_conn($ncon);
				$average = $this->get_average_degree();
			}
			//print($this->get_average_degree());
		}

		public function some() {
			print($this->regions[0]->get_nodes()[0]->get_id());
			$this->regions[0]->get_nodes()[0]->get_ways();
		}

		public function get_nodes_ways() {
			foreach ($this->regions as $region) {
				foreach ($region->get_nodes() as $node) {
					$node->calculate_ways();
				}
			}
		}

		public function get_node_by_id($id) {
			foreach ($this->regions as $reg) {
				foreach ($reg->get_nodes() as $node) {
					if ($node->get_id() == $id)
						return $node;
				}
			}
		}

	}
?>