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

class Tarifficator
{
	const MAIL_DIRECT = 643;

	private $order;
	private $departure;
	/**
	 * @var ApiAdapter\ITarifficatorApiAdapter
	 */
	private $api_adapter;

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
	 * @return TariffInfo
	 * @throws \Exception
	 */
	public function calculate(IPoint $point)
	{
		TarifficatorLogger::debug("Начинаем расчет тарифа");

		try
		{
			$tariff = $this->api_adapter->getTariff($this->buildParcelInfo($point));
		}
		catch (RussianPostException $e)
		{
			TarifficatorLogger::error(
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

		TarifficatorLogger::debug(
			'Получен результат расчета стоимости доставки',
			[
				'total-rate' => $tariff->getTotalRate(),
				'total-vat' => $tariff->getTotalNds(),

				'avia-rate' => $tariff->getAviaRate(),
				'avia-vat' => $tariff->getAviaNds(),

				'ground-rate' => $tariff->getGroundRate(),
				'ground-vat' => $tariff->getGroundNds(),

				'fragile-rate' => $tariff->getFragileRate(),
				'fragile-vat' => $tariff->getFragileNds(),

				'contents-checking-rate' => $tariff->getContentsCheckingRate(),
				'contents-checking-vat' => $tariff->getContentsCheckingNds(),

				'functionality-checking-rate' => $tariff->getFunctionalityCheckingRate(),
				'functionality-checking-vat' => $tariff->getFunctionalityCheckingNds(),

				'with-fitting-rate' => $tariff->getWithFittingRate(),
				'with-fitting-vat' => $tariff->getWithFittingNds(),

				'notice-rate' => $tariff->getNoticeRate(),
				'notice-vat' => $tariff->getNoticeNds(),

				'oversize-rate' => $tariff->getOversizeRate(),
				'oversize-vat' => $tariff->getOversizeNds(),
			]
		);

		return $tariff;
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
		$parcel_info->setCompletenessChecking($this->departure->isCompletenessCheckingService());
		$parcel_info->setContentsChecking($this->departure->isContentsCheckingService());
		$parcel_info->setFragile($this->departure->isFragile());
		$parcel_info->setFunctionalityChecking($this->departure->isFunctionalityCheckingService());
		$parcel_info->setGoodsValue($goods_value);
		$parcel_info->setWithFitting($this->departure->isWithFittingService());

		TarifficatorLogger::debug('Сгенерирован контент для произведения расчета', $parcel_info->getArray());

		return $parcel_info;
	}
}
