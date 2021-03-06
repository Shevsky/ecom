<?php

namespace Shevsky\Ecom\Plugin;

use LapayGroup\RussianPost\TariffInfo;
use Shevsky\Ecom\Domain\Departure\Departure;
use Shevsky\Ecom\Domain\Order\Order;
use Shevsky\Ecom\Domain\Point\Point;
use Shevsky\Ecom\Domain\PointStorage\PointStorage;
use Shevsky\Ecom\Domain\Services\DimensionTypeClassificator;
use Shevsky\Ecom\Enum;
use Shevsky\Ecom\Persistence\Point\IPointSchedule;
use Shevsky\Ecom\Provider;
use Shevsky\Ecom\Services\DeliveryIntervalHandbook\DeliveryIntervalHandbook;
use Shevsky\Ecom\Services\Tarifficator\ApiAdapter;
use Shevsky\Ecom\Services\Tarifficator\Tarifficator;
use Shevsky\Ecom\Services\Tarifficator\TarifficatorResult;
use Shevsky\Ecom\Util\DateTimeLocaleFormatter;
use Shevsky\Ecom\Util\ILogger;
use Shevsky\Ecom\Util\KeyValueCacheUtil;
use Shevsky\Ecom\Util\PointScheduleHelper;

/**
 * @mixin \ecomShipping
 */
trait Calculator
{
	private $logger;
	private $cache_util;
	private $delivery_interval;
	private $payment_list;

	/**
	 * @return array|string|null
	 */
	protected function calculate()
	{
		try
		{
			$this->verifyCountryCode();

			return $this->getTariffs();
		}
		catch (CalculatorException $e)
		{
			if ($e->isWarning())
			{
				return [
					'rate' => null,
					'comment' => $e->getMessage(),
				];
			}
			else if ($e->isError())
			{
				return $e->getMessage();
			}

			return null;
		}
	}

	/**
	 * @return ILogger
	 */
	private function getLogger()
	{
		if (!isset($this->logger))
		{
			$this->logger = new CalculatorLogger(
				\waSystemConfig::isDebug() && $this->is_debug && $this->is_debug_calculator
					? $this->calculator_debug_mode : Enum\DebugMode::DISABLED
			);
		}

		return $this->logger;
	}

	/**
	 * @throws CalculatorException
	 */
	private function verifyCountryCode()
	{
		if ($this->getCountryCode() !== Config::COUNTRY)
		{
			throw CalculatorException::error(CalculatorException::BAD_COUNTRY);
		}
	}

	/**
	 * @return Order
	 * @throws CalculatorException
	 */
	private function getOrder()
	{
		$height = $this->getTotalHeight();
		if (empty($height))
		{
			$height = (float)$this->default_height;
		}

		$length = $this->getTotalLength();
		if (empty($length))
		{
			$length = (float)$this->default_length;
		}

		$width = $this->getTotalWidth();
		if (empty($width))
		{
			$width = (float)$this->default_width;
		}

		$weight = $this->getTotalWeight();
		if (empty($weight))
		{
			$weight = (float)$this->default_weight;
		}

		$order = new Order(
			[
				'total_weight' => $weight,
				'total_height' => $height,
				'total_length' => $length,
				'total_width' => $width,
				'total_price' => $this->getTotalPrice(),
				'total_raw_price' => $this->getTotalRawPrice(),
				'items' => $this->getItems(),
			]
		);

		try
		{
			$dimension_type = DimensionTypeClassificator::buildWithOrder($order)->getDimensionType();
		}
		catch (\Exception $e)
		{
			if ($this->undefined_dimension_case === Enum\UndefinedDimensionCase::FIXED_DIMENSION_TYPE)
			{
				$dimension_type = $this->dimension_type;
			}
			else
			{
				throw CalculatorException::error(CalculatorException::UNDEFINED_DIMENSION_TYPE, $e);
			}
		}

		$order->setDimensionType($dimension_type);

		return $order;
	}

	/**
	 * @return Departure
	 */
	private function getDeparture()
	{
		$departure = new Departure(
			[
				'index_from' => $this->index_from,
				'index_to' => $this->getIndex(),
				'pass_goods_value' => $this->pass_goods_value,
				'total_value_mode' => $this->total_value_mode,
				'mail_category' => $this->mail_category,
				'mail_type' => $this->mail_type,
				'entries_type' => $this->entries_type,
				'payment_method' => $this->payment_method,
				'notice_payment_method' => $this->notice_payment_method,
				'sms_notice_recipient' => $this->sms_notice_recipient,
				'with_fitting' => $this->with_fitting,
				'functionality_checking' => $this->functionality_checking,
				'contents_checking' => $this->contents_checking,
				'completeness_checking' => $this->completeness_checking,
				'vsd' => $this->vsd,
				'fragile' => $this->fragile,
			]
		);

		return $departure;
	}

