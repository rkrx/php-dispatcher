<?php
namespace Kir\Dispatching\ServiceLocators;

use Kir\Dispatching\InstanceFactory;
use Kir\Dispatching\ServiceLocator;

class ClosureServiceLocator implements ServiceLocator {
	/**
	 * @var callable
	 */
	private $instantiateClosure;

	/**
	 * @var callable
	 */
	private $isResponsibleClosure;

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
	 * @param callable $isResponsibleClosure
	 * @internal param callable $closure
	 */
	function __construct(\Closure $instantiateClosure = null, \Closure $isResponsibleClosure = null) {
		if($instantiateClosure === null) {
			$instantiateClosure = function () {
				return null;
			};
		}
		$this->instantiateClosure = $instantiateClosure;
		if($isResponsibleClosure === null) {
			$isResponsibleClosure = function () {
				return true;
			};
		}
		$this->isResponsibleClosure = $isResponsibleClosure;
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
	 * @param InstanceFactory $instanceFactory
	 * @return $this
	 */
	public function setInstanceFactory(InstanceFactory $instanceFactory) {
		$this->instanceFactory = $instanceFactory;
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
		return call_user_func($this->isResponsibleClosure, $interfaceName);
	}

	/**
	 * @param string $interfaceName
	 * @param object $caller
	 * @return object
	 */
	public function resolve($interfaceName, $caller = null) {
		if(array_key_exists($interfaceName, $this->interfaces)) {
			return call_user_func($this->interfaces[$interfaceName], $this, $caller);
		}
		return call_user_func($this->instantiateClosure, $interfaceName, $this, $caller);
	}
}