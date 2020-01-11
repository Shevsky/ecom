<?php

namespace Shevsky\Ecom\Domain\SettingsValidator;

use Shevsky\Ecom\Persistence\SettingsValidator\ISettingsValidator;
use Shevsky\Ecom\Persistence\SettingsValidator\ISettingValidator;

class SettingsValidator implements ISettingsValidator
{
	private $validators = [];

	/**
	 * @param array $settings
	 */
	public function __construct($settings)
	{
		$api_validator = new ApiSettingValidator();

		$this->validators = [
			'api_login' => $api_validator,
			'api_password' => $api_validator,
			'api_token' => $api_validator,
			'index_from' => new IndexFromSettingValidator(),
		];
	}

	/**
	 * @param string $name
	 * @return ISettingValidator|null
	 */
	public function getValidator($name)
	{
		if (!array_key_exists($name, $this->validators)
			|| (!$this->validators[$name] instanceof ISettingValidator))
		{
			return null;
		}

		return $this->validators[$name];
	}
}
