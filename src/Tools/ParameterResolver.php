<?php
namespace Kir\Dispatching\Tools;

use Kir\Dispatching\ServiceLocator;

class ParameterResolver {
	/**
	 * @var ServiceLocator
	 */
	private $serviceLocator;

	/**
	 * @param ServiceLocator $serviceLocator
	 */
	public function __construct(ServiceLocator $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}

	/**
	 * @param $instance
	 * @param $methodName
	 * @param array $params
	 * @return mixed[]
	 */
	public function buildParamsFromObject($instance, $methodName, array $params = array()) {
		$refObject = new \ReflectionObject($instance);
		if(!$refObject->hasMethod($methodName)) {
			throw new \BadMethodCallException(sprintf("Method {$methodName} not found in %s", get_class($instance)));
		}
		return $this->buildParamsFromRefClass($refObject, $methodName, $params);
	}

	/**
	 * @param \ReflectionClass $refClass
	 * @param string $methodName
	 * @param array $params
	 * @return mixed[]
	 */
	public function buildParamsFromRefClass(\ReflectionClass $refClass, $methodName, array $params = array()) {
		$method = $refClass->getMethod($methodName);
		$parameters = array();
		foreach($method->getParameters() as $parameter) {
			list($paramName, $className) = $this->getParamDetails($parameter);
			if(array_key_exists($paramName, $params)) {
				$parameters[] = $params[$paramName];
			} elseif($className !== null) {
				$parameters[] = $this->serviceLocator->resolve($className, null);
			} else {
				$parameters[] = null;
			}
		}
		return $parameters;
	}

	/**
	 * @param \ReflectionParameter $parameter
	 * @return array
	 */
	private function getParamDetails(\ReflectionParameter $parameter) {
		$paramName = $parameter->getName();
		$class = $parameter->getClass();
		$className = null;
		if($class !== null) {
			$className = $class->getName();
		}
		return array($paramName, $className);
	}
}