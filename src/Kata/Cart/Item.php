<?php

namespace Kata\Cart;

/*
 * class that represents one single item in the cart
 * for simplicity this item does not contain a Product entity, but only a reference id for price rules
 * idealy this item would be build/constructed by some sort of factory but for now its manual all the way 
 */

class Item
{
	/**
	 * reference to the product
	 * @var int 
	 */
	protected $productId;
	
	/**
	 * a simple description of the product,
	 * normally this would come from the Product entity or would be resolved through some sort of product manager 
	 * @var string
	 */
	protected $productDescription = '';
	
	/**
	 * Unit price , ex VAT
	 * @var double
	 */
	protected $unitPrice= 0.0;
	
	/**
	 * Value describing a unit, which makes it easier to define price per X , defaults to 1 
	 * @var decimal
	 */
	protected $unit = 1;

	/**
	 * Description of the type of unit, this is purely cosmetic
	 * @var decimal
	 */
	protected $unitDescription = 'pcs';
	
	/**
	 * Number of products 
	 * @var double/int
	 */
	protected $quantity = 1;
	
	/**
	 * Cumalative amount of this cart item
	 * @var double
	 */
	protected $totalAmount = 0.0;
	
	/**
	 * empty constructor
	 */
	public function __construct()
	{
		
	}
	
	/**
	 * Convenience method to set the productId and the description in one call
	 * @param type $id
	 * @param type $description
	 * @return \Kata\Cart\Item
	 */
	function setProduct($id, $description){
		$this->setProductId($id);
		$this->setProductDescription($description);
		return $this;
	}
	
	/**
	 * Set the prodyuct Id
	 * @param type $id
	 * @return \Kata\Cart\Item
	 */
	function setProductId($id){
		$this->productId = $id;
		return $this;
	}
	
	/**
	 * Set product description
	 * @param type $description
	 * @return \Kata\Cart\Item
	 */
	function setProductDescription($description){
		$this->productDescription = $description;
		return $this;
	}
	
	/**
	 * Set the unit
	 * @param type $unit
	 * @return \Kata\Cart\Item
	 */
	public function setUnit($unit){
		$this->unit = $unit;
		return $this;
	}
	
	public function setUnitDescription($description){
		$this->unitDescription = $description;
		return $this;
	}
	
	/**
	 * set number of products
	 * @param type $num
	 * @return \Kata\Cart\Item
	 */
	public function setQuantity($num){
		$this->quantity = $num;
		return $this;
	}
	
	/**
	 * setter for unit price
	 * @param type $price
	 * @return \Kata\Cart\Item
	 */
	public function setUnitPrice($price){
		$this->unitPrice = $price;
		return $this;
	}
	
	/**
	 * Getter for product description
	 * @return string
	 */
	public function description(){
		return $this->productDescription;
	}
	
	/**
	 * getter for unitprice
	 * @return double
	 */
	public function price(){
		return $this->unitPrice;
	}
	
	/**
	 * getter for quantity
	 * @return double
	 */
	public function quantity(){
		return $this->quantity;
	}
	
	/**
	 * getter for productId
	 * @return int
	 */
	public function productId(){
		return $this->productId;
	}
	
	/**
	 * Wrapper function to recalculate the total item amount and return it;
	 * @return type
	 */
	public function totalamount(){
		$this->calculateTotalAmount();
		return $this->totalAmount;
	}
	
	protected function calculateTotalAmount(){
		
		$actualNumber = $this->quantity / $this->unit;
		$this->totalAmount = $actualNumber * $this->unitPrice;
	}
	
	
	
}
