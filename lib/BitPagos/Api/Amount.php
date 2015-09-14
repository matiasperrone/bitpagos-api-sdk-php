<?php

namespace BitPagos\Api;

use BitPagos\Common\BitPagosModel;
use BitPagos\Converter\FormatConverter;
use BitPagos\Validation\NumericValidator;

/**
 * Class Amount
 *
 * payment amount with break-ups.
 *
 * @package BitPagos\Api
 *         
 * @property string currency
 * @property string total
 * @property \BitPagos\Api\Details details
 */
class Amount extends BitPagosModel
{

	/**
	 * 3 letter currency code
	 *
	 * @param string $currency        	
	 *
	 * @return $this
	 */
	public function setCurrency($currency)
	{
		$this->currency = $currency;
		return $this;
	}

	/**
	 * 3 letter currency code
	 *
	 * @return string
	 */
	public function getCurrency()
	{
		return $this->currency;
	}

	/**
	 * Total amount charged as part of this payment.
	 *
	 *
	 * @param string|double $total        	
	 *
	 * @return $this
	 */
	public function setTotal($total)
	{
		NumericValidator::validate( $total, "Total" );
		$total = FormatConverter::formatToPrice( $total, $this->getCurrency() );
		$this->total = $total;
		return $this;
	}

	/**
	 * Total amount charged as part of this payment.
	 *
	 * @return string
	 */
	public function getTotal()
	{
		return $this->total;
	}
}