<?php
namespace TDD\Test;

require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use PHPUnit\Framework\TestCase;
use TDD\Receipt;

class ReceiptTest extends TestCase
{
	public function setUp()
	{
		$this->Formatter = $this->getMockBuilder('TDD\Formatter')
			->setMethods(['currencyAmt'])
			->getMock();
		$this->Formatter->expects($this->any())
			->method('currencyAmt')
			->with($this->anything())
			->will($this->returnArgument(0));
		$this->Receipt = new Receipt($this->Formatter);
	}

	public function tearDown()
	{
		unset($this->Receipt);
	}

	/**
	 * @dataProvider provideSubtotal
	 */
	public function testSubtotal($items, $expected)
	{
		$input = [0,2,5,8];
		$coupon = null;
		$output = $this->Receipt->subtotal($items, $coupon);
		$this->assertEquals(
			$expected,
			$output,
			"Subtotal should be {$expected}"
		);
	}

	public function provideSubtotal()
	{
		return [
			'ints totalling 16' => [[1,2,5,8], 16],
			[[-1,2,5,8], 14],
			[[1,2,8], 11],
		];
	}

	public function testSubtotalAndCoupon()
	{
		$input = [0,2,5,8];
		$coupon = 0.2;
		$output = $this->Receipt->subtotal($input, $coupon);
		$this->assertEquals(
			12,
			$output,
			'Subtotal should be 12'
		);
	}

	public function testSubtotalException()
	{
		$input = [0,2,5,8];
		$coupon = 1.2;
		$this->expectException('BadMethodCallException');
		$this->Receipt->subtotal($input, $coupon);
	}

	public function testPostTaxTotal()
	{
		$items = [1,2,5,8];
		$tax = 0.20;
		$coupon = null;
		$receipt = $this->getMockBuilder('TDD\Receipt')
			->setMethods(['tax', 'subtotal'])
			->setConstructorArgs([$this->Formatter])
			->getMock();
		$receipt->expects($this->once())
			->method('subtotal')
			->with($items, $coupon)
			->will($this->returnValue(10.00));
		$receipt->expects($this->once())
			->method('tax')
			->with(10.00)
			->will($this->returnValue(1.00));
		$result = $receipt->postTaxTotal([1,2,5,8], null);

		$this->assertEquals(11, $result);
	}

	public function testTax()
	{
		$inputAmount = 10.00;
		$this->Receipt->tax = 0.20;
		$output = $this->Receipt->tax($inputAmount);
		$this->assertEquals(
			2.0,
			$output,
			'Tax amount should be 2.0'
		);
	}
}