<?php

namespace Shevsky\Ecom\Persistence\Services;

interface IDimensionTypeClassificator
{
	/**
	 * @return string
	 * @throws \Exception
	 */
	public function getDimensionType();
}
