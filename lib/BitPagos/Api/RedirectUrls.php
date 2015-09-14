<?php

namespace BitPagos\Api;

use BitPagos\Common\BitPagosModel;
use BitPagos\Validation\UrlValidator;

/**
 * Class RedirectUrls
 *
 * Redirect urls required only when using payment_method as BitPagos - the only settings supported are return and cancel urls.
 *
 * @package BitPagos\Api
 *         
 * @property string return_url
 * @property string cancel_url
 */
class RedirectUrls extends BitPagosModel
{

	/**
	 * Url where the payer would be redirected to after approving the payment.
	 *
	 * @param string $return_url        	
	 * @throws \InvalidArgumentException
	 * @return $this
	 */
	public function setReturnUrl($return_url)
	{
		UrlValidator::validate( $return_url, "ReturnUrl" );
		$this->return_url = $return_url;
		if (empty( $this->getPendingUrl() ))
			$this->setPendingUrl( $return_url );
		return $this;
	}

	/**
	 * Url where the payer would be redirected to after approving the payment.
	 *
	 * @return string
	 */
	public function getReturnUrl()
	{
		return $this->return_url;
	}

	/**
	 * Url where the payer would be redirected to if the payment is pending.
	 *
	 * @param string $pending_url        	
	 * @throws \InvalidArgumentException
	 * @return $this
	 */
	public function setPendingUrl($pending_url)
	{
		UrlValidator::validate( $pending_url, "ReturnUrl" );
		$this->pending_url = $pending_url;
		return $this;
	}

	/**
	 * Url where the payer would be redirected to if the payment is pending.
	 *
	 * @return string
	 */
	public function getPendingUrl()
	{
		return $this->pending_url;
	}

	/**
	 * Url where the payer would be redirected to after canceling the payment.
	 *
	 * @param string $cancel_url        	
	 * @throws \InvalidArgumentException
	 * @return $this
	 */
	public function setCancelUrl($cancel_url)
	{
		UrlValidator::validate( $cancel_url, "CancelUrl" );
		$this->cancel_url = $cancel_url;
		return $this;
	}

	/**
	 * Url where the payer would be redirected to after canceling the payment.
	 *
	 * @return string
	 */
	public function getCancelUrl()
	{
		return $this->cancel_url;
	}
}
