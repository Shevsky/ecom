<?php

namespace Shevsky\Ecom\Domain\Point;

use Shevsky\Ecom\Persistence\Point\IPointLocationBuilding;

class PointLocationBuilding implements IPointLocationBuilding
{
	private $data;

	/**
	 * @param array $data = [
	 *  'street' => string,
	 *  'house' => string,
	 *  'building' => string,
	 *  'corpus' => string,
	 *  'letter' => string,
	 *  'hotel' => string,
	 *  'room' => string,
	 *  'slash' => string,
	 *  'office' => string,
	 *  'vladenie' => string,
	 * ]
	 */
	public function __construct($data)
	{
		$this->data = $data;
	}

	/**
	 * @return string
	 */
	public function getStreet()
	{
		return (string)$this->data['street'];
	}

	/**
	 * @return string
	 */
	public function getHouse()
	{
		return (string)$this->data['house'];
	}

	/**
	 * @return string
	 */
	public function getBuilding()
	{
		return (string)$this->data['building'];
	}

	/**
	 * @return string
	 */
	public function getCorpus()
	{
		return (string)$this->data['corpus'];
	}

	/**
	 * @return string
	 */
	public function getLetter()
	{
		return (string)$this->data['letter'];
	}

	/**
	 * @return string
	 */
	public function getHotel()
	{
		return (string)$this->data['hotel'];
	}

	/**
	 * @return string
	 */
	public function getRoom()
	{
		return (string)$this->data['room'];
	}

	/**
	 * @return string
	 */
	public function getSlash()
	{
		return (string)$this->data['slash'];
	}

	/**
	 * @return string
	 */
	public function getOffice()
	{
		return (string)$this->data['office'];
	}

	/**
	 * @return string
	 */
	public function getVladenie()
	{
		return (string)$this->data['vladenie'];
	}
}