	/**
	 * @return array
	 * @throws CalculatorException
	 */
	private function getTariffs()
	{
		$start = microtime(true);
		$this->getLogger()->debug('Начинаем этап расчета тарифов');

		try
		{
			if ($this->is_calculate_thru_tariff)
			{
				$tarifficator = new Tarifficator(
					$this->getOrder(),
					$this->getDeparture(),
					ApiAdapter\TarifficatorTariffApiAdapter::class,
					$this->tariff_agreement_number
				);
			}
			else
			{
				try
				{
					$otpravka_api = Provider::getOtpravkaApi($this->api_login, $this->api_password, $this->api_token);
				}
				catch (\Exception $e)
				{
					$this->getLogger()->debug(
						'Не удалось получить экземпляр API сервиса Отправка',
						[
							'message' => $e->getMessage(),
							'code' => $e->getCode(),
						]
					);

					throw CalculatorException::error(CalculatorException::OTPRAVKA_API_ERROR, $e);
				}

				$memento_key_sault = 'calculator_otpravka_api_memento_key';
				$memento_key = md5($memento_key_sault . $this->api_login . $this->api_password . $this->api_token);

				$tarifficator = new Tarifficator(
					$this->getOrder(),
					$this->getDeparture(),
					ApiAdapter\TarifficatorOtpravkaApiAdapter::class,
					$otpravka_api,
					$memento_key
				);
			}
		}
		catch (\Exception $e)
		{
			$this->getLogger()->debug(
				'Не удалось получить экземпляр тарификатора',
				[
					'message' => $e->getMessage(),
					'code' => $e->getCode(),
				]
			);

			throw CalculatorException::error(CalculatorException::TARIFFICATOR_ERROR, $e);
		}

		$tarifficator->setDebugMode(
			\waSystemConfig::isDebug() && $this->is_debug && $this->is_debug_tarifficator
				? $this->tarifficator_debug_mode : Enum\DebugMode::DISABLED
		);

		$points_start = microtime(true);
		$points = (new PointStorage())
			->filterByCityName($this->getCityName())
			->filterByRegionCode($this->getRegionCode())
			->filterByCardPaymentAvailability((bool)$this->card_payment)
			->filterByCashPaymentAvailability((bool)$this->cash_payment)
			->filterByDeliveryPointTypeAvailability((bool)$this->delivery_point_type)
			->filterByPickupPointTypeAvailability((bool)$this->pickup_point_type)
			->receive();

		$event_data = [
			'id' => $this->id,
			'key' => $this->key,
			'points' => &$points,
		];
		wa()->event('ecom_shipping.calculator.points', $event_data);

		if (empty($points))
		{
			$this->getLogger()->debug(
				"Не найдено пунктов выдачи для адреса \"{$this->getRegionCode()} {$this->getCityName()}\" (оплата картой {$this->card_payment}, оплата наличными {$this->cash_payment}, ПВЗ {$this->delivery_point_type}, Почтоматы {$this->pickup_point_type})"
			);

			throw CalculatorException::error(CalculatorException::NO_POINTS);
		}
		else
		{
			$points_time = microtime(true) - $points_start;
			$points_count = count($points);

			$this->getLogger()->debug(
				"Получены пункты выдачи для адреса \"{$this->getRegionCode()} {$this->getCityName()}\" ({$points_count}) (оплата картой {$this->card_payment}, оплата наличными {$this->cash_payment}, ПВЗ {$this->delivery_point_type}, Почтоматы {$this->pickup_point_type})"
			);
			$this->getLogger()->debug(sprintf('Пункты выдачи получены за %.4F сек.', $points_time));
		}

		$event_data = [
			'id' => $this->id,
			'key' => $this->key,
			'address' => $this->getAddress(),
			'items' => $this->getItems(),
		];
		wa()->event('ecom_shipping.calculator.before_calculate', $event_data);

		$tariffs_start = microtime(true);
		$this->getLogger()->debug('Расчет тарифов запущен');

		$extra_coeff = $this->getExtraCoeff();

		$tariffs = [];
		switch ($this->calculation_mode)
		{
			case Enum\CalculationMode::EACH_POINT:
				foreach ($points as $point)
				{
					$tarifficator_result = $this->getTarifficatorResult($tarifficator, $point);
					if ($tarifficator_result)
					{
						$tariffs[$point->getIndex()] = $this->getTariff(
							$tarifficator_result->getInfo(),
							$point,
							$extra_coeff
						);
					}
				}
				break;
			case Enum\CalculationMode::FIRST_IN_CITY_POINT:
				reset($points);
				$point = current($points);
				$tarifficator_result = $this->getTarifficatorResult($tarifficator, $point);
				if ($tarifficator_result)
				{
					$tariff_info = $tarifficator_result->getInfo();
					$raw_rate = ($tariff_info->getTotalRate() + $tariff_info->getTotalNds()) / 100;
					$extra_charge = $this->getExtraCharge($raw_rate);

					foreach ($points as $point)
					{
						$tariffs[$point->getIndex()] = $this->getTariff(
							$tariff_info,
							$point,
							$extra_coeff,
							$extra_charge
						);
					}
				}
				break;
			case Enum\CalculationMode::GROUP_BY_NAME:
				$tariff_data_by_name = [];
				foreach ($points as $point)
				{
					if (!isset($tariff_data_by_name[$point->getName()]))
					{
						$tarifficator_result = $this->getTarifficatorResult(
							$tarifficator,
							$point
						);

						if ($tarifficator_result)
						{
							$tariff_info = $tarifficator_result->getInfo();
							$raw_rate = ($tariff_info->getTotalRate() + $tariff_info->getTotalNds()) / 100;
							$extra_charge = $this->getExtraCharge($raw_rate);

							$tariff_data_by_name[$point->getName()] = [
								$tariff_info,
								$extra_charge,
							];
						}
						else
						{
							$tariff_data_by_name[$point->getName()] = false;
						}
					}

					if ($tariff_data_by_name[$point->getName()])
					{
						list($tariff_info, $extra_charge) = $tariff_data_by_name[$point->getName()];
						$tariffs[$point->getIndex()] = $this->getTariff(
							$tariff_info,
							$point,
							$extra_coeff,
							$extra_charge
						);
					}
				}
				break;
		}

		$tariffs_time = microtime(true) - $tariffs_start;
		$this->getLogger()->debug(sprintf('Расчет тарифов завершен за %.4F сек.', $tariffs_time));

		$event_data = [
			'id' => $this->id,
			'key' => $this->key,
			'address' => $this->getAddress(),
			'items' => $this->getItems(),
			'tariffs' => &$tariffs,
		];
		wa()->event('ecom_shipping.calculator.after_calculate', $event_data);

		$time = microtime(true) - $start;
		$this->getLogger()->debug(sprintf('Этап расчета тарифов завершен за %.4F сек.', $time));

		return $tariffs;
	}

