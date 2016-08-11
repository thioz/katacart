<?php
namespace Kata\Cart\Rule;

/* 
 * Simple conditional assert using closures, can be extended for very specific conditions
 */

class Assert{
	
	protected $callable;
	
	public function __construct($callable = false)
	{
		if($callable){
			$this->callable = $callable;
		}
	}
	
	public function assert($rule, $item, $context){
		return $this->call($rule, $item,$context);
	}
	
	protected function call($rule, $item, $context){
		
		if(!is_callable($this->callable)){
			return false;
		}
		
		try{
			$result = call_user_func_array($this->callable, [$rule, $item, $context]);
			return $result;
		} catch (Exception $ex) {

		}
		return false;
	}
}

