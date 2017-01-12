<?php
namespace TDD;

use \BadMethodCallException;
use TDD\Formatter;

class Receipt
{
	public function __construct($Formatter)
	{
		$this->Formatter = $Formatter;
	}

	public function subtotal(array $items = [], $coupon = null)
	{
		if ($coupon > 1.00) {
			throw new BadMethodCallException;
		}

		$sum = array_sum($items);

		if ( is_float($coupon) && $coupon > 0) {
			$sum -= ($sum * $coupon);
		}

		return $sum;
	}

	public function tax($amount)
	{
		return $this->Formatter->currencyAmt($amount * $this->tax);
	}

	public function postTaxTotal($items, $coupon)
	{
		$subtotal = $this->subtotal($items, $coupon);
		return $subtotal + $this->tax($subtotal);
	}
}