	/**
	 * @param Tarifficator $tarifficator
	 * @param Point $point
	 * @return TarifficatorResult|null
	 */
	private function getTarifficatorResult(Tarifficator $tarifficator, Point $point)
	{
		$tariff_result = null;

		if ($this->is_calculate_caching)
		{
			try
			{
				$tariff_result = $this->tryCachedTariffResult($tarifficator, $point);
			}
			catch (\Exception $e)
			{
			}
		}

		if (!$tariff_result)
		{
			try
			{
				$tariff_result = $tarifficator->calculate($point);
				if ($this->is_calculate_caching)
				{
					$this->cacheTariffResult($tarifficator, $tariff_result, $point);
				}
			}
			catch (\Exception $e)
			{
				$this->getLogger()->error(
					"Получено исключение от сервиса тарификации. Не удалось произвести расчет для пункта выдачи \"{$point->getId()}\""
				);
			}
		}

		return $tariff_result;
	}

	/**
	 * @param TariffInfo $tariff_info
	 * @param Point $point
	 * @param float|null $fixed_extra_coeff
	 * @param float|null $fixed_extra_charge
	 * @return array
	 */
	private function getTariff(
		TariffInfo $tariff_info,
		Point $point,
		$fixed_extra_coeff = null,
		$fixed_extra_charge = null
	)
	{
		$start = microtime(true);
		$this->getLogger()->debug('Начинаем обработку тарифа');

		try
		{
			$datetime_interval = $this->getDeliveryDateTimeInterval($tariff_info, $point->getSchedule());
		}
		catch (\Exception $e)
		{
			$datetime_interval = [];
		}

		$delivery_interval = array_map(
			[__CLASS__, 'formatDateTimeISO'],
			$datetime_interval
		);
		$est_delivery = DateTimeLocaleFormatter::formatInterval($datetime_interval);

		$raw_rate = ($tariff_info->getTotalRate() + $tariff_info->getTotalNds()) / 100;
		$rate = $raw_rate;

		if ($fixed_extra_coeff === null)
		{
			$extra_coeff = $this->getExtraCoeff();
		}
		else
		{
			$extra_coeff = $fixed_extra_coeff;
		}

		if ($fixed_extra_charge === null)
		{
			$extra_charge = $this->getExtraCharge($raw_rate);
		}
		else
		{
			$extra_charge = $fixed_extra_charge;
		}

		$rate = $rate + $extra_charge;
		$rate = $rate * $extra_coeff;

		$tariff = [
			'name' => $point->getLocation()->getAddress(),
			'description' => $point->getLocation()->getFullAddress(),
			'est_delivery' => $est_delivery,
			'delivery_date' => $delivery_interval,
			'timezone' => date_default_timezone_get(),
			'currency' => Config::CURRENCY,
			'rate' => $rate,
			'type' => \waShipping::TYPE_PICKUP,
			'service' => $point->getName(),

			'custom_data' => [
				\waShipping::TYPE_PICKUP => [
					'id' => $point->getIndex(),
					'lat' => (string)$point->getLocation()->getLatitude(),
					'lng' => (string)$point->getLocation()->getLongitude(),
					'name' => $point->getLocation()->getAddress(),
					'description' => $point->getLocation()->getFullAddress(),
					'way' => $point->getLocation()->getWay(),
					'schedule' => $this->getScheduleWithDateTimeInterval(
						$point->getSchedule(),
						$datetime_interval
					),
					'payment' => $this->getPaymentList(),
				],
			],
		];

		$time = microtime(true) - $start;
		$this->getLogger()->details(
			sprintf(
				'Обработан тариф (исходная %s, наценка %s, коэффициент %s)',
				$raw_rate,
				$extra_charge,
				$extra_coeff
			),
			$tariff
		);
		$this->getLogger()->debug(sprintf('Тариф обработан за %.4F сек.', $time));

		return $tariff;
	}

