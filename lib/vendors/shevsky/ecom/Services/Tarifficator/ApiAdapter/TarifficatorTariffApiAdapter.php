<?php

namespace Shevsky\Ecom\Services\Tarifficator\ApiAdapter;

use GuzzleHttp\Exception\GuzzleException;
use LapayGroup\RussianPost\Exceptions\RussianPostException;
use LapayGroup\RussianPost\ParcelInfo;
use LapayGroup\RussianPost\Providers\Calculation;
use LapayGroup\RussianPost\TariffInfo;
use Shevsky\Ecom\Enum;

class TarifficatorTariffApiAdapter implements ITarifficatorApiAdapter
{
	const ECOM_ORDINARY_OBJECT_ID = 53030;
	const ECOM_WITH_COMPULSORY_PAYMENT_OBJECT_ID = 53070;

	const SMS_NOTICE_RECIPIENT_CODES = [71, 72, 73, 74, 75, 76, 77];
	const CONTENTS_CHECKING_CODE = 81;
	const WITH_FITTING_CODE = 82;
	const FUNCTIONALITY_CHECKING_CODE = 83;

	private $agreement_number;
	private $calculation;

	private $package_codes = [
		Enum\DimensionType::SMALL => 10,
		Enum\DimensionType::MEDIUM => 20,
		Enum\DimensionType::LARGE => 30,
		Enum\DimensionType::EXTRA_LARGE => 40,
		Enum\DimensionType::OVERSIZED => 99,
	];

	public function __construct($agreement_number)
	{
		$this->agreement_number = $agreement_number;

		$this->calculation = new Calculation();
	}

	/**
	 * @param ParcelInfo $parcel_info
	 * @return TariffInfo
	 * @throws RussianPostException
	 * @throws \Exception
	 */
	public function getTariff(ParcelInfo $parcel_info)
	{
		$object_id = null;

		$mail_type = $parcel_info->getMailType();
		if ($mail_type !== Enum\MailType::ECOM)
		{
			throw new \Exception('Адаптер для API сервиса Тарификатор может работать только с отправлениями ЕКОМ');
		}

		$mail_category = $parcel_info->getMailCategory();
		if (!in_array($mail_category, [Enum\MailCategory::ORDINARY, Enum\MailCategory::WITH_COMPULSORY_PAYMENT]))
		{
			throw new \Exception(
				'ЕКОМ-отправления могут быть только с категорией "Обыкновенное" или "С обязательным платежом"'
			);
		}

		if ($mail_category === Enum\MailCategory::WITH_COMPULSORY_PAYMENT)
		{
			$object_id = self::ECOM_WITH_COMPULSORY_PAYMENT_OBJECT_ID;
		}
		else
		{
			$object_id = self::ECOM_ORDINARY_OBJECT_ID;
		}

		$dimension_type = $parcel_info->getDimensionType();
		if (array_key_exists($dimension_type, $this->package_codes))
		{
			$package_code = $this->package_codes[$dimension_type];
		}
		else
		{
			throw new \Exception("Неизвестный типоразмер отправления \"{$dimension_type}\"");
		}

		$params = [
			'from' => $parcel_info->getIndexFrom(),
			'to' => $parcel_info->getDeliveryPointIndex(),
			'weight' => $parcel_info->getWeight(),
			'pack' => $package_code,
			'dogovor' => $this->agreement_number,
			'sumin' => $parcel_info->getGoodsValue(),
		];

		$services = [];
		if ($parcel_info->getSmsNoticeRecipient())
		{
			$services = array_merge($services, self::SMS_NOTICE_RECIPIENT_CODES);
		}
		if ($parcel_info->isContentsChecking())
		{
			$services[] = self::CONTENTS_CHECKING_CODE;
		}
		if ($parcel_info->isWithFitting())
		{
			$services[] = self::WITH_FITTING_CODE;
		}
		if ($parcel_info->isFunctionalityChecking())
		{
			$services[] = self::FUNCTIONALITY_CHECKING_CODE;
		}

		try
		{
			$response = $this->calculation->getTariff($object_id, $params, $services);
		}
		catch (GuzzleException $e)
		{
			throw new \Exception($e->getMessage(), $e->getCode(), $e);
		}

		return $this->buildTariffInfo($response);
	}

	/**
	 * @param array $response
	 * @return TariffInfo
	 */
	private function buildTariffInfo($response)
	{
		$tariff_info = new TariffInfo([]);

		if (!empty($response['pay']))
		{
			$tariff_info->setTotalRate($response['pay']);
		}
		else
		{
			throw new \Exception('Не удалось расчитать стоимость доставки');
		}

		if (!empty($response['nds']))
		{
			$tariff_info->setTotalNds($response['nds']);
		}

		return $tariff_info;
	}
}
