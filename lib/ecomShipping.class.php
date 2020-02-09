<?php

if (!trait_exists('\Shevsky\Ecom\Plugin\Address'))
{
	require_once 'vendors/shevsky/ecom/Plugin/Address.php';
}


if (!trait_exists('\Shevsky\Ecom\Plugin\Calculator'))
{
	require_once 'vendors/shevsky/ecom/Plugin/Calculator.php';
}

if (!trait_exists('\Shevsky\Ecom\Plugin\ShopScript'))
{
	require_once 'vendors/shevsky/ecom/Plugin/ShopScript.php';
}

use Shevsky\Ecom\Chain\SyncPoints\SyncPointsChain;
use Shevsky\Ecom\Domain\PointStorage\PointStorage;
use Shevsky\Ecom\Domain\Services\SettingsValidator\SettingsValidator;
use Shevsky\Ecom\Domain\Template\SettingsTemplate;
use Shevsky\Ecom\Domain\Template\TrackingSimpleTemplate;
use Shevsky\Ecom\Domain\Template\TrackingTemplate;
use Shevsky\Ecom\Plugin;
use Shevsky\Ecom\Provider;
use Shevsky\Ecom\Services\Tracking\TrackingMaintenance;

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
			$tracking_maintenance = new TrackingMaintenance($tracking, $this->tracking_cache_lifetime);

			$template = new TrackingTemplate($tracking_id, $tracking_maintenance);
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

		$is_auto_sync_available = false;
		try
		{
			$model = new waAppSettingsModel();
			$time = $model->get('shop', 'shipping_plugins_sync');

			if (!empty($time))
			{
				$is_auto_sync_available = true;
			}
		}
		catch (\Exception $e)
		{
		}

		try
		{
			if (method_exists($this, 'getGeneralSettings'))
			{
				$sync_data = [
					'time' => (int)$this->getGeneralSettings('sync_time'),
					'success_time' => (int)$this->getGeneralSettings('sync_success_time'),
					'failure_time' => (int)$this->getGeneralSettings('sync_failure_time'),
				];
			}
			else
			{
				throw new \Exception();
			}
		}
		catch (\Exception $e)
		{
			$sync_data = [
				'time' => 0,
				'success_time' => 0,
				'failure_time' => 0,
			];
		}

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
					'is_auto_sync_available' => $is_auto_sync_available,
					'sync_data' => $sync_data,
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
				Provider::getOtpravkaApi($this->api_login, $this->api_password, $this->api_token)
			))->execute();

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
