<?php
namespace Kir\Dispatching\Tools;

class InstanceCache {
	/**
	 * @var object[]
	 */
	private $instances=array();

	/**
	 * @param string $key
	 * @param callable $callback
	 * @return object
	 */
	public function get($key, callable $callback) {
		if(!array_key_exists($key, $this->instances)) {
			$this->instances[$key] = $callback($this);
		}
		return $this->instances[$key];
	}
} 