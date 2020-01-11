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
}
