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
 * @property \BitPagos\Api\itemList itemList
 * @property \BitPagos\Api\Ipn ipn
 * @property \BitPagos\Api\Amount amount
 * @property \BitPagos\Api\RedirectUrls redirect_urls
 * @property string reference_id
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
	}

	/**
	 * Establece la lista de items
	 *
	 * @param \BitPagos\Api\ItemList $itemList        	
	 * @return string
	 */
	public function setItemList(\BitPagos\Api\ItemList $itemList)
	{
		$this->itemList = $itemList;
		return $this;
	}

	/**
	 * Devuelve la lista de items
	 *
	 * @return string
	 */
	public function getItemList()
	{
		return $this->itemList;
	}

	/**
	 * Establece el destino y formato de la llamda del IPN
	 *
	 * @param \BitPagos\Api\Ipn $ipn        	
	 * @return string
	 */
	public function setIpn(\BitPagos\Api\Ipn $ipn)
	{
		$this->ipn = $ipn;
		return $this;
	}

	/**
	 * Devuelve el objeto Ipn
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
	 * @param \BitPagos\Api\Amount $amount        	
	 *
	 * @return $this
	 */
	public function setAmount($amount)
	{
		$this->amount = $amount;
		return $this;
	}

	/**
	 * Amount being collected.
	 *
	 * @return \BitPagos\Api\Amount
	 */
	public function getAmount()
	{
		return $this->amount;
	}

	/**
	 * Redirect urls required
	 *
	 * @param \BitPagos\Api\RedirectUrls $redirect_urls        	
	 *
	 * @return $this
	 */
	public function setRedirectUrls(\BitPagos\Api\RedirectUrls $redirect_urls)
	{
		$this->redirect_urls = $redirect_urls;
		return $this;
	}

	/**
	 * Redirect urls required
	 *
	 * @return \BitPagos\Api\RedirectUrls
	 */
	public function getRedirectUrls()
	{
		return $this->redirect_urls;
	}

	/**
	 * Set the caller reference_id
	 *
	 * @param string $reference_id        	
	 *
	 * @return $this
	 */
	public function setReferenceID($reference_id)
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
	public function create(\BitPagos\Rest\ApiContext $apiContext, $restCall = null)
	{
		$payLoad = $this->toJSON();
		$json = self::executeCall( "/api/v1/checkout/?format=json", "POST", $payLoad, null, $apiContext, $restCall );
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
	public function status($transaction_id, \BitPagos\Rest\ApiContext $apiContext, $restCall = null)
	{
		$payLoad = $this->toJSON();
		$json = self::executeCall( "/api/v1/transaction/{$transaction_id}?format=json", "GET", $payLoad, null, $apiContext, $restCall );
		$this->fromJson( $json );
		return $this;
	}
}
?>