<?php

namespace Shevsky\Ecom\Domain\Chain\PackageCalculator\ByMinimalSides;

use Shevsky\Ecom\Persistence\Chain\IResponsibility;

class SortItemsResponsibility implements IResponsibility
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

		usort(
			$items,
			[__CLASS__, 'itemSorter']
		);

		return [$items];
	}

	/**
	 * @param mixed $a
	 * @param mixed $b
	 * @return int
	 * @throws \Exception
	 */
	private function itemSorter($a, $b)
	{
		$this->verifyItem($a);
		$this->verifyItem($b);

		$a_volume = $a['width'] * $a['height'] * $a['length'];
		$b_volume = $b['width'] * $b['height'] * $b['length'];

		if ($a_volume === $b_volume)
		{
			return 0;
		}

		return $a_volume < $b_volume ? 1 : 0;
	}
}
