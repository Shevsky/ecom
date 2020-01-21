<?php

namespace Shevsky\Ecom\Services\Tarifficator;

class TarifficatorResult
{
	private $data;

	/**
	 * @param array $data = [
	 *  'functionality-checking-rate' => [
	 *      'rate' => int,
	 *      'vat' => int
	 *  ],
	 * 'ground-rate' => [
	 *      'rate' => int,
	 *      'vat' => int
	 *  ],
	 *  'insurance-rate' => [
	 *      'rate' => int,
	 *      'vat' => int
	 *  ],
	 *  'notice-payment-method' => string,
	 *  'payment-method' => string,
	 *  'sms-notice-recipient-rate' => [
	 *      'rate' => int,
	 *      'vat' => int
	 *  ],
	 *  'total-rate' => int,
	 *  'total-vat' => int,
	 *  'with-fitting-rate' => [
	 *      'rate' => int,
	 *      'vat' => int
	 *  ]
	 * ]
	 */
	public function __construct($data)
	{
		$this->data = $data;
	}

	/**
	 * @return float[]|null
	 */
	public function getFunctionalityCheckingRate()
	{
		return $this->getRates('functionality-checking-rate');
	}

	/**
	 * @return float[]|null
	 */
	public function getGroundRate()
	{
		return $this->getRates('ground-rate');
	}

	/**
	 * @return float[]|null
	 */
	public function getInsuranceRate()
	{
		return $this->getRates('insurance-rate');
	}

	/**
	 * @return float[]|null
	 */
	public function getSmsNoticeRecipientRate()
	{
		return $this->getRates('sms-notice-recipient-rate');
	}

	/**
	 * @return float[]|null
	 */
	public function getWithFittingRate()
	{
		return $this->getRates('with-fitting-rate');
	}

	/**
	 * @return string|null
	 */
	public function getNoticePaymentMethod()
	{
		if (!isset($this->data['notice-payment-method']))
		{
			return null;
		}

		return $this->data['notice-payment-method'];
	}

	/**
	 * @return string|null
	 */
	public function getPaymentMethod()
	{
		if (!isset($this->data['payment-method']))
		{
			return null;
		}

		return $this->data['payment-method'];
	}

	/**
	 * @return float|null
	 */
	public function getRate()
	{
		if (!isset($this->data['total-rate']))
		{
			return null;
		}

		return (float)$this->data['total-rate'] / 100;
	}

	/**
	 * @return float|null
	 */
	public function getTax()
	{
		if (!isset($this->data['total-vat']))
		{
			return null;
		}

		return (float)$this->data['total-vat'] / 100;
	}

	/**
	 * @param string $name
	 * @return float[]|null
	 */
	private function getRates($name)
	{
		if (!isset($this->data[$name]))
		{
			return null;
		}

		$rates = $this->data[$name];

		$rate = 0;
		$tax = 0;

		if (array_key_exists('rate', $rates))
		{
			$rate = (int)$rates['rate'];
			$rate = $rate / 100;
		}
		if (array_key_exists('var', $rates))
		{
			$tax = (int)$rates['vat'];
			$tax = $tax / 100;
		}

		$total = $rate + $tax;

		return [
			$total,
			$rate,
			$tax,
		];
	}
}
