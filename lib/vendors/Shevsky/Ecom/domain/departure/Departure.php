<?php

namespace Shevsky\Ecom\Domain\Departure;

use Shevsky\Ecom\Persistence\Departure\IDeparture;

class Departure implements IDeparture
{
	private $params;

	/**
	 * @param array $params = [
	 *  'index_from' => string,
	 *  'index_to' => string,
	 *  'pass_goods_value' => bool,
	 *  'total_value_mode' => string,
	 *  'mail_category' => string,
	 *  'mail_type' => string,
	 *  'entries_type' => string,
	 *  'payment_method' => string,
	 *  'notice_payment_method' => string,
	 *  'sms_notice_recipient' => bool,
	 *  'with_fitting' => bool,
	 *  'functionality_checking' => bool,
	 *  'contents_checking' => bool,
	 *  'completeness_checking' => bool,
	 *  'vsd' => bool,
	 *  'fragile' => bool
	 * ]
	 */
	public function __construct(array $params)
	{
		$this->params = $params;
	}

	/**
	 * @return string
	 */
	public function getIndexFrom()
	{
		return $this->params['index_from'];
	}

	/**
	 * @return string
	 */
	public function getIndexTo()
	{
		return $this->params['index_to'];
	}

	/**
	 * @return bool
	 */
	public function isPassOrderValue()
	{
		return (bool)$this->params['pass_goods_value'];
	}

	/**
	 * @return string
	 */
	public function getOrderValueMode()
	{
		return $this->params['total_value_mode'];
	}

	/**
	 * @return string
	 */
	public function getMailCategory()
	{
		return $this->params['mail_category'];
	}

	/**
	 * @return string
	 */
	public function getMailType()
	{
		return $this->params['mail_type'];
	}

	/**
	 * @return string
	 */
	public function getEntriesType()
	{
		return $this->params['entries_type'];
	}

	/**
	 * @return string
	 */
	public function getPaymentMethod()
	{
		return $this->params['payment_method'];
	}

	/**
	 * @return string
	 */
	public function getNoticePaymentMethod()
	{
		return $this->params['notice_payment_method'];
	}

	/**
	 * @return bool
	 */
	public function isSmsNoticeRecipientService()
	{
		return (bool)$this->params['sms_notice_recipient'];
	}

	/**
	 * @return bool
	 */
	public function isWithFittingService()
	{
		return (bool)$this->params['with_fitting'];
	}

	/**
	 * @return bool
	 */
	public function isFunctionalityCheckingService()
	{
		return (bool)$this->params['functionality_checking'];
	}

	/**
	 * @return bool
	 */
	public function isContentsCheckingService()
	{
		return (bool)$this->params['contents_checking'];
	}

	/**
	 * @return bool
	 */
	public function isCompletenessCheckingService()
	{
		return (bool)$this->params['completeness_checking'];
	}

	/**
	 * @return bool
	 */
	public function isVSDService()
	{
		return (bool)$this->params['vsd'];
	}

	/**
	 * @return bool
	 */
	public function isFragile()
	{
		return (bool)$this->params['fragile'];
	}
}
