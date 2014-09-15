<?php
namespace Kir\Dispatching\InstanceFactories;

use Kir\Dispatching\InstanceFactory;
use Kir\Dispatching\ServiceLocator;
use Kir\Dispatching\Tools;
use Kir\Dispatching\Tools\ParameterResolver;
use ReflectionClass;

class CommonInstanceFactory implements InstanceFactory {
	/**
	 * @var object[]
	 */
	private $instances = array();

	/**
	 * @var ParameterResolver
	 */
	private $parameterResolver;

	/**
	 * @var ServiceLocator
	 */
	private $serviceLocator;

	/**
	 * @param ServiceLocator $serviceLocator
	 * @param ParameterResolver $parameterResolver
	 */
	public function __construct(ServiceLocator $serviceLocator, ParameterResolver $parameterResolver) {
		$this->parameterResolver = $parameterResolver;
		$this->serviceLocator = $serviceLocator;
	}

	/**
	 * @param string $className
	 * @param array $params
	 * @return object
	 */
	public function getInstance($className, array $params = array()) {
		if(!array_key_exists($className, $this->instances)) {
			$this->instances[$className] = $this->createInstance($className, $params);
		}
		return $this->instances[$className];
	}

	/**
	 * @param string $className
	 * @param array $params
	 * @return object
	 */
	public function createInstance($className, array $params = array()) {
		$refClass = new ReflectionClass($className);
		if($refClass->hasMethod('__construct')) {
			$params = $this->parameterResolver->buildParamsFromRefClass($refClass, '__construct', $params);
			return $refClass->newInstanceArgs($params);
		}
		return $refClass->newInstance();
	}
}