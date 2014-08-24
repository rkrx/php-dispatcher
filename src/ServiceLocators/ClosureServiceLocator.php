<?php
namespace Kir\Dispatching\ServiceLocators;

use Closure;
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
	 * @param callable $instantiateClosure
	 * @param callable $isResponsibleClosure
	 * @internal param callable $closure
	 */
	function __construct(Closure $instantiateClosure, Closure $isResponsibleClosure = null) {
		$this->instantiateClosure = $instantiateClosure;
		if($isResponsibleClosure === null) {
			$isResponsibleClosure = function () {
				return true;
			};
		}
		$this->isResponsibleClosure = $isResponsibleClosure;
	}

	/**
	 * @param string $paramName
	 * @return bool
	 */
	public function has($paramName) {
		return call_user_func($this->isResponsibleClosure, $paramName);
	}

	/**
	 * @param string $service
	 * @param object $caller
	 * @return object
	 */
	public function resolve($service, $caller) {
		return call_user_func($this->instantiateClosure, $service, $caller);
	}
}