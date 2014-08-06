<?php
namespace Kir\Dispatching\ServiceLocators;

use Kir\Dispatching\ServiceLocator;

class NullLocator implements ServiceLocator {
	/**
	 * @param string $service
	 * @param object $caller
	 * @return object
	 */
	public function resolve($service, $caller) {
		return null;
	}
}