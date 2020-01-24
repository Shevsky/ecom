<?php

namespace Shevsky\Ecom\Persistence\Point;

interface IPoint
{
	/**
	 * @return int
	 */
	public function getId();

	/**
	 * @return int
	 */
	public function getIndex();

	/**
	 * @return string
	 */
	public function getType();

	/**
	 * @return bool
	 */
	public function getStatus();

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @return string
	 */
	public function getDescription();


	/**
	 * @return string
	 */
	public function getLegalName();

	/**
	 * @return string
	 */
	public function getLegalShortName();

	/**
	 * @return IPointSchedule
	 */
	public function getSchedule();

	/**
	 * @return IPointLocation
	 */
	public function getLocation();

	/**
	 * @return string[]
	 */
	public function getOptions();

	/**
	 * @return bool
	 */
	public function hasContentsChecking();

	/**
	 * @return bool
	 */
	public function hasFunctionalityChecking();

	/**
	 * @return bool
	 */
	public function hasFitting();

	/**
	 * @return bool
	 */
	public function hasPartialRedemption();

	/**
	 * @return bool
	 */
	public function isAvailableCardPayment();

	/**
	 * @return bool
	 */
	public function isAvailableCashPayment();
}
