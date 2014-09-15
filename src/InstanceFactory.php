<?php
namespace Kir\Dispatching;

interface InstanceFactory {
	/**
	 * @param string $className
	 * @return object
	 */
	public function getInstance($className);

	/**
	 * @param string $className
	 * @return object
	 */
	public function createInstance($className);
} 