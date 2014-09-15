<?php
namespace Kir\Dispatching;

use Kir\Dispatching\Tools\MethodInvoker;

class Dispatcher {
	/**
	 * @var MethodInvoker
	 */
	private $invoker = null;

	/**
	 * @var InstanceFactory
	 */
	private $instanceFactory;

	/**
	 * @param InstanceFactory $instanceFactory
	 * @param MethodInvoker $invoker
	 */
	public function __construct(InstanceFactory $instanceFactory, MethodInvoker $invoker) {
		$this->invoker = $invoker;
		$this->instanceFactory = $instanceFactory;
	}

	/**
	 * @param string $className
	 * @return object
	 */
	public function createInstance($className) {
		return $this->instanceFactory->createInstance($className);
	}

	/**
	 * @param object $instance
	 * @param string $method
	 * @param array $params
	 * @throws \Exception
	 * @return mixed
	 */
	public function invokeMethod($instance, $method, $params) {
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
	public function invoke($className, $method, array $params) {
		$instance = $this->createInstance($className);
		return $this->invokeMethod($instance, $method, $params);
	}
}