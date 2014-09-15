<?php
namespace Kir\Dispatching;

use Kir\Dispatching\InstanceFactories\CommonInstanceFactory;
use Kir\Dispatching\Mock\TestClass2;
use Kir\Dispatching\ServiceLocators\ClosureServiceLocator;
use Kir\Dispatching\Tools\MethodInvoker;
use Kir\Dispatching\Tools\ParameterResolver;
use PHPUnit_Framework_TestCase;

class DispatcherTest extends PHPUnit_Framework_TestCase {
	/**
	 */
	public function testCreateInstance() {
		$dispatcher = $this->createDispatcher();
		$instance = $dispatcher->createInstance('Kir\\Dispatching\\Mock\\TestClass3');
		$this->assertInstanceOf('Kir\\Dispatching\\Mock\\TestClass3', $instance);
	}

	/**
	 */
	public function testInvokeMethod() {
		$dispatcher = $this->createDispatcher();
		$instance = $dispatcher->createInstance('Kir\\Dispatching\\Mock\\TestClass3');
		$result = $dispatcher->invokeMethod($instance, 'test', array('a' => 2, 'b' => 3));
		$this->assertEquals(5, $result);
	}

	/**
	 */
	public function testInvoke() {
		$dispatcher = $this->createDispatcher();
		$result = $dispatcher->invoke('Kir\\Dispatching\\Mock\\TestClass3', 'test', array('a' => 2, 'b' => 3));
		$this->assertEquals(5, $result);
	}

	/**
	 * @return Dispatcher
	 */
	private function createDispatcher() {
		$resolver = new ParameterResolver();
		$instanceFactory = new CommonInstanceFactory($resolver);

		$locator = new ClosureServiceLocator(function ($className, InstanceFactory $instanceFactory, ServiceLocator $serviceLocator) {
			return $instanceFactory->getInstance($serviceLocator, $className);
		}, $instanceFactory);

		$locator->addResolver('Kir\\Dispatching\\Mock\\TestClass2', function (InstanceFactory $instanceFactory) {
			return new TestClass2(123);
		});


		$invoker = new MethodInvoker($resolver);
		return new Dispatcher($locator, $invoker);
	}
}