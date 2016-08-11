<?php
namespace Kata\Cart\Rule;

/**
 * A simple container for the rules 
 * Because of separation of concern this should be a separate class that holds the cart and the rules
 * 
 * Also in this setup a discount or upsel is nothing more that an Item with a negative amount. 
 * This is done because changing the actual item's price or quantity could create race conditions when applying other rules to the same product  
 * The new item for discount could  be extended to a different kind of Item ... 
 * for example a DiscountItem, which could possilbly make it easier to separate actual order lines
 * from discount ... but then again ... makes it more complex and we're trying to KISS it 
 * 
 * 
 */

class Container
{
	/**
	 * the instance of the cart
	 * @var \Kata\Cart
	 */
	protected $cart;
	
	/**
	 * array that holds the rules ... hence the name ;) 
	 * @var array
	 */
	protected $rules = [];
	public function __construct(\Kata\Cart $cart)
	{
		$this->cart = $cart;
	}
	
	/**
	 *  Add a rule to the list 
	 * @param \Kata\Cart\Rule $rule
	 */
	function add(Rule $rule){
		$this->rules[] = $rule;
	}
	
	public function cart(){
		return $this->cart;
	}
	
	/**
	 * Process the rules
	 * Since rules have the ability to cancel the processing chain it's only logical that 
	 * we iterate through the rules and then throught al the items 
	 */
	public function process(){
		
		$context = new Context($this);
		foreach($this->rules as $rule){
			// check if the context has been cancelled
			if($context->cancelled()){
				return $context;
			}
			
			foreach($this->cart->items() as $item){
				// check if the context has been cancelled
				if($context->cancelled()){
					break;
				}
				
				// check if the rule applies to this item
				if($rule->applies($item)){
					$rule->apply($item, $context);
				}
			}
		}
		return $context;
	}
	
	/**
	 * internal function to be able to select the rules that apply to a one cart item
	 * @param int $productId
	 */
	protected function getRulesByProductId($productId){
		$rules = [];
		
	}
	
}