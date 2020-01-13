<?php

namespace Shevsky\Ecom\Persistence\Chain;

abstract class AbstractChain
{
	/**
	 * @return IResponsibility[]
	 */
	abstract protected function getResponsibilities();

	/**
	 * @return mixed[]
	 * @throws \Exception
	 */
	public function execute()
	{
		$responsibilities = $this->getResponsibilities();

		$last_result = [];

		foreach ($responsibilities as $responsibility)
		{
			$last_result = $responsibility->execute(...$last_result);
		}

		return $last_result;
	}
}
