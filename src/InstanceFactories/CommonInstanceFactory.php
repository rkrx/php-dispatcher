<?php
namespace Kir\Dispatching\InstanceFactories;

use Kir\Dispatching\InstanceFactory;
use Kir\Dispatching\ServiceLocator;
use Kir\Dispatching\Tools;
use Kir\Dispatching\Tools\CommonInstanceCache;
use Kir\Dispatching\Tools\ParameterResolver;
use ReflectionClass;

class CommonInstanceFactory implements InstanceFactory {
	/**
	 * @var Tools\CommonInstanceCache
	 */
	private $cache = null;

	/**
	 * @var ParameterResolver
	 */
	private $parameterResolver;

	/**
	 * @param ParameterResolver $parameterResolver
	 */
	public function __construct(ParameterResolver $parameterResolver) {
		$this->cache = new CommonInstanceCache();
		$this->parameterResolver = $parameterResolver;
	}

	/**
	 * @param ServiceLocator $serviceLocator
	 * @param string $className
	 * @param array $params
	 * @return object
	 */
	public function getInstance(ServiceLocator $serviceLocator, $className, array $params = array()) {
		$that = $this;
		return $this->cache->get($className, function () use ($className, $params, $serviceLocator, $that) {
			return $that->createInstance($serviceLocator, $className, $params);
		});
	}

	/**
	 * @param ServiceLocator $serviceLocator
	 * @param string $className
	 * @param array $params
	 * @return object
	 */
	public function createInstance(ServiceLocator $serviceLocator, $className, array $params = array()) {
		$refClass = new ReflectionClass($className);
		if($refClass->hasMethod('__construct')) {
			$params = $this->parameterResolver->buildParamsFromRefClass($serviceLocator, $refClass, '__construct', $params);
			return $refClass->newInstanceArgs($params);
		}
		return $refClass->newInstance();
	}
}