	/**
	 * @return float
	 */
	private function getExtraCoeff()
	{
		$extra_coeff = (float)$this->extra_coeff;
		if (!$extra_coeff)
		{
			$extra_coeff = 1;
		}

		return $extra_coeff;
	}

	/**
	 * @param float $rate
	 * @return float
	 */
	private function getExtraCharge($rate)
	{
		$extra_charge = 0;
		if ($this->extra_charge)
		{
			try
			{
				$view = wa()->getView();
				$view->assign('order', 1000);
				$view->assign('shipping', $rate);

				$extra_charge = (float)@$view->fetch('string:' . $this->extra_charge);
			}
			catch (\Exception $e)
			{
				$this->getLogger()->error(
					"Получено исключение при попытке применить наценку: " . $e->getMessage()
				);
			}
		}

		return $extra_charge;
	}

	/**
	 * @return KeyValueCacheUtil
	 */
	private function getCacheUtil()
	{
		if (!isset($this->cache_util))
		{
			$this->cache_util = new KeyValueCacheUtil('calculator');
		}

		return $this->cache_util;
	}

	/**
	 * @param Tarifficator $tarifficator
	 * @param TarifficatorResult $tariff_result
	 * @param Point $point
	 */
	private function cacheTariffResult(Tarifficator $tarifficator, TarifficatorResult $tariff_result, Point $point)
	{
		$this->getCacheUtil()->setCache(
			"{$tarifficator->getMementoKey()}.cache",
			$point->getIndex(),
			$tariff_result->memento()
		);
	}

	/**
	 * @param Tarifficator $tarifficator
	 * @param Point $point
	 * @return TarifficatorResult
	 * @throws \Exception
	 */
	private function tryCachedTariffResult(Tarifficator $tarifficator, Point $point)
	{
		$raw_tariff_result = $this->getCacheUtil()->getCache(
			"{$tarifficator->getMementoKey()}.cache",
			$point->getIndex()
		);

		return TarifficatorResult::restore($raw_tariff_result);
	}

