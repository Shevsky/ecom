<?php

namespace Shevsky\Ecom\Services\Tarifficator;

use LapayGroup\RussianPost\Exceptions\RussianPostException;
use LapayGroup\RussianPost\ParcelInfo;
use LapayGroup\RussianPost\Providers\OtpravkaApi;
use LapayGroup\RussianPost\TariffInfo;
use Shevsky\Ecom\Enum;
use Shevsky\Ecom\Persistence\Departure\IDeparture;
use Shevsky\Ecom\Persistence\Order\IOrder;
use Shevsky\Ecom\Persistence\Point\IPoint;

class Tarifficator
{
	const MAIL_DIRECT = 643;

	private $order;
	private $departure;
	private $otpravka_api;

	/**
	 * @param IOrder $order
	 * @param IDeparture $departure
	 * @param OtpravkaApi $otpravka_api
	 */
	public function __construct(IOrder $order, IDeparture $departure, OtpravkaApi $otpravka_api)
	{
		$this->order = $order;
		$this->departure = $departure;
		$this->otpravka_api = $otpravka_api;
	}

	/**
	 * @param IPoint $point
	 * @return TariffInfo
	 * @throws \Exception
	 */
	public function calculate(IPoint $point)
	{
		TarifficatorLogger::debug('Начинаем расчет тарифа через API сервиса Отправка');

		try
		{
			$tariff = $this->otpravka_api->getDeliveryTariff($this->buildParcelInfo($point));
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
			$tariff->getRawData()
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
