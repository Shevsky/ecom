<?php

namespace Shevsky\Ecom\Services\Tarifficator;

use Shevsky\Ecom\Api\Otpravka\MethodTariff;
use Shevsky\Ecom\Api\Otpravka\OtpravkaApi;
use Shevsky\Ecom\Enum;
use Shevsky\Ecom\Persistence\Departure\IDeparture;
use Shevsky\Ecom\Persistence\Order\IOrder;
use Shevsky\Ecom\Persistence\Point\IPoint;

class Tarifficator
{
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
	 * @return TarifficatorResult
	 * @throws \Exception
	 */
	public function calculate(IPoint $point)
	{
		TarifficatorLogger::debug('Начинаем расчет тарифа через API сервиса Отправка');

		// TODO test mock
		return new TarifficatorResult([
			'total-rate' => 5000,
			'total-vat' => 500
		]);

		try
		{
			$raw_result = $this->otpravka_api->execute($this->buildMethodTariff($point));
		}
		catch (\Exception $e)
		{
			TarifficatorLogger::debug(
				'Получено исключение при попытке произвести удаленный запрос',
				[
					'message' => $e->getMessage(),
					'code' => $e->getCode(),
				]
			);
			throw $e;
		}

		TarifficatorLogger::debug('Получен результат расчета тарифа', $raw_result);

		return new TarifficatorResult($raw_result);
	}

	/**
	 * @param IPoint $point
	 * @return MethodTariff
	 */
	private function buildMethodTariff(IPoint $point)
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

		$index_to = $this->departure->getIndexTo();
		if (empty($index_to))
		{
			$index_to = $point->getIndex();
		}

		$content = [
			'delivery-point-index' => $point->getId(),
			'dimension-type' => $this->order->getDimensionType(),
			'index-from' => $this->departure->getIndexFrom(),
			'goods-value' => $goods_value,
			'index-to' => $index_to,
			'mail-category' => $this->departure->getMailCategory(),
			'mail-type' => $this->departure->getMailType(),
			'mass' => (int)($this->order->getWeight() * 1000),
			'payment-method' => $this->departure->getPaymentMethod(),
			'sms-notice-recipient' => $this->departure->isSmsNoticeRecipientService() ? 1 : 0,
			'with-fitting' => $this->departure->isWithFittingService(),
			'functionality-checking' => $this->departure->isFunctionalityCheckingService(),
			'contents-checking' => $this->departure->isContentsCheckingService(),
		];

		TarifficatorLogger::debug('Сгенерирован контент для удаленного запроса', $content);

		return new MethodTariff($content);
	}
}
