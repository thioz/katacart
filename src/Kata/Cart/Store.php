<?php
namespace Kata\Cart;
/* 
 * Abstract store class to store the cart items
 */

abstract class Store {
	
	protected $items = [];

	/**
	 * method to access the cart items
	 */
	public abstract function items();
	
	/**
	 * method to add a cart item
	 */
	
	public abstract function addItem(Item $item);
	
}
