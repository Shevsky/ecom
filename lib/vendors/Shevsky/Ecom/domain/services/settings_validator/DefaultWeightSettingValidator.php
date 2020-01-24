<?php

namespace Shevsky\Ecom\Domain\Services\SettingsValidator;

use Shevsky\Ecom\Persistence\Services\SettingsValidator\ISettingValidator;

class DefaultWeightSettingValidator implements ISettingValidator
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
			throw new \Exception('Поле "Вес по умолчанию" должно быть заполнено');
		}

		if (!is_numeric($value))
		{
			throw new \Exception('Вес по умолчанию должен быть числом');
		}

		$value = (float)$value;
		if ($value < 0.01 || $value > 15)
		{
			throw new \Exception('Вес должен быть от 0.01 до 15 кг');
		}

		return true;
	}
}
