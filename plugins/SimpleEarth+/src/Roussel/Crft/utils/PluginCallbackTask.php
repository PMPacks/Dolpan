<?php

namespace Roussel\Crft\utils;

use pocketmine\scheduler\PluginTask;
use pocketmine\plugin\Plugin;

/**
 * Allows the creation of simple callbacks with extra data
 * The last parameter in the callback will be this object
 *
 */
class PluginCallbackTask extends PluginTask{

	/** @var callable */
	protected $callable;

	/** @var array */
	protected $args;

	/**
	 * @param Plugin   $owner
	 * @param callable $callable
	 * @param array    $args
	 */
	public function __construct(Plugin $owner, callable $callable, array $args = []){
		parent::__construct($owner);
		$this->callable = $callable;
		$this->args = $args;
		$this->args[] = $this;
	}

	/**
	 * @return callable
	 */
	public function getCallable(){
		return $this->callable;
	}

	public function onRun($currentTicks){
		$c = $this->callable;
		$args = $this->args;
		$args[] = $currentTicks;
		$c(...$args);
	}

}
