<?php

namespace BitPagos\Handler;

/**
 * Interface IBitPagosHandler
 *
 * @package BitPagos\Handler
 */
interface IBitPagosHandler
{

	/**
	 *
	 * @param \BitPagos\Core\HttpConfig $httpConfig        	
	 * @param string $request        	
	 * @param mixed $options        	
	 * @return mixed
	 */
	public function handle($httpConfig, $request, $options);
}
