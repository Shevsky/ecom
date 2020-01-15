<?php

namespace Shevsky\Ecom\Domain\Chain\PackageCalculator\ByMinimalSides;

use Shevsky\Ecom\Persistence\Chain\IResponsibility;

class CorrectItemsResponsibility implements IResponsibility
{
	use GetItems;

	/**
	 * @param mixed $args
	 * @return mixed[]
	 * @throws \Exception
	 */
	public function execute(array ...$args)
	{
		$items = $this->getItems(...$args);

		return [$items];
	}
}
