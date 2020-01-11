<?php

namespace Shevsky\Ecom\Domain\SettingsValidator;

use Shevsky\Ecom\Persistence\SettingsValidator\ISettingValidator;

class ApiSettingValidator implements ISettingValidator
{
	/**
	 * @param mixed $value
	 * @return boolean
	 * @throws \Exception
	 */
	public function validate($value)
	{
		if (!$value)
		{
			throw new \Exception('Не указаны данные для авторизации API');
		}

		return true;
	}
}
