<?php
	require_once("cons.class.php");
	class Sput_con extends Connection
	{
		private $real_w;

		function __construct($weights, $n1, $n2) {
			parent::__construct($weights, $n1, $n2);
			$this->real_w = $this->weight * 3;
		}

		public function get_read_weight() {
			return $this->real_w;
		}
	}
?>