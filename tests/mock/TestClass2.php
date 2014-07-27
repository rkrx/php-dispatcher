<?php
namespace Kir\Http\Routing\Mock;

class TestClass2 {
	/**
	 * @var mixed
	 */
	public $test;

	/**
	 * @param $test
	 */
	public function __construct($test) {
		$this->test = $test;
	}

	/**
	 * @param mixed $a
	 * @param mixed $b
	 * @return mixed
	 */
	public function test($a, $b) {
		return $a + $b;
	}
}