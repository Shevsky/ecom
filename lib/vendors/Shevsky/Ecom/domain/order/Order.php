<?php

namespace Shevsky\Ecom\Domain\Order;

use Shevsky\Ecom\Enum;
use Shevsky\Ecom\Persistence\Order\IOrder;

class Order implements IOrder
{
	private $params;
	private $package_calculator;
	private $dimension_type;

	/**
	 * @param array $params = [
	 *  'total_weight' => float,
	 *  'total_height' => float,
	 *  'total_length' => float,
	 *  'total_width' => float,
	 *  'total_price' => float,
	 *  'total_raw_price' => float,
	 *  'items' => [
	 *      $index => [
	 *          'id' => string,
	 *          'sku' => string,
	 *          'name' => string,
	 *          'weight' => float,
	 *          'price' => float,
	 *          'quantity' => int,
	 *          'height' => float,
	 *          'length' => float,
	 *          'width' => float,
	 *      ]
	 *  ]
	 * ]
	 */
	public function __construct(array $params)
	{
		$this->params = $params;

		if (isset($params['items']) && is_array($params['items']))
		{
			$this->package_calculator = new OrderPackageCalculator($params['items']);
		}
	}

	/**
	 * @return float
	 */
	public function getWeight()
	{
		try
		{
			return $this->tryProperty('total_weight');
		}
		catch (\Exception $e)
		{
			return 0;
		}
	}

	/**
	 * @return float
	 */
	public function getHeight()
	{
		try
		{
			return $this->tryProperty('total_height');
		}
		catch (\Exception $e)
		{
			try
			{
				return $this->tryCalculateProperty(Enum\OrderProperty::HEIGHT);
			}
			catch (\Exception $e)
			{
				return 0;
			}
		}
	}

	/**
	 * @return float
	 */
	public function getLength()
	{
		try
		{
			return $this->tryProperty('total_length');
		}
		catch (\Exception $e)
		{
			try
			{
				return $this->tryCalculateProperty(Enum\OrderProperty::LENGTH);
			}
			catch (\Exception $e)
			{
				return 0;
			}
		}
	}

	/**
	 * @return float
	 */
	public function getWidth()
	{
		try
		{
			return $this->tryProperty('total_width');
		}
		catch (\Exception $e)
		{
			try
			{
				return $this->tryCalculateProperty(Enum\OrderProperty::WIDTH);
			}
			catch (\Exception $e)
			{
				return 0;
			}
		}
	}

	/**
	 * @return float
	 */
	public function getPriceWithDiscounts()
	{
		try
		{
			return $this->tryProperty('total_price');
		}
		catch (\Exception $e)
		{
			return 0;
		}
	}

	/**
	 * @return float
	 */
	public function getPriceWithoutDiscounts()
	{
		try
		{
			return $this->tryProperty('total_raw_price');
		}
		catch (\Exception $e)
		{
			return 0;
		}
	}

	/**
	 * @param string $dimension_type
	 */
	public function setDimensionType($dimension_type)
	{
		$this->dimension_type = $dimension_type;
	}

	/**
	 * @return string
	 */
	public function getDimensionType()
	{
		return $this->dimension_type;
	}

	/**
	 * @param string $name
	 * @return float
	 * @throws \Exception
	 */
	private function tryProperty($name)
	{
		if (empty($this->params[$name]))
		{
			throw new \Exception("Поле \"{$name}\" не определено или не заполнено");
		}

		return (float)$this->params[$name];
	}

	/**
	 * @param string $name
	 * @return float
	 * @throws \Exception
	 */
	private function tryCalculateProperty($name)
	{
		if (!isset($this->package_calculator))
		{
			throw new \Exception("Невозможно посчитать поле \"{$name}\": калькулятор недоступен");
		}

		return (float)$this->package_calculator->calculate($name);
	}
}
