<?php
namespace Kir\Dispatching\ServiceLocators;

use Kir\Dispatching\InstanceFactories\CommonInstanceFactory;
use Kir\Dispatching\InstanceFactory;
use Kir\Dispatching\ServiceLocator;
use Kir\Dispatching\Tools\ParameterResolver;

class ClosureServiceLocator implements ServiceLocator {
	/**
	 * @var callable
	 */
	private $instantiateClosure;

	/**
	 * @var InstanceFactory
	 */
	private $instanceFactory = null;

	/**
	 * @var string[]
	 */
	private $interfaces = array();

	/**
	 * @param callable $instantiateClosure
	 * @param InstanceFactory $instanceFactory
	 */
	function __construct(\Closure $instantiateClosure = null, InstanceFactory $instanceFactory = null) {
		if($instantiateClosure === null) {
			$instantiateClosure = function () {
				return null;
			};
		}
		$this->instantiateClosure = $instantiateClosure;
		if($instanceFactory === null) {
			$parameterResolver = new ParameterResolver($this);
			$instanceFactory = new CommonInstanceFactory($this, $parameterResolver);
		}
		$this->instanceFactory = $instanceFactory;
	}

	/**
	 * @param array $resolvers
	 * @return $this
	 */
	public function addResolvers(array $resolvers) {
		foreach($resolvers as $interfaceName => $resolver) {
			$this->addResolver($interfaceName, $resolver);
		}
		return $this;
	}

	/**
	 * @param string $interfaceName
	 * @param \Closure $resolver
	 * @return $this
	 */
	public function addResolver($interfaceName, \Closure $resolver) {
		$this->interfaces[$interfaceName] = $resolver;
		return $this;
	}

	/**
	 * @param string $interfaceName
	 * @return bool
	 */
	public function has($interfaceName) {
		if(array_key_exists($interfaceName, $this->interfaces)) {
			return true;
		}
		return false;
	}

	/**
	 * @param string $interfaceName
	 * @param object $caller
	 * @return object
	 */
	public function resolve($interfaceName, $caller = null) {
		if(array_key_exists($interfaceName, $this->interfaces)) {
			return call_user_func($this->interfaces[$interfaceName], $this->instanceFactory, $this, $caller);
		}
		return call_user_func($this->instantiateClosure, $interfaceName, $this->instanceFactory, $this, $caller);
	}
}