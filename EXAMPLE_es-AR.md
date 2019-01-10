En Español
==========

Utilicé como base las clases del SDK de PayPal que está muy buena.

``` php
<?php
namespace ejemplo;

$directorio_actual = str_replace( '\\', '/', __DIR__ ) . '/';
$autoload = $directorio_actual . '../third_party/vendor/autoload.php';
require_once $autoload;

use \BitPagos\Api\Checkout;
use \BitPagos\Api\Item;
use \BitPagos\Rest\ApiContext;
use \BitPagos\Transport\RestCall;

define('BITPAGOS_APPKEY', 'aaaaaaaaaaaaaaaaaaa');

/**
 *
 * @author Matias
 *
 * @property string restCall
 * @property string Soporte
 * @property string BaseDatos
 * @property string UsuarioID
 * @property string BitPagos
 * @property string ultimoError
 */
class Mi_BitPagos
{
    public $restCall;
    private $BitPagos;

    /**
     * Consulta una transacción en BitPagos y devuelve el resultado como \BitPagos\Api\Checkout
     *
     * @param integer $transaction_id
     *          id de la transacción
     *
     * @return \BitPagos\Api\Checkout
     */
    public function &consultarTransaccion($transaction_id)
    {
        $apiContext = new ApiContext();
        $apiContext->setApiKey( BITPAGOS_APPKEY )->setConfig( $this->configuracion() );
        $this->restCall = new RestCall( $apiContext );
        $this->BitPagos = new Checkout();
        $this->BitPagos->status( $transaction_id, $apiContext, $this->restCall );
        return $this->BitPagos;
    }

    /**
     * Envia el pedido a BitPagos y devuelve un objeto Checkout con las propiedades devueltas por el servidor
     *
     * @param array $params
     *   Es un array que tiene varios campos: referencia, moneda, monto, descripcion, 
     *   url_exito, url_cancela, url_pendiente, url_ipn (opcional)
     * 
     * @return \BitPagos\Api\Checkout
     */
    private function &enviar(array &$params)
    {
        $request_id = $params['referencia'] . '_' . date( 'YmdHis' );

        $item = new Item();
        $item->setQuantity( 1 )
            ->setPrice( $params['monto'] )
            ->setTitle( $params['descripcion'] );

        $this->BitPagos = new Checkout();
        $this->BitPagos->setCurrency( $params['moneda'] )
            ->setAmount( $params['monto'] )
            ->addItem( $item )
            ->setReferenceID( $params['referencia'] )
            ->setReturnSuccess( $params['url_exito'] )
            ->setReturnCancel( $params['url_cancela'] )
            ->setReturnPending( $params['url_pendiente'] );

        if (isset($params['url_ipn']) and !empty($params['url_ipn']))
            $this->BitPagos->setIpn( $params['url_ipn'] );

        $apiContext = new ApiContext( null, $request_id );
        $apiContext->setApiKey( BITPAGOS_APPKEY )->setConfig( $this->configuracion() );
        $this->restCall = new RestCall( $apiContext );
        $this->BitPagos->create( $apiContext, $this->restCall );

        return $this->BitPagos;
    }

    private function configuracion($param)
    {
        return ['log.FileName' => str_replace( '\\', '/', __DIR__ ) . '/../' . 'log/bitpagos-' . date( 'Y-m-d' ) . '.log',
                'log.LogLevel' => 'FINE',
                'cache.enabled' => false,
                'http.CURLOPT_FOLLOWLOCATION' => true,
                'http.CURLOPT_MAXREDIRS' => 3
        ];
    }

    private function do_log ( $sLogText )
    {
        $sText = ob_get_contents();
        if ( $sText )
            ob_get_clean();

        if ( $this->pp_log And $this->pp_log_file )
        {
            fwrite($this->pp_log_file, ($sText ? $sText . "\n" : '') . $sLogText);
        }
    }

}
```

Supongamos un ejemplito de uso clásico:

```
        $bitpagos = new \ejemplo\Mi_BitPagos();

        $resultado = $bitpagos->enviar( $datos );

        $params['trxbitpagos'] = $resultado->txn_id;
        $params['estado'] = $resultado->status;
        $params['usuario_email'] = $resultado->email;
        $params['usuario_nombre'] = $resultado->first_name;
        $params['usuario_apellido'] = $resultado->last_name;
        $params['url_pago'] = $resultado->checkout_url;
        $params['respuesta'] = $this->restCall->httpConnection->responseHeaders . $this->restCall->httpConnection->responseBody;
        $params['request'] = trim( $this->restCall->httpConnection->requestHeaders . $resultado->toJSON() );
        $params['bitpagos_id'] = $resultado->id;

        $ok = $this->guardarResultado( $params );
```

Fuera de la clase expuesta anteriormente podés encontrar más info en el SDK de PayPal.

En el bajo nivel tendrías:
Los "headers":
`$this->restCall->httpConnection->responseHeaders`

El "body":
`$this->restCall->httpConnection->responseBody`

Pero lo mejor y más interesante viene con la clase **\BitPagos\Api\Checkout** y las funciones:
`->toJSON()`
`->toArray()`
