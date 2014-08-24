<?php
namespace Kir\Dispatching\Mock;

class TestClass3 {
	/**
	 * @var mixed
	 */
	public $test;

	/**
	 * @param $test
	 */
	public function __construct(TestClass2 $test) {
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