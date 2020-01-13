<?php

namespace Shevsky\Ecom\Domain\SettingsValidator;

use Shevsky\Ecom\Persistence\SettingsValidator\ISettingValidator;

class TrackingCacheLifetimeSettingValidator implements ISettingValidator
{

	/**
	 * @param mixed $value
	 * @return boolean
	 * @throws \Exception
	 */
	public function validate($value)
	{
		if (!preg_match('/^\d+$/', $value))
		{
			throw new \Exception('Время жизни кеша для трекинга должно быть целочисленным числом');
		}

		return true;
	}
}
