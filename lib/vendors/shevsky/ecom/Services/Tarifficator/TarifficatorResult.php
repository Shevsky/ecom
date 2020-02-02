<?php

namespace Shevsky\Ecom\Services\Tarifficator;

use LapayGroup\RussianPost\TariffInfo;
use Shevsky\Ecom\Util\IArrayConvertable;
use Shevsky\Ecom\Util\IMemento;

class TarifficatorResult implements IArrayConvertable, IMemento
{
	private $tariff_info;

	/**
	 * @param TariffInfo $tariff_info
	 */
	public function __construct(TariffInfo $tariff_info)
	{
		$this->tariff_info = $tariff_info;
	}

	/**
	 * @return TariffInfo
	 */
	public function getInfo()
	{
		return $this->tariff_info;
	}

	/**
	 * @return array
	 */
	public function memento()
	{
		return $this->toArray();
	}

	/**
	 * @param mixed $data
	 * @return static
	 * @throws \Exception
	 */
	public static function restore($data)
	{
		if (!is_array($data))
		{
			throw new \Exception('Не удалось восстановить снимок');
		}


		$tariff_info = new TariffInfo([]);

		if (!empty($data['total-rate']))
		{
			$tariff_info->setTotalRate($data['total-rate']);
		}
		if (!empty($data['total-vat']))
		{
			$tariff_info->setTotalNds($data['total-vat']);
		}

		if (!empty($data['avia-rate']))
		{
			$tariff_info->setAviaRate($data['avia-rate']);
		}
		if (!empty($data['avia-vat']))
		{
			$tariff_info->setAviaNds($data['avia-vat']);
		}

		if (!empty($data['ground-rate']))
		{
			$tariff_info->setGroundRate($data['ground-rate']);
		}
		if (!empty($data['ground-vat']))
		{
			$tariff_info->setGroundNds($data['ground-vat']);
		}

		if (!empty($data['fragile-rate']))
		{
			$tariff_info->setFragileRate($data['fragile-rate']);
		}
		if (!empty($data['fragile-vat']))
		{
			$tariff_info->setFragileNds($data['fragile-vat']);
		}

		if (!empty($data['contents-checking-rate']))
		{
			$tariff_info->setContentsCheckingRate($data['contents-checking-rate']);
		}
		if (!empty($data['contents-checking-vat']))
		{
			$tariff_info->setContentsCheckingNds($data['contents-checking-vat']);
		}

		if (!empty($data['functionality-checking-rate']))
		{
			$tariff_info->setFunctionalityCheckingRate($data['functionality-checking-rate']);
		}
		if (!empty($data['functionality-checking-vat']))
		{
			$tariff_info->setFunctionalityCheckingNds($data['functionality-checking-vat']);
		}

		if (!empty($data['with-fitting-rate']))
		{
			$tariff_info->setWithFittingRate($data['with-fitting-rate']);
		}
		if (!empty($data['with-fitting-vat']))
		{
			$tariff_info->setWithFittingNds($data['with-fitting-vat']);
		}

		if (!empty($data['notice-rate']))
		{
			$tariff_info->setNoticeRate($data['notice-rate']);
		}
		if (!empty($data['notice-vat']))
		{
			$tariff_info->setNoticeNds($data['notice-vat']);
		}

		if (!empty($data['oversize-rate']))
		{
			$tariff_info->setOversizeRate($data['oversize-rate']);
		}
		if (!empty($data['oversize-vat']))
		{
			$tariff_info->setOversizeNds($data['oversize-vat']);
		}

		return new self($tariff_info);
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return [
			'total-rate' => $this->getInfo()->getTotalRate(),
			'total-vat' => $this->getInfo()->getTotalNds(),

			'avia-rate' => $this->getInfo()->getAviaRate(),
			'avia-vat' => $this->getInfo()->getAviaNds(),

			'ground-rate' => $this->getInfo()->getGroundRate(),
			'ground-vat' => $this->getInfo()->getGroundNds(),

			'fragile-rate' => $this->getInfo()->getFragileRate(),
			'fragile-vat' => $this->getInfo()->getFragileNds(),

			'contents-checking-rate' => $this->getInfo()->getContentsCheckingRate(),
			'contents-checking-vat' => $this->getInfo()->getContentsCheckingNds(),

			'functionality-checking-rate' => $this->getInfo()->getFunctionalityCheckingRate(),
			'functionality-checking-vat' => $this->getInfo()->getFunctionalityCheckingNds(),

			'with-fitting-rate' => $this->getInfo()->getWithFittingRate(),
			'with-fitting-vat' => $this->getInfo()->getWithFittingNds(),

			'notice-rate' => $this->getInfo()->getNoticeRate(),
			'notice-vat' => $this->getInfo()->getNoticeNds(),

			'oversize-rate' => $this->getInfo()->getOversizeRate(),
			'oversize-vat' => $this->getInfo()->getOversizeNds(),
		];
	}
}
