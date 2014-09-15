<?php
namespace Kir\Dispatching\Tools;

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
	 * @param object $instance
	 * @param string $methodName
	 * @param array $params
	 * @return mixed
	 */
	public function invoke($instance, $methodName, array $params = array()) {
		$args = $this->parameterResolver->buildParamsFromObject($instance, $methodName, $params);
		$refObj = new \ReflectionObject($instance);
		$refMethod = $refObj->getMethod($methodName);
		return $refMethod->invokeArgs($instance, $args);
	}
}