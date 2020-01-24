<?php

namespace Shevsky\Ecom\Persistence\Chain;

abstract class AbstractChain
{
	/**
	 * @return IResponsibility[]
	 */
	abstract protected function getResponsibilities();

	/**
	 * @param array[] $args
	 * @return mixed[]
	 * @throws \Exception
	 */
	public function execute(array ...$args)
	{
		$responsibilities = $this->getResponsibilities();

		$last_result = $args;

		foreach ($responsibilities as $responsibility)
		{
			$last_result = $responsibility->execute(...$last_result);
		}

		return $last_result;
	}
}
