<?php

namespace BitPagos\Api;

use BitPagos\Common\BitPagosResourceModel;
use BitPagos\Core\BitPagosConstants;

/**
 * Class Checkout
 *
 * Lets you create, process and manage checkout payments.
 *
 * @package BitPagos\Api
 *
 * @property string client_id
 * @property string secret
 * @property string key
 */
class Checkout extends BitPagosResourceModel
{
	public $client_id = null;
	public $secret = null;
	public $key = null;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->ipn_format = 'json';
		$this->ipn = '';
	}

	/**
	 * Establece la lista de items
	 *
	 * @param \BitPagos\Api\ItemList $itemList
	 * @return $this
	 */
	public function &addItem(\BitPagos\Api\Item $item)
	{
		if (! isset( $this->items ))
			$this->items = [$item];
		else
			$this->items[] = $item;
		return $this;
	}

	/**
	 * Establece el destino de la llamda del IPN
	 *
	 * @param string $ipn
	 * @return $this
	 */
	public function &setIpn($url)
	{
		$this->ipn = $url;
		return $this;
	}

	/**
	 * Devuelve el destino de la llamada del IPN
	 *
	 * @return string
	 */
	public function getIpn()
	{
		return $this->ipn;
	}

	/**
	 * Amount being collected.
	 *
	 * @param float $amount
	 *
	 * @return $this
	 */
	public function &setAmount($amount)
	{
		$this->amount = $amount;
		return $this;
	}

	/**
	 * Amount being collected.
	 *
	 * @return float
	 */
	public function getAmount()
	{
		return $this->amount;
	}

	/**
	 * Currency of the amount being collected.
	 *
	 * @param string $currency
	 *
	 * @return $this
	 */
	public function &setCurrency($currency)
	{
		$this->currency = $currency;
		return $this;
	}

	/**
	 * Currency of the amount being collected.
	 *
	 * @return string
	 */
	public function getCurrency()
	{
		return $this->currency;
	}

	/**
	 * Sets the success url redirect
	 *
	 * @param string $url
	 *
	 * @return $this
	 */
	public function &setReturnSuccess($url)
	{
		$this->return_success = $url;
		return $this;
	}

	/**
	 * Gets the success url redirect
	 *
	 * @return string
	 */
	public function getReturnSuccess()
	{
		return $this->return_success;
	}

	/**
	 * Sets the cancel url redirect
	 *
	 * @param string $url
	 *
	 * @return $this
	 */
	public function &setReturnCancel($url)
	{
		$this->return_cancel = $url;
		return $this;
	}

	/**
	 * Gets the cancel url redirect
	 *
	 * @return string
	 */
	public function getReturnCancel()
	{
		return $this->return_cancel;
	}

	/**
	 * Sets the pending url redirect
	 *
	 * @param string $url
	 *
	 * @return $this
	 */
	public function &setReturnPending($url)
	{
		$this->return_pending = $url;
		return $this;
	}

	/**
	 * Gets the pending url redirect
	 *
	 * @return string
	 */
	public function getReturnPending()
	{
		return $this->return_pending;
	}

	/**
	 * Set the caller reference_id
	 *
	 * @param string $reference_id
	 *
	 * @return $this
	 */
	public function &setReferenceID($reference_id)
	{
		$this->reference_id = $reference_id;
		return $this;
	}

	/**
	 * Get the caller reference_id
	 *
	 * @return string
	 */
	public function getReferenceID()
	{
		return $this->reference_id;
	}

	/**
	 * Send a request.
	 *
	 * @param \BitPagos\Rest\ApiContext $apiContext
	 *        	is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
	 * @param \BitPagos\Rest\RestCall $restCall
	 *        	is the Rest Call Service that is used to make rest calls
	 * @return \BitPagos\Rest\Checkout
	 */
	public function &create(\BitPagos\Rest\ApiContext $apiContext, BitPagos\Transport\RestCall &$restCall = null)
	{
		$payLoad = $this->toJSON();
		$json = self::executeCall( "/api/v1/checkout/", "POST", $payLoad, $apiContext, null, $restCall );
		$this->fromJson( $json );
		return $this;
	}

	/**
	 * Send a request.
	 *
	 * @param \BitPagos\Rest\ApiContext $apiContext
	 *        	is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
	 * @param \BitPagos\Rest\RestCall $restCall
	 *        	is the Rest Call Service that is used to make rest calls
	 * @return \BitPagos\Rest\Checkout
	 */
	public function &status($transaction_id, \BitPagos\Rest\ApiContext $apiContext, BitPagos\Transport\RestCall &$restCall = null)
	{
		$payLoad = $this->toJSON();
		$json = self::executeCall( "/api/v1/transaction/{$transaction_id}?format=json", "GET", $payLoad, null, $apiContext, $restCall );
		$this->fromJson( $json );
		return $this;
	}
}
?>