<?php

namespace Shevsky\Ecom\Domain\Order;

use Shevsky\Ecom\Enum;
use Shevsky\Ecom\Persistence\Order\IOrder;
use Shevsky\Ecom\Persistence\Order\IOrderDimensionTypeClassificator;

class OrderDimensionTypeClassificator implements IOrderDimensionTypeClassificator
{
	private $weight;
	private $height;
	private $length;
	private $width;

	private $dimension_type_params = [
		Enum\DimensionType::SMALL => [260, 170, 80, 1],
		Enum\DimensionType::MEDIUM => [300, 200, 150, 3],
		Enum\DimensionType::LARGE => [400, 270, 180, 5],
		Enum\DimensionType::EXTRA_LARGE => [530, 360, 220, 10],
	];

	private $oversized_max_weight = 15;
	private $oversized_max_side_sum = 1600;
	private $oversized_max_one_side = 600;

	/**
	 * @param IOrder $order
	 */
	public function __construct(IOrder $order)
	{
		$this->weight = $order->getWeight();
		$this->height = $order->getHeight();
		$this->length = $order->getLength();
		$this->width = $order->getWidth();
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public function getDimensionType()
	{
		$this->validate();

		try
		{
			return $this->tryDimensionType();
		}
		catch (\Exception $e)
		{
			if ($this->isOversized())
			{
				return Enum\DimensionType::OVERSIZED;
			}

			throw new \Exception('Не удалось определить типоразмер отправления', $e->getCode(), $e);
		}
	}

	/**
	 * @throws \Exception
	 */
	private function validate()
	{
		if (empty($this->weight) || empty($this->height) || empty($this->length) || empty($this->width))
		{
			throw new \Exception('Размеры или вес заказа неизвестны');
		}

		if ($this->weight < 0 || $this->height < 0 || $this->length < 0 || $this->width < 0)
		{
			throw new \Exception('Размеры или вес заказа не могут быть отрицательными');
		}
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	private function tryDimensionType()
	{
		foreach ($this->dimension_type_params as $dimension_type => $params)
		{
			if ($this->isParamsMatching(...$params))
			{
				return $dimension_type;
			}
		}

		throw new \Exception('Не удалось определить типоразмер отправления');
	}

	/**
	 * @param float $max_a
	 * @param float $max_b
	 * @param float $max_c
	 * @param float $max_weight
	 * @return bool
	 */
	private function isParamsMatching($max_a, $max_b, $max_c, $max_weight)
	{
		if ($this->weight > $max_weight)
		{
			return false;
		}

		$correct_tests = [];

		$max_dimensions = [$max_a, $max_b, $max_c];
		sort($max_dimensions);

		$dimensions = [$this->height, $this->length, $this->width];
		foreach ($max_dimensions as $i => $max_value)
		{
			foreach ($dimensions as $m => $value)
			{
				if ($value <= $max_value)
				{
					$correct_tests[] = true;
					unset($dimensions[$m]);
					break 1;
				}
			}
		}

		return count($correct_tests) === 3;
	}

	/**
	 * @return bool
	 */
	private function isOversized()
	{
		if ($this->weight > $this->oversized_max_weight)
		{
			return false;
		}

		$side_sum = $this->height + $this->length + $this->width;
		if ($side_sum > $this->oversized_max_side_sum)
		{
			return false;
		}

		$sides = [$this->height, $this->length, $this->width];
		$is_every_less_than_max_one_side = array_reduce($sides, [__CLASS__, 'everyLessThanMaxOneSideReducer'], true);
		if (!$is_every_less_than_max_one_side)
		{
			return false;
		}

		return true;
	}

	/**
	 * @param bool $carry
	 * @param float $side
	 * @return bool
	 */
	private function everyLessThanMaxOneSideReducer($carry, $side)
	{
		return $carry && $side <= $this->oversized_max_one_side;
	}
}
