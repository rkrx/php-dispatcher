<?php
namespace Kir\Http\Routing\ServiceLocators;

use Kir\Http\Routing\ServiceLocator;

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