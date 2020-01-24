<?php

require_once './bootstrap.php';

use PHPUnit\Framework\TestCase;
use Shevsky\Ecom\Domain\Services\DimensionTypeClassificator;
use \Shevsky\Ecom\Enum;
use \Shevsky\Ecom\Domain\Order\Order;

class OrderDimensionTypeClassificatorTest extends TestCase
{
	public function testInvalidCase()
	{
		$this->expectException(\Exception::class);

		$order = new Order([]);
		$classificator = DimensionTypeClassificator::buildWithOrder($order);
		$classificator->getDimensionType();
	}

	public function testNegativeCase()
	{
		$this->expectException(\Exception::class);

		$order = new Order(
			[
				'total_weight' => -1,
				'total_height' => -1,
				'total_length' => -1,
				'total_width' => -1,
			]
		);
		$classificator = DimensionTypeClassificator::buildWithOrder($order);
		$classificator->getDimensionType();
	}

	public function testEmptyCase()
	{
		$this->expectException(\Exception::class);

		$order = new Order(
			[
				'total_weight' => 0,
				'total_height' => 0,
				'total_length' => 0,
				'total_width' => 0,
			]
		);
		$classificator = DimensionTypeClassificator::buildWithOrder($order);
		$classificator->getDimensionType();
	}

	public function testSmallCase()
	{
		$order = new Order(
			[
				'total_weight' => 1,
				'total_height' => 170,
				'total_length' => 50,
				'total_width' => 240,
			]
		);
		$classificator = DimensionTypeClassificator::buildWithOrder($order);

		$this->assertSame(Enum\DimensionType::SMALL, $classificator->getDimensionType());
	}

	public function testMediumCase()
	{
		$order = new Order(
			[
				'total_weight' => 1,
				'total_height' => 299,
				'total_length' => 200,
				'total_width' => 150,
			]
		);
		$classificator = DimensionTypeClassificator::buildWithOrder($order);

		$this->assertSame(Enum\DimensionType::MEDIUM, $classificator->getDimensionType());
	}

	public function testLargeCase()
	{
		$order = new Order(
			[
				'total_weight' => 5,
				'total_height' => 180,
				'total_length' => 10,
				'total_width' => 400,
			]
		);
		$classificator = DimensionTypeClassificator::buildWithOrder($order);

		$this->assertSame(Enum\DimensionType::LARGE, $classificator->getDimensionType());
	}

	public function testExtraLargeCase()
	{
		$order = new Order(
			[
				'total_weight' => 9.5,
				'total_height' => 360,
				'total_length' => 360,
				'total_width' => 220,
			]
		);
		$classificator = DimensionTypeClassificator::buildWithOrder($order);

		$this->assertSame(Enum\DimensionType::EXTRA_LARGE, $classificator->getDimensionType());
	}

	public function testOversizedCase1()
	{
		$order = new Order(
			[
				'total_weight' => 14,
				'total_height' => 600,
				'total_length' => 400,
				'total_width' => 400,
			]
		);
		$classificator = DimensionTypeClassificator::buildWithOrder($order);

		$this->assertSame(Enum\DimensionType::OVERSIZED, $classificator->getDimensionType());
	}

	public function testOversizedCase2()
	{
		$order = new Order(
			[
				'total_weight' => 15,
				'total_height' => 100,
				'total_length' => 100,
				'total_width' => 50,
			]
		);
		$classificator = DimensionTypeClassificator::buildWithOrder($order);

		$this->assertSame(Enum\DimensionType::OVERSIZED, $classificator->getDimensionType());
	}

	public function testUnknownCaseOversizedWeight()
	{
		$this->expectException(\Exception::class);

		$order = new Order(
			[
				'total_weight' => 16,
				'total_height' => 600,
				'total_length' => 400,
				'total_width' => 40,
			]
		);
		$classificator = DimensionTypeClassificator::buildWithOrder($order);
		$classificator->getDimensionType();
	}

	public function testUnknownCaseOversizedSum()
	{
		$this->expectException(\Exception::class);

		$order = new Order(
			[
				'total_weight' => 15,
				'total_height' => 600,
				'total_length' => 600,
				'total_width' => 600,
			]
		);
		$classificator = DimensionTypeClassificator::buildWithOrder($order);
		$classificator->getDimensionType();
	}

	public function testUnknownCaseOversizedOneSide()
	{
		$this->expectException(\Exception::class);

		$order = new Order(
			[
				'total_weight' => 15,
				'total_height' => 60,
				'total_length' => 60,
				'total_width' => 900,
			]
		);
		$classificator = DimensionTypeClassificator::buildWithOrder($order);
		$classificator->getDimensionType();
	}
}
