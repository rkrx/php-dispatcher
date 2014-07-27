<?php
namespace Kir\Http\Routing;

use Kir\Http\Routing\ServiceLocators\ClosureServiceLocator;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

class InstanceFactoryTest extends PHPUnit_Framework_TestCase {
	public function testValueInjection() {
		$injector = new InstanceFactory();
		$injector->register('test', 123);
		$instance = $injector->createInstance('Kir\\Http\\Routing\\Mock\\TestClass2');
		$this->assertInstanceOf('Kir\\Http\\Routing\\Mock\\TestClass2', $instance);
		$this->assertEquals(123, $instance->test);
	}

	public function testServiceInjection() {
		$simpleServiceLocator = new ClosureServiceLocator(function ($serviceName, InstanceFactory $injector) {
			switch ($serviceName) {
				case 'test':
					return $injector->createInstance("Kir\\Http\\Routing\\Mock\\TestClass");
			}
			return null;
		});
		$injector = new InstanceFactory($simpleServiceLocator);
		$instance = $injector->createInstance('Kir\\Http\\Routing\\Mock\\TestClass2');
		$this->assertInstanceOf('Kir\\Http\\Routing\\Mock\\TestClass2', $instance);
		$this->assertInstanceOf('Kir\\Http\\Routing\\Mock\\TestClass', $instance->test);
	}
}