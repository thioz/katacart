<?php

namespace Kata\Cart\Rule;

use Kata\Cart;
use Kata\Cart\Rule;

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
	 * @var Cart
	 */
	protected $cart;
	
	/**
	 * instance of Context
	 * @var Context
	 */
	protected $context;

	/**
	 * array that holds the rules ... hence the name ;) 
	 * @var array
	 */
	protected $rules = [];

	public function __construct(Cart $cart)
	{
		$this->cart = $cart;
	}

	/**
	 *  Add a rule to the list 
	 * @param Rule $rule
	 */
	function add(Rule $rule)
	{
		$this->rules[] = $rule;
	}

	public function cart()
	{
		return $this->cart;
	}

	/**
	 * Process the rules
	 * Since rules have the ability to cancel the processing chain it's only logical that 
	 * we iterate through the rules and then throught al the items 
	 */
	public function process()
	{

		$context = $this->getContext();
		foreach ($this->rules as $rule)
		{
			// check if the context has been cancelled
			if ($context->cancelled())
			{
				return $context;
			}

			$this->processRule($rule);
		}
		return $context;
	}

	protected function processRule($rule)
	{
		$context = $this->getContext();
		foreach ($this->cart->items() as $item)
		{
			// check if the context has been cancelled
			if ($context->cancelled())
			{
				break;
			}

			if (!$rule->enabled())
			{
				break;
			}

			// check if the rule applies to this item
			if ($rule->applies($item))
			{
				$rule->apply($item, $context);
			}
		}
	}

	public function getContext()
	{
		if (!$this->context)
		{
			$this->context = new Context($this);
		}
		return $this->context;
	}

}
