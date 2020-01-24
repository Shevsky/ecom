<?php

namespace Shevsky\Ecom\Domain\Chain\PackageCalculator\ByMinimalSides;

use Shevsky\Ecom\Enum;
use Shevsky\Ecom\Persistence\Chain\IResponsibility;

class SumPackageResponsibility implements IResponsibility
{
	use GetItems;
	use VerifyItem;

	/**
	 * @param mixed $args
	 * @return mixed[]
	 * @throws \Exception
	 */
	public function execute(array ...$args)
	{
		$items = $this->getItems(...$args);

		list($height, $length, $width) = array_reduce($items, [__CLASS__, 'itemReducer'], [0, 0, 0]);

		return [
			Enum\OrderProperty::HEIGHT => (float)$height,
			Enum\OrderProperty::LENGTH => (float)$length,
			Enum\OrderProperty::WIDTH => (float)$width,
		];
	}

	/**
	 * @param array $carry
	 * @param array $item
	 * @return array
	 * @throws \Exception
	 */
	private function itemReducer($carry, $item)
	{
		$this->verifyItem($item);

		$quantity = !isset($item['quantity']) ? 0 : (int)$item['quantity'];

		$_carry = $carry;

		for ($i = 0; $i < $quantity; $i++)
		{
			sort($_carry, SORT_NUMERIC);
			$dims = [$item['width'], $item['length'], $item['height']];
			sort($dims, SORT_NUMERIC);
			$_carry = [$_carry[0] + $dims[0], max($_carry[1], $dims[1]), max($_carry[2], $dims[2])];
		}

		return $_carry;
	}
}
