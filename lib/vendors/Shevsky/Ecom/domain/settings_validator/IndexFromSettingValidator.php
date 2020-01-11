<?php

namespace Shevsky\Ecom\Domain\SettingsValidator;

use Shevsky\Ecom\Persistence\SettingsValidator\ISettingValidator;

class IndexFromSettingValidator implements ISettingValidator
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
			throw new \Exception('Поле "Индекс места приема" должно быть заполнено');
		}

		if (!preg_match('/^\d{6}$/', $value))
		{
			throw new \Exception('Индекс должен состоять из 6 цифр');
		}

		return true;
	}
}
