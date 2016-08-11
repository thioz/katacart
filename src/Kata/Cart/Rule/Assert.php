<?php
namespace Kata\Cart\Rule;

/* 
 * Simple conditional assert using closures, can be extended for very specific conditions
 */

class Assert{
	
	protected $callable;
	
	public function __construct($callable)
	{
		$this->callable = $callable;
	}
	
	public function assert($item, $context){
		return $this->call($item,$context);
	}
	
	protected function call($item, $context){
		try{
			$result = call_user_func_array($this->callable, [$item, $context]);
			return $result;
		} catch (Exception $ex) {

		}
		return false;
	}
}

