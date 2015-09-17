<?php

namespace BitPagos\Transport;

use BitPagos\Core\HttpConfig;
use BitPagos\Core\HttpConnection;
use BitPagos\Core\LoggingManager;
use BitPagos\Rest\ApiContext;

/**
 * Class BitPagosRestCall
 *
 * @package BitPagos\Transport
 */
class RestCall
{
	
	/**
	 * BitPagos Logger
	 *
	 * @var LoggingManager logger interface
	 */
	private $logger;
	
	/**
	 * API Context
	 *
	 * @var ApiContext
	 */
	private $apiContext;
	
	/**
	 * HTTP Connection
	 * Is not available until the execute method is called
	 *
	 * @var httpConnection
	 */
	public $httpConnection;

	/**
	 * Default Constructor
	 *
	 * @param ApiContext $apiContext        	
	 */
	public function __construct(ApiContext $apiContext)
	{
		$this->apiContext = $apiContext;
		$this->logger = LoggingManager::getInstance( __CLASS__ );
	}

	/**
	 *
	 * @param array $handlers
	 *        	Array of handlers
	 * @param string $path
	 *        	Resource path relative to base service endpoint
	 * @param string $method
	 *        	HTTP method - one of GET, POST, PUT, DELETE, PATCH etc
	 * @param string $data
	 *        	Request payload
	 * @param array $headers
	 *        	HTTP headers
	 * @return mixed
	 * @throws \BitPagos\Exception\BitPagosConnectionException
	 */
	public function execute($handlers = array(), $path, $method, $data = '', $headers = array())
	{
		$config = $this->apiContext->getConfig();
		$httpConfig = new HttpConfig( null, $method, $config );
		$headers = $headers ? $headers : array();
		$httpConfig->setHeaders( $headers + array('Content-Type' => 'application/json') );
		
		/**
		 *
		 * @var \BitPagos\Handler\IBitPagosHandler $handler
		 */
		foreach ( $handlers as $handler )
		{
			if (! is_object( $handler ))
			{
				$fullHandler = "\\" . ( string ) $handler;
				$handler = new $fullHandler( $this->apiContext );
			}
			$handler->handle( $httpConfig, $data, array('path' => $path, 'apiContext' => $this->apiContext) );
		}
		$this->httpConnection = new HttpConnection( $httpConfig, $config );
		$response = $this->httpConnection->execute( $data );
		
		return $response;
	}
}
