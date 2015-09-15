<?php

/**
 * API handler for all REST API calls
 */
namespace BitPagos\Handler;

use BitPagos\Auth\OAuthTokenCredential;
use BitPagos\Common\BitPagosUserAgent;
use BitPagos\Core\BitPagosConstants;
use BitPagos\Core\CredentialManager;
use BitPagos\Core\HttpConfig;
use BitPagos\Exception\BitPagosConfigurationException;
use BitPagos\Exception\BitPagosInvalidCredentialException;
use BitPagos\Exception\BitPagosMissingCredentialException;

/**
 * Class RestHandler
 */
class RestHandler implements IBitPagosHandler
{
	/**
	 * Private Variable
	 *
	 * @var \BitPagos\Rest\ApiContext $apiContext
	 */
	private $apiContext;

	/**
	 * Construct
	 *
	 * @param \BitPagos\Rest\ApiContext $apiContext        	
	 */
	public function __construct($apiContext)
	{
		$this->apiContext = $apiContext;
	}

	/**
	 *
	 * @param HttpConfig $httpConfig        	
	 * @param string $request        	
	 * @param mixed $options        	
	 * @return mixed|void
	 * @throws BitPagosConfigurationException
	 * @throws BitPagosInvalidCredentialException
	 * @throws BitPagosMissingCredentialException
	 */
	public function handle($httpConfig, $request, $options)
	{
		$credential = $this->apiContext->getCredential();
		$config = $this->apiContext->getConfig();
		
		if (is_null( $credential ) and ! empty( $this->apiContext->getApiKey() ))
			$credential = false;
		
		if (is_null( $credential ))
		{
			// Try picking credentials from the config file
			$credMgr = CredentialManager::getInstance( $config );
			$credValues = $credMgr->getCredentialObject();
			
			if (! is_array( $credValues ))
			{
				throw new BitPagosMissingCredentialException( "Empty or invalid credentials passed" );
			}
			
			$credential = new OAuthTokenCredential( $credValues['clientId'], $credValues['clientSecret'] );
		}
		
		if (( is_null( $credential ) or ! ( $credential instanceof OAuthTokenCredential ) ) and empty( $this->apiContext->getApiKey() ))
		{
			throw new BitPagosInvalidCredentialException( "Invalid credentials passed" );
		}
		
		$httpConfig->setUrl( rtrim( trim( $this->_getEndpoint( $config ) ), '/' ) . ( isset( $options['path'] ) ? $options['path'] : '' ) );
		
		if (! array_key_exists( "User-Agent", $httpConfig->getHeaders() ))
		{
			$httpConfig->addHeader( "User-Agent", BitPagosUserAgent::getValue( BitPagosConstants::SDK_NAME, BitPagosConstants::SDK_VERSION ) );
		}
		
		if (! is_null( $credential ) && $credential instanceof OAuthTokenCredential and is_null( $httpConfig->getHeader( 'Authorization' ) ))
		{
			$httpConfig->addHeader( 'Authorization', "OAuth " . $credential->getAccessToken( $config ), false );
		}
		
		if ($credential === false and ! empty( $this->apiContext->getApiKey() ) and is_null( $httpConfig->getHeader( 'Authorization' ) ))
		{
			$httpConfig->addHeader( 'Authorization', "ApiKey " . $credential->getApiKey(), false );
		}
		
		if ($httpConfig->getMethod() == 'POST' || $httpConfig->getMethod() == 'PUT')
		{
			$httpConfig->addHeader( 'BitPagos-Request-Id', $this->apiContext->getRequestId() );
		}
		// Add any additional Headers that they may have provided
		$headers = $this->apiContext->getRequestHeaders();
		foreach ( $headers as $key => $value )
		{
			$httpConfig->addHeader( $key, $value );
		}
	}

	/**
	 * End Point
	 *
	 * @param array $config        	
	 *
	 * @return string
	 * @throws \BitPagos\Exception\BitPagosConfigurationException
	 */
	private function _getEndpoint($config)
	{
		if (isset( $config['service.EndPoint'] ))
		{
			return $config['service.EndPoint'];
		}
		else if (isset( $config['mode'] ))
		{
			switch ( strtoupper( $config['mode'] ) )
			{
				case 'SANDBOX' :
					return BitPagosConstants::REST_SANDBOX_ENDPOINT;
					break;
				case 'LIVE' :
					return BitPagosConstants::REST_LIVE_ENDPOINT;
					break;
				default :
					throw new BitPagosConfigurationException( 'The mode config parameter must be set to either sandbox/live' );
					break;
			}
		}
		else
		{
			// Defaulting to Sandbox
			return BitPagosConstants::REST_SANDBOX_ENDPOINT;
		}
	}
}
