<?php
namespace Kir\Dispatching\Tools;

use Kir\Dispatching\ServiceLocator;

class MethodInvoker {
	/**
	 * @var ParameterResolver
	 */
	private $parameterResolver;

	/**
	 * @param ParameterResolver $parameterResolver
	 */
	public function __construct(ParameterResolver $parameterResolver) {
		$this->parameterResolver = $parameterResolver;
	}

	/**
	 * @param ServiceLocator $serviceLocator
	 * @param object $instance
	 * @param string $methodName
	 * @param array $params
	 * @return mixed
	 */
	public function invoke(ServiceLocator $serviceLocator, $instance, $methodName, array $params = array()) {
		$args = $this->parameterResolver->buildParamsFromObject($serviceLocator, $instance, $methodName, $params);
		$refObj = new \ReflectionObject($instance);
		$refMethod = $refObj->getMethod($methodName);
		return $refMethod->invokeArgs($instance, $args);
	}
}