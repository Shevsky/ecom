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
	public function getPaymentMethod();

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
}
