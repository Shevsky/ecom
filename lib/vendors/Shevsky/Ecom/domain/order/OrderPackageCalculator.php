<?php

namespace Shevsky\Ecom\Domain\Order;

use Shevsky\Ecom\Domain\Chain\PackageCalculator\PackageCalculatorByMinimalSidesChain;

class OrderPackageCalculator
{
	private $items;

	private $package;

	/**
	 * @param array $items = [
	 *  $index => [
	 *      'id' => string,
	 *      'sku' => string,
	 *      'name' => string,
	 *      'weight' => float,
	 *      'price' => float,
	 *      'quantity' => int,
	 *      'height' => float,
	 *      'length' => float,
	 *      'width' => float,
	 *  ]
	 * ]
	 */
	public function __construct(array $items)
	{
		$this->items = $items;
	}

	/**
	 * @param string $name
	 * @return float
	 * @throws \Exception
	 */
	public function calculate($name)
	{
		if (!isset($this->package))
		{
			$this->validate();

			$this->package = $this->calculatePackage();
		}

		if (!isset($this->package[$name]))
		{
			throw new \Exception("Не удалось определить параметр заказа \"{$name}\"");
		}

		return (float)$this->package[$name];
	}

	/**
	 * @throws \Exception
	 */
	private function validate()
	{
		if (empty($this->items))
		{
			throw new \Exception('Массив товаров заказа пустой');
		}
	}

	/**
	 * @return array = [
	 *  Enum\OrderProperty::HEIGHT => float,
	 *  Enum\OrderProperty::LENGTH => float,
	 *  Enum\OrderProperty::WIDTH => float,
	 * ]
	 * @throws \Exception
	 */
	private function calculatePackage()
	{
		return (new PackageCalculatorByMinimalSidesChain())->execute($this->items);
	}
}
