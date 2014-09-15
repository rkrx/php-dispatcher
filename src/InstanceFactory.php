<?php
namespace Kir\Dispatching;

interface InstanceFactory {
	/**
	 * @param ServiceLocator $serviceLocator
	 * @param string $className
	 * @return object
	 */
	public function getInstance(ServiceLocator $serviceLocator, $className);

	/**
	 * @param ServiceLocator $serviceLocator
	 * @param string $className
	 * @return object
	 */
	public function createInstance(ServiceLocator $serviceLocator, $className);
} 