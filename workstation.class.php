<?php
	require_once("node.class.php");
	require_once("cons.class.php");
	require_once("way.class.php");

	class Workstation extends Node
	{
		public static $workstations = array();

		function __construct($node) {
			parent::__construct();
			$connection = new Connection(new Weights(array(0)), $node, $this, 0);
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
	}
?>