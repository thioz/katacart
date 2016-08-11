<?php
namespace Kata\Cart;

use Closure;
use Exception;
use Kata\Cart\Rule\Assert;
use Kata\Cart\Rule\Context;

/* 
 * Rule class 
 */

class Rule
{
	
	/**
	 * The product ID on which to apply the rule,
	 * this could be solved using assert statements but since in this setup a rule always applies to a product 
	 * this was the most logical solution.
	 * @var int
	 */
	protected $productId;
	
	/**
	 * shows if this rule is still enabled, can be set to make sure a rule doesn't run multiple times
	 * @var boolean
	 */
	protected $enabled = true;
	
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
	
	public function __construct($productId = false)
	{
		$this->productId = $productId;
	}
	
	/**
	 * Evaluates if this rule applies to the Item instance. 
	 * default it checks the productId but could  be extended in the future with more logic
	 * @param Item $item
	 */
	public function applies(Item $item){
		return $item->productId() == $this->productId;
	}
	
	/**
	 * Setter for productId
	 * @param int $id
	 * @return \Kata\Cart\Rule
	 */
	public function setProductId($id){
		$this->productId = $id;
		return $this;
	}
	
	public function productId(){
		return $this->productId;
	}
	
	public function enabled(){
		return $this->enabled;
	}
	
	public function disable(){
		$this->enabled = false;
	}
	
	/**
	 * 
	 * @param Closure/Assert $closureOrAssert
	 * @return \Kata\Cart\Rule
	 * @throws Exception
	 */
	public function assert($closureOrAssert){
		if($closureOrAssert instanceof Closure || is_callable($closureOrAssert)){
			$closureOrAssert = new Assert($closureOrAssert);
		}
		
		if(!$closureOrAssert instanceof Assert){
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
		return $this;
	}

	public function fail($callable){
		if(!is_callable($callable)){
			throw new Exception('Fail should be callable');
		}
		$this->onFail[] = $callable;
		return $this;
	}
	
	/**
	 * 
	 * @param \Kata\Cart\Item $item
	 * @param Context $context
	 */
	public function apply(Item $item, Context $context){
		foreach($this->asserts as $assert){
			if(!$assert->assert($this, $item,$context)){
				// if one assert failes the fail handlers will be fired
				$this->fireFail($item, $context);
				return;
			}
		}
		$this->fireSuccess($item, $context);
	}
	
	protected function fireSuccess($item, $context){
		$this->fireHandlers($this->onSuccess, $item, $context);
	}
	protected function fireFail($item, $context){
		$this->fireHandlers($this->onFail, $item, $context);
	}
	
	protected function fireHandlers($handlers, $item, $context){
		foreach($handlers as $handler){
			if(is_callable($handler)){
				$result = call_user_func_array($handler, [$this, $item, $context]);
				
				/**
				 * if a success/fail handler returns false the handler chain will stop
				 */				
				if($result === false){
					return;
				}
			}
		}
	}
	
	/**
	 * factory method to be able to chain the rule setup
	 * @param type $productId
	 * @return Rule
	 */
	public static function create($productId = false){
		return new static($productId);
	}
	
}
