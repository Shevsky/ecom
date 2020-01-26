<?php

namespace Shevsky\Ecom\Services\Tarifficator;

use LapayGroup\RussianPost\Exceptions\RussianPostException;
use LapayGroup\RussianPost\ParcelInfo;
use LapayGroup\RussianPost\TariffInfo;
use Shevsky\Ecom\Enum;
use Shevsky\Ecom\Persistence\Departure\IDeparture;
use Shevsky\Ecom\Persistence\Order\IOrder;
use Shevsky\Ecom\Persistence\Point\IPoint;
use Shevsky\Ecom\Services\Tarifficator\ApiAdapter;
use Shevsky\Ecom\Util\ILogger;

class Tarifficator
{
	const MAIL_DIRECT = 643;

	private $order;
	private $departure;
	/**
	 * @var ApiAdapter\ITarifficatorApiAdapter
	 */
	private $api_adapter;

	private $debug_mode = Enum\DebugMode::DISABLED;
	private $logger;

	/**
	 * @param IOrder $order
	 * @param IDeparture $departure
	 * @param string $api_adapter_classname
	 * @param mixed[] $api_adapter_construct_params
	 * @throws \Exception
	 */
	public function __construct(
		IOrder $order,
		IDeparture $departure,
		$api_adapter_classname,
		...$api_adapter_construct_params
	)
	{
		$this->order = $order;
		$this->departure = $departure;

		$this->api_adapter = new $api_adapter_classname(...$api_adapter_construct_params);
	}

	/**
	 * @param IPoint $point
	 * @return TarifficatorResult
	 * @throws \Exception
	 */
	public function calculate(IPoint $point)
	{
		$this->getLogger()->debug("Начинаем расчет тарифа");

		try
		{
			$tariff_info = $this->api_adapter->getTariff($this->buildParcelInfo($point));
		}
		catch (RussianPostException $e)
		{
			$this->getLogger()->error(
				'Получено исключение при попытке произвести расчет стоимости доставки',
				[
					'message' => $e->getMessage(),
					'code' => $e->getCode(),
					'raw_request' => $e->getRawRequest(),
					'raw_response' => $e->getRawResponse(),
				]
			);
			throw $e;
		}

		$result = new TarifficatorResult($tariff_info);

		$this->getLogger()->debug(
			'Получен результат расчета стоимости доставки',
			$result->toArray()
		);

		return $result;
	}

	/**
	 * @return string
	 */
	public function getMementoKey()
	{
		$order_memento = [$this->order->getDimensionType()];
		if ($this->departure->isPassOrderValue())
		{
			switch ($this->departure->getOrderValueMode())
			{
				case Enum\TotalValueMode::WITH_DISCOUNTS:
					$order_memento[] = round($this->order->getPriceWithDiscounts());
					break;
				case Enum\TotalValueMode::WITHOUT_DISCOUNTS:
					$order_memento[] = round($this->order->getPriceWithoutDiscounts());
					break;
			}
		}
		$order_memento_key = json_encode($order_memento);

		$departure_memento = $this->departure->toArray();
		unset($departure_memento['index_to']);
		$departure_memento_key = json_encode($departure_memento);

		$adapter_memento_key = $this->api_adapter->getMementoKey();

		return md5($order_memento_key . $departure_memento_key . $adapter_memento_key);
	}

	/**
	 * @param string $debug_mode
	 */
	public function setDebugMode($debug_mode)
	{
		$this->debug_mode = $debug_mode;
	}

	/**
	 * @return ILogger
	 */
	private function getLogger()
	{
		if (!isset($this->logger))
		{
			$this->logger = new TarifficatorLogger($this->debug_mode);
		}

		return $this->logger;
	}

	/**
	 * @param IPoint $point
	 * @return ParcelInfo
	 */
	private function buildParcelInfo(IPoint $point)
	{
		$goods_value = 0;
		if ($this->departure->isPassOrderValue())
		{
			switch ($this->departure->getOrderValueMode())
			{
				case Enum\TotalValueMode::WITH_DISCOUNTS:
					$goods_value = $this->order->getPriceWithDiscounts() * 100;
					break;
				case Enum\TotalValueMode::WITHOUT_DISCOUNTS:
					$goods_value = $this->order->getPriceWithoutDiscounts() * 100;
					break;
			}

			$goods_value = (int)$goods_value;
		}

		$parcel_info = new ParcelInfo();

		$parcel_info->setCourier(false);
		$parcel_info->setDeliveryPointIndex((string)$point->getIndex());
		$parcel_info->setHeight((int)($this->order->getHeight() / 10));
		$parcel_info->setLength((int)($this->order->getLength() / 10));
		$parcel_info->setWidth((int)($this->order->getWidth() / 10));
		$parcel_info->setWeight((int)($this->order->getWeight() * 1000));
		$parcel_info->setDimensionType($this->order->getDimensionType());
		$parcel_info->setIndexFrom($this->departure->getIndexFrom());
		$parcel_info->setMailCategory($this->departure->getMailCategory());
		$parcel_info->setMailType($this->departure->getMailType());
		$parcel_info->setEntriesType($this->departure->getEntriesType());
		$parcel_info->setPaymentMethod($this->departure->getPaymentMethod());
		$parcel_info->setSmsNoticeRecipient($this->departure->isSmsNoticeRecipientService());
		$parcel_info->setFragile($this->departure->isFragile());
		$parcel_info->setGoodsValue($goods_value);

		if ($point->hasContentsChecking())
		{
			$parcel_info->setContentsChecking($this->departure->isContentsCheckingService());
		}
		if ($point->hasFunctionalityChecking())
		{
			$parcel_info->setFunctionalityChecking($this->departure->isFunctionalityCheckingService());
		}

		// Пока что не поддерживается ни одним ПВЗ
		//if ($point->hasFitting())
		//{
		//	$parcel_info->setWithFitting($this->departure->isWithFittingService());
		//}

		$this->getLogger()->debug('Сгенерирован контент для произведения расчета', $parcel_info->getArray());

		return $parcel_info;
	}
}
