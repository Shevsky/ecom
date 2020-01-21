<?php

namespace Shevsky\Ecom\Plugin;

use Shevsky\Ecom\Domain\Departure\Departure;
use Shevsky\Ecom\Domain\Order\Order;
use Shevsky\Ecom\Domain\Order\OrderDimensionTypeClassificator;
use Shevsky\Ecom\Domain\Point\Point;
use Shevsky\Ecom\Domain\PointStorage\PointStorage;
use Shevsky\Ecom\Enum;
use Shevsky\Ecom\Persistence\Point\IPointSchedule;
use Shevsky\Ecom\Services\DeliveryIntervalHandbook\DeliveryIntervalHandbook;
use Shevsky\Ecom\Services\Tarifficator\Tarifficator;
use Shevsky\Ecom\Util\DateTimeLocaleFormatter;
use Shevsky\Ecom\Util\PointScheduleHelper;

/**
 * @mixin \ecomShipping
 */
trait Calculator
{
	private $delivery_interval;

	/**
	 * @return array|string|null
	 */
	protected function calculate()
	{
		try
		{
			$this->verifyCountryCode();
			$this->verifyIndex();

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
	 * @deprecated Верификация по индексу не нужна, если его нет - используем индекс пункта выдачи
	 * @throws CalculatorException
	 */
	private function verifyIndex()
	{
		return;

		if (!$this->getIndex() && !$this->isShopScript8Checkout())
		{
			throw CalculatorException::warning(CalculatorException::NO_INDEX);
		}
	}

	/**
	 * @return Order
	 * @throws CalculatorException
	 */
	private function getOrder()
	{
		$weight = $this->getTotalWeight();
		if (empty($weight))
		{
			$weight = (float)$this->weight;
		}

		$order = new Order(
			[
				'total_weight' => $weight,
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
				'payment_method' => $this->payment_method,
				'sms_notice_recipient' => $this->sms_notice_recipient,
				'with_fitting' => $this->with_fitting,
				'functionality_checking' => $this->functionality_checking,
				'contents_checking' => $this->contents_checking,
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
		CalculatorLogger::debug('Начинаем расчет тарифов');

		try
		{
			$otpravka_api = $this->getOtpravkaApi();
		}
		catch (\Exception $e)
		{
			CalculatorLogger::debug(
				'Не удалось получить экземпляр API сервиса Отправка',
				[
					'message' => $e->getMessage(),
					'code' => $e->getCode(),
				]
			);

			throw CalculatorException::error(CalculatorException::OTPRAVKA_API_ERROR, $e);
		}

		$tarifficator = new Tarifficator(
			$this->getOrder(),
			$this->getDeparture(),
			$otpravka_api
		);

		/**
		 * @var Point[] $points
		 */
		$points = (new PointStorage())
			->filterByCityName($this->getCityName())
			->filterByRegionCode($this->getRegionCode())
			->receive();

		if (empty($points))
		{
			CalculatorLogger::debug(
				"Не найдено пунктов выдачи для адреса \"{$this->getRegionCode()} {$this->getCityName()}\""
			);

			throw CalculatorException::error(CalculatorException::NO_POINTS);
		}

		$tariffs = [];
		foreach ($points as $point)
		{
			try
			{
				$tariffs[] = $this->getTariff($tarifficator, $point);
			}
			catch (\Exception $e)
			{
				CalculatorLogger::error(
					"Получено исключение от сервиса тарификации. Не удалось произвести расчет для пункта выдачи \"{$point->getId()}\""
				);
			}
		}

		CalculatorLogger::debug('Расчет тарифов завершен', $tariffs);

		return $tariffs;
	}

	/**
	 * @param Tarifficator $tarifficator
	 * @param Point $point
	 * @return array
	 * @throws \Exception
	 */
	private function getTariff(Tarifficator $tarifficator, Point $point)
	{
		$result = $tarifficator->calculate($point);

		$datetime_interval = $this->getDeliveryDateTimeInterval($point->getSchedule());
		$delivery_interval = array_map(
			[__CLASS__, 'formatDateTimeISO'],
			$datetime_interval
		);
		$est_delivery = DateTimeLocaleFormatter::formatInterval($datetime_interval);

		$tariff = [
			'name' => $point->getLocation()->getAddress(),
			'description' => $point->getLocation()->getFullAddress(),
			'est_delivery' => $est_delivery,
			'delivery_date' => $delivery_interval,
			'timezone' => date_default_timezone_get(),
			'currency' => Config::CURRENCY,
			'rate' => $result->getRate() + $result->getTax(),
			'type' => \waShipping::TYPE_PICKUP,
			'service' => $point->getName(),

			'custom_data' => [
				\waShipping::TYPE_PICKUP => [
					'id' => $point->getId(),
					'lat' => (string)$point->getLocation()->getLatitude(),
					'lng' => (string)$point->getLocation()->getLongitude(),
					'name' => $point->getLocation()->getAddress(),
					'description' => $point->getLegalName(),
					'way' => $point->getLocation()->getWay(),
					'schedule' => $this->getScheduleWithDateTimeInterval(
						$point->getSchedule(),
						$datetime_interval
					),
				],
			],
		];

		CalculatorLogger::debug('Расчитан тариф', $tariff);

		return $tariff;
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
	 * @param IPointSchedule $schedule
	 * @return \DateTime[]
	 * @throws \Exception
	 */
	private function getDeliveryDateTimeInterval(IPointSchedule $schedule)
	{
		$interval = $this->getDeliveryInterval();
		$datetime = new \DateTime($this->getPackageProperty('departure_datetime'));

		$datetime_interval = [];

		if ($interval[0])
		{
			$from_datetime = clone $datetime;
			$from_datetime->modify("+{$interval[0]} day");

			$this->modifyDateTimeWithSchedule($from_datetime, $schedule);

			$datetime_interval[] = $from_datetime;
		}

		if ($interval[1])
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

}
