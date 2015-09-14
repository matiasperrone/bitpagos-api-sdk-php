<?php

namespace BitPagos\Api;

use BitPagos\Common\BitPagosModel;
use BitPagos\Validation\UrlValidator;

/**
 * Class Ipn
 *
 * IPN urls required only when using IPN Notification.
 *
 * @package BitPagos\Api
 *         
 * @property string url
 * @property string format
 */
class Ipn extends BitPagosModel
{

	/**
	 * Default Constructor
	 */
	public function __construct()
	{
		$this->setJson();
	}

	/**
	 * Url where the payer would be redirected to after approving the payment.
	 *
	 * @param string $return_url        	
	 * @throws \InvalidArgumentException
	 * @return $this
	 */
	public function setUrl($url)
	{
		UrlValidator::validate( $url );
		$this->url = $url;
		return $this;
	}

	/**
	 * Url where the payer would be redirected to after approving the payment.
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * Sets the IPN format to JSON (default).
	 *
	 * @return $this
	 */
	public function setJson()
	{
		$this->format = 'json';
		return $this;
	}

	/**
	 * Sets the IPN format to Form.
	 *
	 * @return string
	 */
	public function setForm()
	{
		$this->format = 'form';
		return $this;
	}

	/**
	 * Gets the IPN format.
	 *
	 * @return string
	 */
	public function getFormat()
	{
		return $this->format;
	}
}
