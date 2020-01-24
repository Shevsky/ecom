<?php

namespace Shevsky\Ecom\Domain\Chain\PackageCalculator\ByMinimalSides;

trait GetItems
{
	/**
	 * @param mixed $args
	 * @return mixed[]
	 * @throws \Exception
	 */
	protected function getItems(array ...$args)
	{
		if (!array_key_exists(0, $args))
		{
			throw new \Exception('Не найден список с товарами');
		}

		list($items) = $args;
		if (empty($items))
		{
			throw new \Exception('Массив с товарами пуст');
		}

		return $items;
	}
}
