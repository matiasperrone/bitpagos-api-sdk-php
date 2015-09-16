<?php

namespace BitPagos\Core;

/**
 * Class BitPagosConstants
 * Placeholder for BitPagos Constants
 *
 * @package BitPagos\Core
 */
class BitPagosConstants
{
	const SDK_NAME = 'bitpagos-api-sdk-php';
	const SDK_VERSION = '0.2.2';
	
	// AUTH
	const AUTH_DATA = 'client_id=[CLIEND_ID]&redirect_uri=[CALLBACK_URI]&response_type=code&scope=read+payments';
	const HEADERS = "Authorization: OAuth [TOKEN]";
	
	// SANDBOX
	const REST_SANDBOX_ENDPOINT = 'https://www.bitpagos.net';
	// PRODUCCION
	const REST_LIVE_ENDPOINT = 'https://www.bitpagos.net';
}
