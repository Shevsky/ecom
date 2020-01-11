<?php

namespace Shevsky\Ecom\Persistence\Point;

interface IPointLocationBuilding
{
	/**
	 * @return string
	 */
	public function getStreet();

	/**
	 * @return string
	 */
	public function getHouse();

	/**
	 * @return string
	 */
	public function getBuilding();

	/**
	 * @return string
	 */
	public function getCorpus();

	/**
	 * @return string
	 */
	public function getLetter();

	/**
	 * @return string
	 */
	public function getHotel();

	/**
	 * @return string
	 */
	public function getRoom();

	/**
	 * @return string
	 */
	public function getSlash();

	/**
	 * @return string
	 */
	public function getOffice();

	/**
	 * @return string
	 */
	public function getVladenie();
}
