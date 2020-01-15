<?php

use Shevsky\Ecom\Api\Otpravka\OtpravkaApi;
use Shevsky\Ecom\Autoloader;
use Shevsky\Ecom\Chain\SyncPoints\SyncPointsChain;
use Shevsky\Ecom\Enum;
use Shevsky\Ecom\Domain\Order\Order;
use Shevsky\Ecom\Domain\Order\OrderDimensionTypeClassificator;
use Shevsky\Ecom\Domain\PointStorage\PointStorage;
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
					'sync_points_url' => $this->getInteractionUrl('syncPoints', 'backend'),
					'points_handbook_count' => (new PointStorage())->count(),
					'settings' => $settings,
				],
			]
		);

		$output = $template->render();

		if (method_exists($this, 'getNoticeHtml'))
		{
			$output = $this->getNoticeHtml($params) . $output;
		}

		return $output;
	}

	/**
	 * @return array|string
	 */
	protected function calculate()
	{
		$order = new Order(
			[
				'total_weight' => $this->getTotalWeight(),
				'total_height' => $this->getTotalHeight(),
				'total_length' => $this->getTotalLength(),
				'total_width' => $this->getTotalWidth(),
				'total_price' => $this->getTotalPrice(),
				'total_raw_price' => $this->getTotalRawPrice(),
				'items' => $this->getItems(),
			]
		);

		try
		{
			$dimension_type = (new OrderDimensionTypeClassificator($order))->getDimensionType();
		}
		catch (\Exception $e)
		{
			if ($this->undefined_dimension_case === Enum\UndefinedDimensionCase::FIXED_DIMENSION_TYPE)
			{
				$dimension_type = $this->dimension_type;
			}
			else
			{
				return 'undefined dimension case, forbidden';
			}
		}
	}

	/**
	 * @return bool|null
	 */
	protected function sync()
	{
		try
		{
			$otpravka_api = $this->getOtpravkaApi();

			(new SyncPointsChain($otpravka_api))->execute();

			return true;
		}
		catch (\Exception $e)
		{
			return false;
		}
	}

	protected function init()
	{
		self::registerAutoloader();

		parent::init();
	}

	/**
	 * @return OtpravkaApi
	 * @throws \Exception
	 */
	protected function getOtpravkaApi()
	{
		if (!$this->hasOtpravkaApiParams())
		{
			throw new \Exception('Параметры для API сервиса Отправка не указаны');
		}

		return new OtpravkaApi($this->api_login, $this->api_password, $this->api_token);
	}

	/**
	 * @return bool
	 */
	private function hasOtpravkaApiParams()
	{
		return !!$this->api_login && !!$this->api_password && !!$this->api_token;
	}

	/**
	 * @return bool
	 */
	private function verifyTrackingApi()
	{
		return !!$this->tracking_login && !!$this->tracking_password;
	}

	private static function registerAutoloader()
	{
		require_once __DIR__ . '/vendors/Shevsky/Ecom/Autoloader.php';
		Autoloader::register();
	}
}
