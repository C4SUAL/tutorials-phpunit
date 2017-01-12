<?php
namespace TDD\Test;

require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use PHPUnit\Framework\TestCase;
use TDD\Receipt;

class ReceiptTest extends TestCase
{
	public function setUp()
	{
		$this->Receipt = new Receipt;
	}

	/**
	 * @dataProvider provideTotal
	 */
	public function testTotal($items, $expected)
	{
		$input = [0,2,5,8];
		$coupon = null;
		$output = $this->Receipt->total($items, $coupon);
		$this->assertEquals(
			$expected,
			$output,
			"Total should be {$expected}"
		);
	}

	public function provideTotal()
	{
		return [
			'ints totalling 16' => [[1,2,5,8], 16],
			[[-1,2,5,8], 14],
			[[1,2,8], 11],
		];
	}

	public function testTotalAndCoupon()
	{
		$input = [0,2,5,8];
		$coupon = 0.2;
		$output = $this->Receipt->total($input, $coupon);
		$this->assertEquals(
			12,
			$output,
			'Total should be 12'
		);
	}

	public function testTotalException()
	{
		$input = [0,2,5,8];
		$coupon = 1.2;
		$this->expectException('BadMethodCallException');
		$this->Receipt->total($input, $coupon);
	}

	public function testPostTaxTotal()
	{
		$items = [1,2,5,8];
		$tax = 0.20;
		$coupon = null;
		$receipt = $this->getMockBuilder('TDD\Receipt')
			->setMethods(['tax', 'total'])
			->getMock();
		$receipt->expects($this->once())
			->method('total')
			->with($items, $coupon)
			->will($this->returnValue(10.00));
		$receipt->expects($this->once())
			->method('tax')
			->with(10.00, $tax)
			->will($this->returnValue(1.00));
		$result = $receipt->postTaxTotal([1,2,5,8], 0.20, null);

		$this->assertEquals(11, $result);
	}

	public function tearDown()
	{
		unset($this->Receipt);
	}

	public function testTax()
	{
		$inputAmount = 10.00;
		$taxValue = 0.2;
		$output = $this->Receipt->tax($inputAmount, $taxValue);
		$this->assertEquals(
			2.0,
			$output,
			'Tax amount should be 2.0'
		);
	}
}