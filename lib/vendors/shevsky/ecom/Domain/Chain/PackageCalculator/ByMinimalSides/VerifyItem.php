<?php

namespace Shevsky\Ecom\Domain\Chain\PackageCalculator\ByMinimalSides;

trait VerifyItem
{
	/**
	 * @param array $item
	 * @throws \Exception
	 */
	protected function verifyItem($item)
	{
		if (!is_array($item))
		{
			throw new \Exception('Товар должен быть массивом');
		}

		if (!isset($item['width']) || !isset($item['height']) || !isset($item['length']))
		{
			throw new \Exception('В данных товара не обнаружены поля с габаритами');
		}
	}
}
