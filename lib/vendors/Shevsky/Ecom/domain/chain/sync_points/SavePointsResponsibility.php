<?php

namespace Shevsky\Ecom\Chain\SyncPoints;

use ecomShippingPointsModel;
use Shevsky\Ecom\Persistence\Chain\IResponsibility;
use Shevsky\Ecom\Util\PointFormatter;

/**
 * @var string $test
 */
class SavePointsResponsibility implements IResponsibility
{
	private $points_model;

	public function __construct()
	{
		$this->points_model = new ecomShippingPointsModel();
	}

	/**
	 * @param mixed $args
	 * @return mixed[]
	 * @throws \Exception
	 */
	public function execute(array ...$args)
	{
		if (!array_key_exists(0, $args))
		{
			throw new \Exception('Не удалось распознать пункты выдачи');
		}

		list($points) = $args;
		if (empty($points))
		{
			return [];
		}

		$this->points_model->truncate();

		foreach ($points as $point)
		{
			try
			{
				$data = PointFormatter::getPointDbDataFromApiData($point);
			}
			catch (\Exception $e)
			{
				continue;
			}

			$this->points_model->insert($data);
		}

		return [];
	}
}
