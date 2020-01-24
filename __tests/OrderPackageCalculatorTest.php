<?php

require_once './bootstrap.php';

use PHPUnit\Framework\TestCase;
use Shevsky\Ecom\Domain\Services\OrderPackageCalculator;

class OrderPackageCalculatorTest extends TestCase
{
	private function getPackage($items)
	{
		$calculator = new OrderPackageCalculator($items);

		$height = $calculator->calculate('height');
		$length = $calculator->calculate('length');
		$width = $calculator->calculate('width');

		$package = [$height, $length, $width];
		sort($package);

		return $package;
	}

	public function testOneItemCase()
	{
		$items = [
			[
				'height' => 150,
				'length' => 30.5,
				'width' => 22,
				'quantity' => 1,
			],
		];

		$this->assertEqualsCanonicalizing([22.0, 30.5, 150], $this->getPackage($items));
	}

	public function testTwoItemsCase()
	{
		$items = [
			[
				'height' => 100,
				'length' => 200,
				'width' => 50,
				'quantity' => 1,
			],
			[
				'height' => 25,
				'length' => 400,
				'width' => 200,
				'quantity' => 2,
			]
		];

		$this->assertEqualsCanonicalizing([100.0, 200.0, 400.0], $this->getPackage($items));
	}

	public function testThreeItemsCase()
	{
		$items = [
			[
				'height' => 100,
				'length' => 200,
				'width' => 50,
				'quantity' => 3,
			],
			[
				'height' => 25,
				'length' => 400,
				'width' => 200,
				'quantity' => 2,
			],
			[
				'height' => 20,
				'length' => 20,
				'width' => 20,
				'quantity' => 1
			]
		];

		$this->assertEqualsCanonicalizing([200.0, 220.0, 400.0], $this->getPackage($items));
	}

	public function testInvalidParamsCase1()
	{
		$this->expectException(\Exception::class);

		(new OrderPackageCalculator([]))->calculate('');
	}

	public function testInvalidParamsCase2()
	{
		$this->expectException(\Exception::class);

		(new OrderPackageCalculator([]))->calculate('qwe');
	}

	public function testInvalidParamsCase3()
	{
		$this->expectException(\Exception::class);

		(new OrderPackageCalculator([]))->calculate('default_weight');
	}
}
