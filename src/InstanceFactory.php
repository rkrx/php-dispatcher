<?php
namespace Kir\Http\Routing;

use ReflectionClass;

class InstanceFactory {
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
			$paramName = $parameter->getName();
			if(array_key_exists($paramName, $this->values)) {
				$params[] = $this->values[$paramName];
			} elseif($this->sl !== null) {
				$params[] = $this->sl->resolve($paramName, $this);
			} else {
				$params[] = null;
			}
		}
		return $refClass->newInstanceArgs($params);
	}
}