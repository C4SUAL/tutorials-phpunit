<?php
namespace TDD\Test;

require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use PHPUnit\Framework\TestCase;

use TDD\Blender;

class BlenderTest extends TestCase
{

	public function setUp()
	{
		$this->Blender = new Blender;
	}

	public function testGetSpeed()
	{
		$this->Blender->speed = 2;

		$this->assertEquals(
			2,
			$this->Blender->getSpeed(),
			'Speed should be 2'
		);
	}

	/**
	 * @dataProvider provideSetSpeed
	 */
	public function testSetSpeed($speed, $expected)
	{
		$this->Blender->full = true;

		$this->Blender->setSpeed($speed);

		$this->assertEquals(
			$expected,
			$this->Blender->speed,
			"Speed should be {$expected}"
		);
	}

	public function provideSetSpeed()
	{
		return [
			[-1, 0],
			[0, 0],
			[1, 1],
			[9, 9],
			[10, 9]
		];
	}

	public function testIsFull()
	{
		$this->Blender->full = TRUE;
		$this->assertTrue($this->Blender->isFull(), 'Blender should be full');


		$this->Blender->full = FALSE;
		$this->assertNotTrue($this->Blender->isFull(), 'Blender should not be full');
	}

	public function testFill()
	{
		// @pre !isFull
		// $this->Blender->full = true;
		// $this->assertFalse(
		// 	$this->Blender->full,
		// 	'@pre Blender should not be full'
		// );

		$this->Blender->fill();

		// @post isFull
		$this->assertTrue(
			$this->Blender->full,
			'Blender should be full'
		);
	}

	public function testEmpty()
	{
		$this->Blender->full = true;

		$this->Blender->void();

		$this->assertNotTrue(
			$this->Blender->full,
			'Blender should not be full'
		);
	}

	/**
	 * @dataProvider provideRun
	 */
	public function testRun($input, $pass, $msg)
	{
		try
		{
			for ($i = 0; $i < strlen($input); $i++)
			{
				$cmd = $input[$i];

				if (is_numeric($cmd))
				{
					$this->Blender->setSpeed( (int) $cmd);
				}
				else
				{
					switch ($cmd)
					{
						case " ":
							continue;
						case 'F':
							$this->Blender->fill();
							break;
						case 'E':
							$this->Blender->void();
							break;
						default:
							throw new \RuntimeException('Unknown test directive :' . $cmd);
					}
				}
			}
			// if it's not thrown an exception by now then test passes

		}
		catch (\RuntimeException $e)
		{
			// expect fail?
			if ($pass) $this->fail($msg);
		}
	}

	public function provideRun()
	{
		return [
			// input, pass?, message
			['F123456789876543210E', true, 'Blender should perform full sequence'],
			['F5', false, 'Speed to high'],
			['1', false, 'Empty'],
			['F10E1', false, 'Empty'],
			['F1235', false, 'Skips'],
			['FE', true, 'Never turns on'],
			['FE1', false, 'Empty whilst running'],
			['E', false, 'Never filled']
		];
	}
}