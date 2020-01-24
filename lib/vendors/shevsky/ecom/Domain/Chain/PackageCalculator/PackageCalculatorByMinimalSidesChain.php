<?php

namespace Shevsky\Ecom\Domain\Chain\PackageCalculator;

use Shevsky\Ecom\Domain\Chain\PackageCalculator\ByMinimalSides;
use Shevsky\Ecom\Persistence\Chain\AbstractChain;
use Shevsky\Ecom\Persistence\Chain\IResponsibility;

class PackageCalculatorByMinimalSidesChain extends AbstractChain
{
	/**
	 * @return IResponsibility[]
	 */
	protected function getResponsibilities()
	{
		return [
			new ByMinimalSides\CorrectItemsResponsibility(),
			new ByMinimalSides\SortItemsResponsibility(),
			new ByMinimalSides\SumPackageResponsibility(),
		];
	}
}
