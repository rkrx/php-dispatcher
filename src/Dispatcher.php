<?php
namespace Kir\Dispatching;

use Kir\Dispatching\Tools\MethodInvoker;

class Dispatcher {
	/** @var MethodInvoker */
	private $invoker = null;
	/** @var ServiceLocator */
	private $serviceLocator;

	/**
	 * @param ServiceLocator $serviceLocator
	 * @param MethodInvoker $invoker
	 */
	public function __construct(ServiceLocator $serviceLocator, MethodInvoker $invoker) {
		$this->invoker = $invoker;
		$this->serviceLocator = $serviceLocator;
	}

	/**
	 * @param string $className
	 * @return object
	 */
	public function createInstance($className) {
		return $this->serviceLocator->resolve($className, null);
	}

	/**
	 * @param object $instance
	 * @param string $method
	 * @param array $params
	 * @throws \Exception
	 * @return mixed
	 */
	public function invokeMethod($instance, $method, array $params = array()) {
		return $this->invoker->invoke($instance, $method, $params);
	}

	/**
	 * @param string $className
	 * @param string $method
	 * @param array $params
	 * @throws Exceptions\BadInstanceFoundException
	 * @throws Exceptions\MethodNotFoundException
	 * @return mixed
	 */
	public function invoke($className, $method, array $params = array()) {
		$instance = $this->createInstance($className);
		return $this->invokeMethod($instance, $method, $params);
	}
}
