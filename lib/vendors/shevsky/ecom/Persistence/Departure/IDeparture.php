<?php

namespace Shevsky\Ecom\Persistence\Departure;

interface IDeparture
{
	/**
	 * @return string
	 */
	public function getIndexFrom();

	/**
	 * @return string
	 */
	public function getIndexTo();

	/**
	 * @return bool
	 */
	public function isPassOrderValue();

	/**
	 * @return string
	 */
	public function getOrderValueMode();

	/**
	 * @return string
	 */
	public function getMailCategory();

	/**
	 * @return string
	 */
	public function getMailType();

	/**
	 * @return string
	 */
	public function getEntriesType();

	/**
	 * @return string
	 */
	public function getPaymentMethod();

	/**
	 * @return string
	 */
	public function getNoticePaymentMethod();

	/**
	 * @return bool
	 */
	public function isSmsNoticeRecipientService();

	/**
	 * @return bool
	 */
	public function isWithFittingService();

	/**
	 * @return bool
	 */
	public function isFunctionalityCheckingService();

	/**
	 * @return bool
	 */
	public function isContentsCheckingService();

	/**
	 * @return bool
	 */
	public function isCompletenessCheckingService();

	/**
	 * @return bool
	 */
	public function isVSDService();

	/**
	 * @return bool
	 */
	public function isFragile();

	/**
	 * @return array
	 */
	public function toArray();
}
