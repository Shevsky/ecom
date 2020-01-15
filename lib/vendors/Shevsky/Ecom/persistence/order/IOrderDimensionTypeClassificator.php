<?php

namespace Shevsky\Ecom\Persistence\Order;

interface IOrderDimensionTypeClassificator
{
	/**
	 * @return string
	 * @throws \Exception
	 */
	public function getDimensionType();
}
