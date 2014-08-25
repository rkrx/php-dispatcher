<?php
namespace Kir\Dispatching;

use ReflectionClass;
use ReflectionParameter;

class InstanceFactory {
	/**
	 * @var Tools\InstanceCache
	 */
	private $cache = null;

	/**
	 * @var ServiceLocator
	 */
	private $sl;

	/**
	 * @var array
	 */
	private $values = array();

	/**
	 * @param ServiceLocator $sl
	 */
	public function __construct(ServiceLocator $sl = null) {
		$this->cache = new Tools\InstanceCache();
		$this->sl = $sl;
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
	 * @return object
	 */
	public function getInstance($className) {
		$that = $this;
		return $this->cache->get($className, function () use ($className, $that) {
			return $that->createInstance($className);
		});
	}

	/**
	 * @param string $className
	 * @return object
	 */
	public function createInstance($className) {
		$refClass = new ReflectionClass($className);
		if($refClass->hasMethod('__construct')) {
			return $this->invokeConstructor($refClass);
		}
		return $refClass->newInstance();
	}

	/**
	 * @param ReflectionClass $refClass
	 * @return mixed
	 */
	private function invokeConstructor(ReflectionClass $refClass) {
		$constructor = $refClass->getMethod('__construct');
		$params = array();
		foreach($constructor->getParameters() as $parameter) {
			list($paramName, $className) = $this->getParamDetails($parameter);
			if($className !== null) {
				$params[] = $this->getInstance($className);
			} elseif(array_key_exists($paramName, $this->values)) {
				$registeredService = $this->values[$paramName];
				if(is_callable($registeredService)) {
					$params[] = call_user_func($registeredService);
				} else {
					$params[] = $registeredService;
				}
			} elseif($this->sl !== null && $this->sl->has($paramName)) {
				$params[] = $this->sl->resolve($paramName, $this);
			} else {
				$params[] = null;
			}
		}
		return $refClass->newInstanceArgs($params);
	}

	/**
	 * @param $parameter
	 * @return array
	 */
	private function getParamDetails(ReflectionParameter $parameter) {
		$paramName = $parameter->getName();
		$class = $parameter->getClass();
		$className = null;
		if($class !== null) {
			$className = $class->getName();
		}
		return array($paramName, $className);
	}
}