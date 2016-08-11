<?php
namespace Kata\Cart\Rule;

/**
 * a ruleset makes it possible to group rules which will be processed as a single rule
 */
class RuleSet extends \Kata\Cart\Rule
{
	/**
	 * Holds the rules in this set
	 * @var array
	 */
	protected $rules = [];
	
	/**
	 *  Add a rule to the list 
	 * @param \Kata\Cart\Rule $rule
	 */
	function add(Rule $rule){
		$this->rules[] = $rule;
	}
	
	
		
}
