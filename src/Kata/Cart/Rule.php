<?php
namespace Kata\Cart;

/* 
 * Abstract Rule class 
 */

abstract class Rule
{
	
	/**
	 * The product ID on which to apply the rule,
	 * this could be solved using assert statements but since in this setup a rule always applies to a product 
	 * this was the most logical solution.
	 * @var int
	 */
	protected $productId;
	
	/**
	 *
	 * @var array or asserts 
	 */
	protected $asserts = [];
	
	/**
	 * closures/callables that are fired when the asserts evaluate to true
	 * @var array
	 */
	protected $onSuccess = [];

	/**
	 * closures/callables that are fired when the asserts evaluate to false
	 * @var array
	 */
	protected $onFail = [];
	
	public function __construct()
	{
		
	}
	
	public function assert($closureOrAssert){
		if($closureOrAssert instanceof \Closure){
			$closureOrAssert = new Rule\Assert($closureOrAssert);
		}
		
		if(!$closureOrAssert instanceof Rule\Assert){
			throw new Exception('Assert should be a closure or an Assert instance');
		}
		
		$this->asserts[] = $closureOrAssert;
		return $this;
	}
	
	public function success($callable){
		if(!is_callable($callable)){
			throw new Exception('Success should be callable');
		}
		
		$this->onSuccess[] = $callable;
	}

	public function fail($callable){
		if(!is_callable($callable)){
			throw new Exception('Fail should be callable');
		}
		$this->onFail[] = $callable;
	}
	
	public function apply(\Kata\Cart $cart){
		
	}
}
