<?php

use LapayGroup\RussianPost\Exceptions\RussianPostException;
use Shevsky\Ecom\Domain\Point\Point;
use Shevsky\Ecom\Domain\PointStorage\PointStorage;
use Shevsky\Ecom\Provider;

class ecomShippingBackendFindPointsController extends waJsonController
{
	public function execute()
	{
		$region = waRequest::post('region', '', waRequest::TYPE_STRING_TRIM);
		$city = waRequest::post('city', '', waRequest::TYPE_STRING_TRIM);

		if (!$region || !$city)
		{
			$this->setError('Регион или город не указаны');

			return;
		}

		$points = array_map(
			[__CLASS__, 'pointToArray'],
			(new PointStorage())->filterByCityName($city)
				->filterByRegionCode($region)
				->receive()
		);

		$this->response = $points;
	}

	/**
	 * @param Point $point
	 * @return array
	 */
	private function pointToArray(Point $point)
	{
		return [
			'id' => $point->getId(),
			'index' => $point->getIndex(),
			'location' => $point->getLocation()->toArray(),
		];
	}
}
