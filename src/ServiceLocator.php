<?php
namespace Kir\Dispatching;

interface ServiceLocator {
	/**
	 * @param string $paramName
	 * @return bool
	 */
	public function has($paramName);

	/**
	 * @param string $interfaceName
	 * @param object $caller
	 * @return object
	 */
	public function resolve($interfaceName, $caller = null);
}