<?php

namespace BitPagos\Common;

use BitPagos\Common\BitPagosModel;
use BitPagos\Rest\ApiContext;
use BitPagos\Rest\IResource;
use BitPagos\Transport\RestCall;

/**
 * Class BitPagosResourceModel
 * An Executable BitPagosModel Class
 *
 * @property \BitPagos\Api\Links[] links
 * @package BitPagos\Common
 */
class BitPagosResourceModel extends BitPagosModel implements IResource
{

	/**
	 * Sets Links
	 *
	 * @param \BitPagos\Api\Links[] $links
	 *
	 * @return $this
	 */
	public function setLinks($links)
	{
		$this->links = $links;
		return $this;
	}

	/**
	 * Gets Links
	 *
	 * @return \BitPagos\Api\Links[]
	 */
	public function getLinks()
	{
		return $this->links;
	}

	public function getLink($rel)
	{
		foreach ( $this->links as $link )
		{
			if ($link->getRel() == $rel)
			{
				return $link->getHref();
			}
		}
		return null;
	}

	/**
	 * Append Links to the list.
	 *
	 * @param \BitPagos\Api\Links $links
	 * @return $this
	 */
	public function addLink($links)
	{
		if (! $this->getLinks())
		{
			return $this->setLinks( array($links) );
		}
		else
		{
			return $this->setLinks( array_merge( $this->getLinks(), array($links) ) );
		}
	}

	/**
	 * Remove Links from the list.
	 *
	 * @param \BitPagos\Api\Links $links
	 * @return $this
	 */
	public function removeLink($links)
	{
		return $this->setLinks( array_diff( $this->getLinks(), array($links) ) );
	}

	/**
	 * Execute SDK Call to BitPagos services
	 *
	 * @param string $url
	 * @param string $method
	 * @param string $payLoad
	 * @param array $headers
	 * @param BitPagos\Rest\ApiContext $apiContext
	 * @param BitPagos\Transport\RestCall $restCall
	 * @param array $handlers
	 * @return string json response of the object
	 */
	protected static function executeCall(
										$url,
										$method,
										$payLoad,
										\BitPagos\Rest\ApiContext $apiContext,
										$headers = array(),
										\BitPagos\Transport\RestCall &$restCall = null,
										$handlers = array('BitPagos\Handler\RestHandler'))
	{
		if (empty( $apiContext ))
		{
			throw new \InvalidArgumentException( 'The context can not be empty' );
		}

		//Initialize the context and rest call object if not provided explicitly
		if (! $restCall)
			$restCall = new RestCall( $apiContext );

			//Make the execution call
		$json = $restCall->execute( $handlers, $url, $method, $payLoad, $headers );
		return $json;
	}
}
