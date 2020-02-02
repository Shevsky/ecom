<?php

namespace Shevsky\Ecom\Util;

interface IMemento
{
	/**
	 * @return mixed
	 */
	public function memento();

	/**
	 * @param mixed $data
	 * @return static
	 */
	public static function restore($data);
}
