<?php

namespace Shevsky\Ecom\Domain\Services\SettingsValidator;

use Shevsky\Ecom\Persistence\Services\SettingsValidator\ISettingValidator;

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
