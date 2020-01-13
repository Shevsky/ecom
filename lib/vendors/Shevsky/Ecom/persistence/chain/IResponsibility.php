<?php

namespace Shevsky\Ecom\Persistence\Chain;

interface IResponsibility
{
	/**
	 * @param mixed $args
	 * @return mixed[]
	 * @throws \Exception
	 */
	public function execute(array ...$args);
}
