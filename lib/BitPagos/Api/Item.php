<?php

namespace BitPagos\Api;

use BitPagos\Common\BitPagosModel;
use BitPagos\Converter\FormatConverter;
use BitPagos\Validation\NumericValidator;
use BitPagos\Validation\UrlValidator;

/**
 * Class Item
 *
 * An item being paid for.
 *
 * @package BitPagos\Api
 *         
 * @property string quantity
 * @property string title
 * @property string price
 */
class Item extends BitPagosModel
{

	/**
	 * Number of items.
	 *
	 * @param string $quantity        	
	 *
	 * @return $this
	 */
	public function setQuantity($quantity)
	{
		$this->quantity = $quantity;
		return $this;
	}

	/**
	 * Number of items.
	 *
	 * @return string
	 */
	public function getQuantity()
	{
		return $this->quantity;
	}

	/**
	 * Name of the item.
	 *
	 * @param string $name        	
	 *
	 * @return $this
	 */
	public function setTitle($name)
	{
		$this->title = $name;
		return $this;
	}

	/**
	 * Name of the item.
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Cost of the item.
	 *
	 * @param string|double $price        	
	 *
	 * @return $this
	 */
	public function setPrice($price)
	{
		NumericValidator::validate( $price, "Price" );
		$price = FormatConverter::formatToPrice( $price, $this->getCurrency() );
		$this->price = $price;
		return $this;
	}

	/**
	 * Cost of the item.
	 *
	 * @return string
	 */
	public function getPrice()
	{
		return $this->price;
	}

	/**
	 * 3-letter Currency Code
	 *
	 * @param string $currency        	
	 *
	 * @return $this
	 */
	public function setCurrency($currency)
	{
		$this->currency = $currency;
		return $this;
	}

	/**
	 * 3-letter Currency Code
	 *
	 * @return string
	 */
	public function getCurrency()
	{
		return $this->currency;
	}
}
