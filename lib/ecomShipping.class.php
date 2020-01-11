<?php

use Shevsky\Ecom\Autoloader;
use Shevsky\Ecom\Domain\SettingsValidator\SettingsValidator;
use Shevsky\Ecom\Domain\Template\SettingsTemplate;
use Shevsky\Ecom\Domain\Template\TrackingTemplate;
use Shevsky\Ecom\Plugin;

class ecomShipping extends waShipping
{
	/**
	 * @return string
	 */
	public static function getPublicPath()
	{
		try
		{
			return wa()->getRootUrl(true) . 'wa-plugins/shipping/ecom';
		}
		catch (waException $e)
		{
			return '';
		}
	}

	/**
	 *
	 * @return string
	 */
	public function allowedCurrency()
	{
		return Plugin::CURRENCY;
	}

	/**
	 *
	 * @return string
	 */
	public function allowedWeightUnit()
	{
		return Plugin::WEIGHT_UNIT;
	}

	/**
	 * @return string
	 */
	public function allowedLinearUnit()
	{
		return Plugin::LINEAR_UNIT;
	}

	/**
	 * @return array
	 */
	public function allowedAddress()
	{
		return [
			[
				'country' => Plugin::COUNTRY,
			],
		];
	}

	/**
	 * @param string|null $tracking_id
	 * @return string
	 */
	public function tracking($tracking_id = null)
	{
		return (new TrackingTemplate($tracking_id))->render();
	}

	public function saveSettings($settings = [])
	{
		$settings_validator = new SettingsValidator($settings);

		foreach ($settings as $name => $value)
		{
			if ($setting_validator = $settings_validator->getValidator($name))
			{
				try
				{
					$setting_validator->validate($value);
				}
				catch (Exception $e)
				{
					throw new waException($e->getMessage());
				}
			}
		}

		parent::saveSettings($settings);
	}

	/**
	 * @param array $params
	 * @return string
	 */
	public function getSettingsHTML($params = [])
	{
		$template = new SettingsTemplate();

		$settings = $this->getSettings();

		$template->assign(
			[
				'params' => [
					'id' => $this->id,
					'key' => (string)$this->key === $this->id ? null : (string)$this->key,
					'settings' => $settings,
				],
			]
		);

		return $template->render();
	}

	/**
	 *
	 */
	protected function calculate()
	{
		// TODO: Implement calculate() method.
	}

	protected function init()
	{
		self::registerAutoloader();

		parent::init();
	}

	private static function registerAutoloader()
	{
		require_once __DIR__ . '/vendors/Shevsky/Ecom/Autoloader.php';
		Autoloader::register();
	}
}
