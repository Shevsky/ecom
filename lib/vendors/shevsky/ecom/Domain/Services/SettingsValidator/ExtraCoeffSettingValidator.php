<?php

namespace Shevsky\Ecom\Domain\Services\SettingsValidator;

use Shevsky\Ecom\Persistence\Services\SettingsValidator\ISettingValidator;

class ExtraCoeffSettingValidator implements ISettingValidator
{

	/**
	 * @param mixed $value
	 * @return boolean
	 * @throws \Exception
	 */
	public function validate($value)
	{
		if ($value && !preg_match('/^[0-9\.]+$/', $value))
		{
			throw new \Exception(
				'Поле "Дополнительный коэффициент к стоимости доставки" должно быть числом'
			);
		}

		return true;
	}
}