	/**
	 * @throws \Exception
	 */
	private function getDeliveryInterval()
	{
		if (!isset($this->delivery_interval))
		{
			$this->delivery_interval = (new DeliveryIntervalHandbook())->getInterval(
				$this->region_code_from,
				$this->city_name_from,
				$this->getRegionCode(),
				$this->getCityName()
			);
		}

		return $this->delivery_interval;
	}

	/**
	 * @param TariffInfo $tariff
	 * @param IPointSchedule $schedule
	 * @return \DateTime[]
	 * @throws \Exception
	 */
	private function getDeliveryDateTimeInterval(TariffInfo $tariff, IPointSchedule $schedule)
	{
		$interval = [];

		if ($tariff->getDeliveryMinDays())
		{
			$interval[] = $tariff->getDeliveryMinDays();
		}
		if ($tariff->getDeliveryMaxDays())
		{
			$interval[] = $tariff->getDeliveryMaxDays();
		}

		if (empty($interval))
		{
			$interval = $this->getDeliveryInterval();
		}

		$datetime = new \DateTime($this->getPackageProperty('departure_datetime'));

		$datetime_interval = [];

		if (!empty($interval[0]))
		{
			$from_datetime = clone $datetime;
			$from_datetime->modify("+{$interval[0]} day");

			$this->modifyDateTimeWithSchedule($from_datetime, $schedule);

			$datetime_interval[] = $from_datetime;
		}

		if (!empty($interval[1]))
		{
			$to_datetime = clone $datetime;
			$to_datetime->modify("+{$interval[1]} day");

			$this->modifyDateTimeWithSchedule($to_datetime, $schedule);

			$datetime_interval[] = $to_datetime;
		}

		return $datetime_interval;
	}

	/**
	 * @param \DateTime $datetime
	 * @param IPointSchedule $schedule
	 */
	private function modifyDateTimeWithSchedule(\DateTime $datetime, IPointSchedule $schedule)
	{
		$array_schedule = $schedule->toArray();

		for ($i = 0; $i <= 6; $i++)
		{
			if ($i)
			{
				$datetime->modify("+{$i} days");
			}

			$day = (int)$datetime->format('w');

			if ($array_schedule[$day]->isWorking())
			{
				break;
			}
		}
	}

	/**
	 * @param \DateTime $datetime
	 * @return string
	 */
	private function formatDateTimeISO(\DateTime $datetime)
	{
		return $datetime->format('Y-m-d H:i:s');
	}

	/**
	 * @param IPointSchedule $schedule
	 * @param \DateTime[] $datetime_interval
	 * @return array
	 */
	private function getScheduleWithDateTimeInterval(IPointSchedule $schedule, array $datetime_interval)
	{
		if (empty($datetime_interval))
		{
			return [];
		}

		$datetime = clone $datetime_interval[0];
		$array_schedule = $schedule->toArray();
		$working_days_count = PointScheduleHelper::getWorkingDaysCount($schedule);

		if (empty($working_days_count))
		{
			return [];
		}

		$weekdays = [];

		$datetime->modify("-1 days");
		while (count($weekdays) < 7)
		{
			$datetime->modify("+1 days");
			$day = (int)$datetime->format('w');
			$schedule_daily = $array_schedule[$day];
			if (!$schedule_daily->isWorking())
			{
				continue;
			}

			$weekdays[] = [
				'type' => 'workday',
				'start_work' => $datetime->format('Y-m-d') . ' ' . $schedule_daily->getHourFrom() . ':'
					. $schedule_daily->getMinutesFrom(),
				'end_work' => $datetime->format('Y-m-d') . ' ' . $schedule_daily->getHourTo() . ':'
					. $schedule_daily->getMinutesTo(),
			];
		}

		return [
			'weekdays' => $weekdays,
		];
	}

	/**
	 * @return string[]
	 */
	private function getPaymentList()
	{
		if (!isset($this->payment_list))
		{
			$this->payment_list = [];

			if (!empty($this->card_payment))
			{
				$this->payment_list[\waShipping::PAYMENT_TYPE_CARD] = true;
			}

			if (!empty($this->cash_payment))
			{
				$this->payment_list[\waShipping::PAYMENT_TYPE_CASH] = true;
			}

			if (!empty($this->pre_payment))
			{
				$this->payment_list[\waShipping::PAYMENT_TYPE_PREPAID] = true;
			}
		}

		return $this->payment_list;
	}
}
