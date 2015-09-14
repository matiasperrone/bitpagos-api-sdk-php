<?php

namespace BitPagos\Api;

use BitPagos\Common\BitPagosModel;

/**
 * Class ItemList
 *
 * List of items being paid for.
 *
 * @package BitPagos\Api
 *         
 * @property \BitPagos\Api\Item[] items
 * @property string shipping_method
 */
class ItemList extends BitPagosModel
{

	/**
	 * Is this list empty?
	 */
	public function isEmpty()
	{
		return empty( $this->items );
	}

	/**
	 * List of items.
	 *
	 * @param \BitPagos\Api\Item[] $items        	
	 *
	 * @return $this
	 */
	public function setItems($items)
	{
		$this->items = $items;
		return $this;
	}

	/**
	 * List of items.
	 *
	 * @return \BitPagos\Api\Item[]
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * Append Items to the list.
	 *
	 * @param \BitPagos\Api\Item $item        	
	 * @return $this
	 */
	public function addItem($item)
	{
		if (! $this->getItems())
		{
			return $this->setItems( array($item) );
		}
		else
		{
			return $this->setItems( array_merge( $this->getItems(), array($item) ) );
		}
	}

	/**
	 * Remove Items from the list.
	 *
	 * @param \BitPagos\Api\Item $item        	
	 * @return $this
	 */
	public function removeItem($item)
	{
		return $this->setItems( array_diff( $this->getItems(), array($item) ) );
	}
}
