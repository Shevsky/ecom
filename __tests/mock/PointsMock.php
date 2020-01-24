<?php

class PointsMock
{
	/**
	 * @var array $points = [
	 *  $index => [
	 *      'address' => [
	 *          'address-type' => string,
	 *          'area' => string,
	 *          'building' => string,
	 *          'corpus' => string,
	 *          'hotel' => string,
	 *          'house' => string,
	 *          'index' => string,
	 *          'letter' => string,
	 *          'location' => string,
	 *          'num-address-type' => string,
	 *          'place' => string,
	 *          'region' => string,
	 *          'room' => string,
	 *          'slash' => string,
	 *          'street' => string,
	 *          'office' => string,
	 *          'vladenie' => string
	 *      ],
	 *      'brand-name' => string,
	 *      'closed' => boolean,
	 *      'delivery-point-index' => string,
	 *      'delivery-point-type' => string,
	 *      'getto' => string,
	 *      'id' => string,
	 *      'legal-name' => string,
	 *      'legal-short-name' => string,
	 *      'temporary-closed' => boolean,
	 *      'work-time' => string[],
	 *      'latitude' => string,
	 *      'longitude' => string,
	 *      'functionality-checking' => string,
	 *      'contents-checking' => string,
	 *      'with-fitting' => string,
	 *      'default_weight-limit' => float,
	 *      ]
	 *  ]
	 * ]
	 */
	private $points;

	public function __construct()
	{
		$this->points = json_decode(file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'points-all.json'), true);
	}

	public function getAll()
	{
		return $this->points;
	}

	public function getRandomChunk($chunk_size)
	{
		$chunk_random_start = rand(0, count($this->points) - $chunk_size);

		$points = $this->points;
		shuffle($points);

		return array_slice($points, $chunk_random_start, $chunk_size);
	}
}
