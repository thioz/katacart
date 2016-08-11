<?php
namespace Kata;

use Kata\Cart\Item;
use Kata\Cart\Store;

class Cart{
	
	/**
	 *
	 * @var Store
	 */
	protected $store;
	
	public function __construct(Store $store)
	{
		$this->store = $store;
	}
	
	/**
	 * Gets the items present in the cart from the store
	 */
	public function items(){
		return $this->store->items();
	}
	
	public function totalamount(){
		$total = 0.0;
		foreach($this->items() as $item){
			$total += $item->totalamount();
		}
		return $total;
	}
	
	/**
	 * Adds an item to the cart store
	 * @param Item $item
	 * @return \Kata\Cart
	 */
	public function addItem(Item $item){
		$this->store->addItem($item);
		return $this;
	}

	/**
	 * Convenience method to be able to access the store directly
	 * @return Store
	 */
	public function store(){
		return $this->store;
	}
	
	
}