<?php
namespace Kir\Dispatching;

use Exception;
use ReflectionMethod;
use ReflectionObject;

class Dispatcher {
	/**
	 * @var callable
	 */
	private $classFactory;

	/**
	 * @return callable
	 */
	public function getClassFactory() {
		return $this->classFactory;
	}

	/**
	 * @param callable $callable
	 * @return $this
	 */
	public function setClassFactory($callable) {
		$this->classFactory = $callable;
		return $this;
	}

	/**
	 * @param string $className
	 * @param string $method
	 * @param array $params
	 * @throws Exceptions\BadInstanceFoundException
	 * @throws Exceptions\MethodNotFoundException
	 * @return mixed
	 */
	public function invoke($className, $method, array $params) {
		$instance = call_user_func($this->classFactory, $className, $params);
		if(!is_object($instance)) {
			throw new Exceptions\BadInstanceFoundException();
		}
		return $this->invokeMethod($method, $instance, $params);
	}

	/**
	 * @param string $method
	 * @param object $instance
	 * @param array $params
	 * @throws Exception
	 * @return mixed
	 */
	private function invokeMethod($method, $instance, $params) {
		$refObject = new ReflectionObject($instance);
		if(!$refObject->hasMethod($method)) {
			throw new Exceptions\MethodNotFoundException("Missing method {$method}");
		}
		$refMethod = $refObject->getMethod($method);
		$parameters = $this->buildParams($refMethod, $params);
		return $refMethod->invokeArgs($instance, $parameters);
	}

	/**
	 * @param ReflectionMethod $refMethod
	 * @param array $params
	 * @return array
	 */
	private function buildParams($refMethod, $params) {
		$parameters = array();
		foreach($refMethod->getParameters() as $parameter) {
			if(array_key_exists($parameter->getName(), $params)) {
				$value = $params[$parameter->getName()];
			} else {
				$value = $parameter->getDefaultValue();
			}
			$parameters[] = $value;
		}
		return $parameters;
	}
}