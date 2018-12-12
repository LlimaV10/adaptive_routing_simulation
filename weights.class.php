<?php
	Class Weights
	{
		private $arr = array();

		function __construct($arr)
		{
			foreach ($arr as $v) {
				$this->arr[] = $v;
			}
		}

		public function get_weight() {
			return ($this->arr[rand(0, count($this->arr) - 1)]);
		}
	}
?>