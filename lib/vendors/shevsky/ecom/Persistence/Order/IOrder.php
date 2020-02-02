<?php

namespace Shevsky\Ecom\Persistence\Order;

use Shevsky\Ecom\Util\IArrayConvertable;

interface IOrder extends IArrayConvertable
{
	/**
	 * @return float
	 */
	public function getWeight();

	/**
	 * @return float
	 */
	public function getHeight();

	/**
	 * @return float
	 */
	public function getLength();

	/**
	 * @return float
	 */
	public function getWidth();

	/**
	 * @return float
	 */
	public function getPriceWithDiscounts();

	/**
	 * @return float
	 */
	public function getPriceWithoutDiscounts();

	/**
	 * @return string
	 */
	public function getDimensionType();
}
