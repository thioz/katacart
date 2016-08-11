<?php

namespace Kata\Cart\Rule;

/**
 * The Context  acts like a sort of event and state for the rules processing. 
 * it is created once and send throught the chain of rules and rulesets which can invoke a cancel command to stop the processing chain.
 * 
 */

class Context
{
	protected $cancelled = false;
	
	/**
	 * stores data that can be used between different rules / rulesets.
	 * @var array 
	 */
	protected $data = [];
	
	/**
	 * stores log entries 
	 * @var array
	 */
	protected $log = [];
	
	/**
	 *
	 * @var Container
	 */
	protected $container;
	
	public function __construct(Container $container)
	{
		$this->container = $container;
	}
	
	/**
	 * getter for the container so the rules can access the cart as well 
	 * @return Container
	 */
	public function container(){
		return $this->container;
	}
	
	/**
	 * Stop the processing chain
	 * @return \Kata\Cart\Rule\Context
	 */
	public function cancel(){
		$this->cancelled = true;
		return $this;
	}
	
	/**
	 * Check if the processing has been cancelled by a rule/ruleset
	 * @return boolean
	 */
	public function cancelled(){
		return $this->cancelled;
	}
	
	/**
	 * Set a data variable
	 * @param string $name
	 * @param mixed $value
	 */
	public function set($name, $value){
		$this->data[$name] = $value;
		return $this;
	}
	
	/**
	 * get a entry from data data store and return it or a default value when not set
	 * 
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($name, $default = null){
		return isset($this->data[$name]) ? $this->data[$name] : $default;
	}
	
	/**
	 * Check if a variable has been set
	 * @param string $name
	 * @return boolean
	 */
	public function has($name){
		return isset($this->data[$name]);
	}
}