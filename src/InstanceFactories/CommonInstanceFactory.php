<?php
namespace Kir\Dispatching\InstanceFactories;

use Kir\Dispatching\InstanceFactory;
use Kir\Dispatching\Tools;
use ReflectionClass;

class CommonInstanceFactory implements InstanceFactory {
	/**
	 * @var Tools\CommonInstanceCache
	 */
	private $cache = null;

	/**
	 * @var array
	 */
	private $values = array();

	/**
	 * @var Tools\ParameterResolver
	 */
	private $parameterResolver;

	/**
	 * @param Tools\ParameterResolver $parameterResolver
	 */
	public function __construct(Tools\ParameterResolver $parameterResolver) {
		$this->cache = new Tools\CommonInstanceCache();
		$this->parameterResolver = $parameterResolver;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return $this
	 */
	public function register($name, $value) {
		$this->values[$name] = $value;
		return $this;
	}

	/**
	 * @param string $className
	 * @param array $params
	 * @return object
	 */
	public function getInstance($className, array $params=array()) {
		$that = $this;
		return $this->cache->get($className, function () use ($className, $params, $that) {
			return $that->createInstance($className, $params);
		});
	}

	/**
	 * @param string $className
	 * @param array $params
	 * @return object
	 */
	public function createInstance($className, array $params=array()) {
		$refClass = new ReflectionClass($className);
		if($refClass->hasMethod('__construct')) {
			$params = $this->parameterResolver->buildParamsFromRefClass($refClass, '__construct', $params);
			return $refClass->newInstanceArgs($params);
		}
		return $refClass->newInstance();
	}
}