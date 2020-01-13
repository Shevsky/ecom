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
		return $this->data['street'];
	}

	/**
	 * @return string
	 */
	public function getHouse()
	{
		return $this->data['house'];
	}

	/**
	 * @return string
	 */
	public function getBuilding()
	{
		return $this->data['building'];
	}

	/**
	 * @return string
	 */
	public function getCorpus()
	{
		return $this->data['corpus'];
	}

	/**
	 * @return string
	 */
	public function getLetter()
	{
		return $this->data['letter'];
	}

	/**
	 * @return string
	 */
	public function getHotel()
	{
		return $this->data['hotel'];
	}

	/**
	 * @return string
	 */
	public function getRoom()
	{
		return $this->data['room'];
	}

	/**
	 * @return string
	 */
	public function getSlash()
	{
		return $this->data['slash'];
	}

	/**
	 * @return string
	 */
	public function getOffice()
	{
		return $this->data['office'];
	}

	/**
	 * @return string
	 */
	public function getVladenie()
	{
		return $this->data['vladenie'];
	}
}
