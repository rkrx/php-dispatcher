<?php
namespace Kir\Dispatching;

interface ServiceLocator {
	/**
	 * @param string $paramName
	 * @return bool
	 */
	public function has($paramName);

	/**
	 * @param string $service
	 * @param object $caller
	 * @return object
	 */
	public function resolve($service, $caller);
}