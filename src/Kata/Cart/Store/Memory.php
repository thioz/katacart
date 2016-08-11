<?php
namespace Kata\Cart\Store;

use Kata\Cart\Store;
/* 
 * Simple in memory store for KataCart
 * This can only ebe used for development purposes and would really suck in real world applications
 */

class Memory extends Store{
	
	public function addItem($item)
	{
		$this->items[] = $item;
	}

	public function items()
	{
		return $this->items;
	}

}
