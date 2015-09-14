<?php

namespace BitPagos\Exception;

/**
 * Class BitPagosConfigurationException
 *
 * @package BitPagos\Exception
 */
class BitPagosConfigurationException extends \Exception
{

    /**
     * Default Constructor
     *
     * @param string|null $message
     * @param int  $code
     */
    public function __construct($message = null, $code = 0)
    {
        parent::__construct($message, $code);
    }
}
