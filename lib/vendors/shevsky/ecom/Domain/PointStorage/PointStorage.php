<?php

namespace Shevsky\Ecom\Domain\PointStorage;

use ecomShippingPointsModel;
use Shevsky\Ecom\Domain\Point\Point;
use Shevsky\Ecom\Persistence\PointStorage\IPointStorage;
use waException;

class PointStorage implements IPointStorage
{
	private $points_model;

	private $filters = [];

	public function __construct()
	{
		$this->points_model = new ecomShippingPointsModel();
	}

	/**
	 * @param string $region_code
	 * @return self
	 */
	public function filterByRegionCode($region_code)
	{
		if (!array_key_exists('region_code', $this->filters))
		{
			$this->filters['region_code'] = [];
		}

		$this->filters['region_code'][] = $region_code;
		$this->filters['region_code'] = array_unique($this->filters['region_code']);

		return $this;
	}

	/**
	 * @param string $city_name
	 * @return self
	 */
	public function filterByCityName($city_name)
	{
		if (!array_key_exists('city_name', $this->filters))
		{
			$this->filters['city_name'] = [];
		}

		$this->filters['city_name'][] = $city_name;
		$this->filters['city_name'] = array_unique($this->filters['city_name']);

		return $this;
	}

	/**
	 * @param bool $availability
	 * @return self
	 */
	public function filterByCardPaymentAvailability($availability)
	{
		$this->filters['card_payment'] = $availability;

		return $this;
	}

	/**
	 * @param bool $availability
	 * @return self
	 */
	public function filterByCashPaymentAvailability($availability)
	{
		$this->filters['cash_payment'] = $availability;

		return $this;
	}

	/**
	 * @param $availability
	 * @return self
	 */
	public function filterByDeliveryPointTypeAvailability($availability)
	{
		$this->filters['delivery_point_type'] = $availability;

		return $this;
	}

	/**
	 * @param $availability
	 * @return self
	 */
	public function filterByPickupPointTypeAvailability($availability)
	{
		$this->filters['pickup_point_type'] = $availability;

		return $this;
	}


	/**
	 * @return Point[]
	 */
	public function receive()
	{
		$raw_points = [];
		if (empty($this->filters))
		{
			$raw_points = $this->points_model->getAll();
		}
		else
		{
			try
			{
				$raw_points = $this->points_model->getByField($this->getFiltersToModelConditions(), true);
			}
			catch (waException $e)
			{
			}
		}

		return array_map([Point::class, 'build'], $raw_points);
	}

	/**
	 * @return number
	 */
	public function count()
	{
		if (empty($this->filters))
		{
			return $this->points_model->countAll();
		}

		try
		{
			return $this->points_model->countByField($this->getFiltersToModelConditions());
		}
		catch (waException $e)
		{
			return 0;
		}
	}

	/**
	 * @return array[]
	 */
	private function getFiltersToModelConditions()
	{
		$model_conditions = [];

		if (array_key_exists('region_code', $this->filters))
		{
			$model_conditions['region_code'] = $this->filters['region_code'];
		}

		if (array_key_exists('city_name', $this->filters))
		{
			$model_conditions['city_name'] = $this->filters['city_name'];
		}

		if (!empty($this->filters['card_payment']))
		{
			$model_conditions['card_payment'] = 1;
		}

		if (!empty($this->filters['cash_payment']))
		{
			$model_conditions['cash_payment'] = 1;
		}

		$model_conditions['type'] = [];
		if (!empty($this->filters['delivery_point_type']))
		{
			$model_conditions['type'][] = 'DELIVERY_POINT';
		}
		if (!empty($this->filters['pickup_point_type']))
		{
			$model_conditions['type'][] = 'PICKUP_POINT';
		}

		if (empty($model_conditions['type']))
		{
			unset($model_conditions['type']);
		}

		return $model_conditions;
	}
}
