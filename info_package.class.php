<?php
	require_once("message.class.php");

	Class Info_package extends Message
	{
		public static $info_packages = array();
		public $first_node;
		public $count_arrives;
		public $stop_dublicating_please;
		public static $curr_id = 0;
		private $id;

		function __construct($node, $dest, $start_workstation) {
			parent::__construct($node, $dest);
			$this->first_node = $start_workstation;
			$this->count_arrives = 0;
			$this->id = Info_package::$curr_id++;
			$this->stop_dublicating_please = 0;
			Info_package::$info_packages[] = $this;
		}

		public function get_id() {
			return $this->id;
		}

		public static function get_info_package_by_id($id) {
			foreach (Info_package::$info_packages as $package) {
				if ($package->get_id() == $id)
					return $package;
			}
		}
	}

?>