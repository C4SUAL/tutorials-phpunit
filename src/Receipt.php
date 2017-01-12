<?php
namespace TDD;

use \BadMethodCallException;

class Receipt
{
	public function total(array $items = [], $coupon = null)
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

	public function tax($amount, $rate)
	{
		return $amount * $rate;
	}

	public function postTaxTotal($items, $tax, $coupon)
	{
		$subtotal = $this->total($items, $coupon);
		return $subtotal += $this->tax($subtotal, $tax);
	}
}