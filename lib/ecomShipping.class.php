<?php

if (!trait_exists('\Shevsky\Ecom\Plugin\Address'))
{
	require_once 'vendors/Shevsky/Ecom/plugin/Address.php';
}


if (!trait_exists('\Shevsky\Ecom\Plugin\Calculator'))
{
	require_once 'vendors/Shevsky/Ecom/plugin/Calculator.php';
}

if (!trait_exists('\Shevsky\Ecom\Plugin\ShopScript'))
{
	require_once 'vendors/Shevsky/Ecom/plugin/ShopScript.php';
}

use Shevsky\Ecom\Chain\SyncPoints\SyncPointsChain;
use Shevsky\Ecom\Domain\PointStorage\PointStorage;
use Shevsky\Ecom\Domain\Services\SettingsValidator\SettingsValidator;
use Shevsky\Ecom\Domain\Template\SettingsTemplate;
use Shevsky\Ecom\Domain\Template\TrackingSimpleTemplate;
use Shevsky\Ecom\Domain\Template\TrackingTemplate;
use Shevsky\Ecom\Plugin;
use Shevsky\Ecom\Provider;

class ecomShipping extends waShipping
{
	use Plugin\Address;
	use Plugin\Calculator;
	use Plugin\ShopScript;

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
		return Plugin\Config::CURRENCY;
	}

	/**
	 *
	 * @return string
	 */
	public function allowedWeightUnit()
	{
		return Plugin\Config::WEIGHT_UNIT;
	}

	/**
	 * @return string
	 */
	public function allowedLinearUnit()
	{
		return Plugin\Config::LINEAR_UNIT;
	}

	/**
	 * @return array
	 */
	public function allowedAddress()
	{
		return [
			[
				'country' => Plugin\Config::COUNTRY,
			],
		];
	}

	/**
	 * @param string|null $tracking_id
	 * @return string
	 */
	public function tracking($tracking_id = null)
	{
		try
		{
			$tracking = Provider::getTracking($this->tracking_login, $this->tracking_password);
			$template = new TrackingTemplate($tracking_id, $tracking, $this->tracking_cache_lifetime);
		}
		catch (\Exception $e)
		{
			$template = new TrackingSimpleTemplate();
			$template->assign(
				[
					'error' => $e->getMessage(),
				]
			);
		}

		$template->assign(
			[
				'tracking_id' => $tracking_id,
			]
		);

		return $template->render();
	}

	/**
	 * @param array $settings
	 * @return array
	 * @throws waException
	 */
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
					throw new waException($e->getMessage(), $e->getCode(), $e);
				}
			}
		}

		try
		{
			$settings_validator->validate();
		}
		catch (\Exception $e)
		{
			throw new waException($e->getMessage(), $e->getCode(), $e);
		}

		return parent::saveSettings($settings);
	}

	/**
	 * @param array $params
	 * @return string
	 */
	public function getSettingsHTML($params = [])
	{
		$template = new SettingsTemplate();

		try
		{
			$get_regions_url = wa()->getAppUrl('webasyst') . '?module=backend&action=regions';
		}
		catch (waException $e)
		{
			$get_regions_url = '';
		}

		try
		{
			$countries = array_values((new waCountryModel())->allWithFav());
		}
		catch (waException $e)
		{
			$countries = [];
		}

		$settings = $this->getSettings();

		$template->assign(
			[
				'params' => [
					'id' => $this->id,
					'key' => (string)$this->key === $this->id ? null : (string)$this->key,
					'get_agreement_number_url' => $this->getInteractionUrl('getAgreementNumber', 'backend'),
					'sync_points_url' => $this->getInteractionUrl('syncPoints', 'backend'),
					'get_regions_url' => $get_regions_url,
					'points_handbook_count' => (new PointStorage())->count(),
					'countries' => $countries,
					'settings' => $settings,
				],
			]
		);

		$output = $template->render();

		if (method_exists($this, 'getNoticeHtml'))
		{
			try
			{
				$output = $this->getNoticeHtml($params) . $output;
			}
			catch (\waException $e)
			{
			}
		}

		return $output;
	}

	/**
	 * @return bool|null
	 */
	protected function sync()
	{
		try
		{
			(new SyncPointsChain(
				Provider::getOtpravkaApi($this->login, $this->password, $this->token)
			))->execute(); // TODO протестировать правильность работы

			return true;
		}
		catch (\Exception $e)
		{
			return false;
		}
	}

	protected function init()
	{
		require_once __DIR__ . '/vendors/autoload.php';

		parent::init();
	}
}
