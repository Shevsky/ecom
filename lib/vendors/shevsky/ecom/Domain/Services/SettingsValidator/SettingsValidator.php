<?php

namespace Shevsky\Ecom\Domain\Services\SettingsValidator;

use Shevsky\Ecom\Persistence\Services\SettingsValidator\ISettingsValidator;
use Shevsky\Ecom\Persistence\Services\SettingsValidator\ISettingValidator;

class SettingsValidator implements ISettingsValidator
{
	private $settings;
	private $validators = [];

	/**
	 * @param array $settings
	 */
	public function __construct($settings)
	{
		$this->settings = $settings;

		$api_validator = new ApiSettingValidator();
		$this->validators = [
			'api_login' => $api_validator,
			'api_password' => $api_validator,
			'api_token' => $api_validator,
			'tracking_cache_lifetime' => new TrackingCacheLifetimeSettingValidator(),
			'index_from' => new IndexFromSettingValidator($settings),
			'region_code_from' => new RegionCodeFromSettingValidator(),
			'city_name_from' => new CityNameFromSettingValidator(),
			'default_weight' => new DefaultWeightSettingValidator(),
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

	/**
	 * @throws \Exception
	 */
	public function validate()
	{
		(new DefaultDimensionSettingsValidator())->validate($this->settings);
		(new OtpravkaApiSyncSettingsValidator())->validate($this->settings);
	}
}
