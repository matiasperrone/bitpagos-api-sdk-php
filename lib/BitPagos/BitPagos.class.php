<?php

namespace welcome2ba;

class BitPagos
{
	public $client_id = null;
	public $secret = null;
	public $key = null;
	public $format = 'json';
	public $urls = [
			'oauth2' => ['authorize' => 'https://www.bitpagos.com/oauth2/authorize', 'token' => 'https://www.bitpagos.com/oauth2/access_token'], 
			'redirect_uri' => null, 
			'api' => 'https://www.bitpagos.com/api/v1/checkout/?format=[FORMAT]'];
	private $oauth2_data = 'client_id=[CLIEND_ID]&redirect_uri=[CALLBACK_URI]&response_type=code&scope=read+payments';
	private $headers = "Authorization: OAuth [TOKEN]";

	/**
	 * Constructor
	 */
	public function __construct()
	{
	}

	public function getAPIUrl()
	{
		return str_replace( '[FORMAT]', $this->format, $this->urls );
	}

	/*
	 * ==========================================================================
	 * Metodo: ultimoError
	 * Devuelve el mensaje del último error.
	 * Devuelve:
	 * Parametros:
	 * ============================================================================
	 */
	public function ultimoError()
	{
		return $this->ultimoError;
	}

	/**
	 * Consultar al servidor sobre la información acerca de un pago.
	 *
	 * @param $params array
	 *        	(por referencia) Datos a consultar
	 */
	public function consultar(array &$params)
	{
		try
		{
		}
		catch ( Exception $e )
		{
			// Mensaje para el usuario;
			$sMensajeAlUsuario = "No se pudo consultar el estado de la transacción. Ha ocurrido un error interno";
			
			// Informa del error
			$aArgumentos = func_get_args();
			$this->Soporte->informarError( $oError, $sMensajeAlUsuario, __CLASS__, __FUNCTION__, $aArgumentos );
			
			// Devuelve un error
			$this->ultimoError = $sMensajeAlUsuario;
			throw new Exception( $sMensajeAlUsuario );
		}
	}
}
?>