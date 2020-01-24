<?php

namespace Shevsky\Ecom\Domain\Services\SettingsValidator;

use Shevsky\Ecom\Persistence\Services\SettingsValidator\ISettingValidator;

class IndexFromSettingValidator implements ISettingValidator
{
	private $settings;

	/**
	 * @param mixed $settings
	 */
	public function __construct($settings)
	{
		$this->settings = $settings;
	}

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
			throw new \Exception('Индекс места приема должен состоять из 6 цифр');
		}

		return true;
	}
}
