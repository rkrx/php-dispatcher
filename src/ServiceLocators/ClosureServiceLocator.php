<?php
namespace Kir\Dispatching\ServiceLocators;

use Closure;
use Kir\Dispatching\ServiceLocator;

class ClosureServiceLocator implements ServiceLocator {
	/**
	 * @var callable
	 */
	private $closure;

	/**
	 * @param callable $closure
	 */
	function __construct(Closure $closure) {
		$this->closure = $closure;
	}

	/**
	 * @param string $service
	 * @param object $caller
	 * @return object
	 */
	public function resolve($service, $caller) {
		return call_user_func($this->closure, $service, $caller);
	}
}