<?php
namespace Kir\Dispatching\ServiceLocators;

use Kir\Dispatching\ServiceLocator;

class NullLocator implements ServiceLocator {
	/**
	 * @param string $paramName
	 * @return bool
	 */
	public function has($paramName) {
		return false;
	}

	/**
	 * @param string $service
	 * @param object $caller
	 * @return object
	 */
	public function resolve($service, $caller) {
		return null;
	}
}