<?php

namespace Shevsky\Ecom\Services\DeliveryIntervalHandbook;

use Shevsky\Ecom\Context;

class DeliveryIntervalHandbook
{
	private $handbook;

	/**
	 * @return array
	 * @throws \Exception
	 */
	private function getHandbook()
	{
		if (!isset($this->handbook))
		{
			$handbook_path = Context::getInstance()->env->getPluginPath()
				. 'lib/config/data/delivery_interval_handbook.json';
			if (!file_exists($handbook_path))
			{
				throw new \Exception('Справочник по интервалам сроков доставки не найден');
			}

			$handbook_json = file_get_contents($handbook_path);
			$handbook = json_decode($handbook_json, true);

			if ($handbook === null || json_last_error() !== JSON_ERROR_NONE)
			{
				throw new \Exception('Не удалось распарсить JSON-справочник');
			}

			$this->handbook = $handbook;
		}

		return $this->handbook;
	}

	/**
	 * @param {string} $from_region_code
	 * @param {string} $from_city_name
	 * @param {string} $to_region_code
	 * @param {string} $to_city_name
	 * @return int[]
	 * @throws \Exception
	 */
	public function getInterval($from_region_code, $from_city_name, $to_region_code, $to_city_name)
	{
		$handbook = $this->getHandbook();

		if (array_key_exists($from_region_code, $handbook))
		{
			if (array_key_exists($from_city_name, $handbook[$from_region_code]))
			{
				if (array_key_exists($to_region_code, $handbook[$from_region_code][$from_city_name]))
				{
					if (array_key_exists($to_city_name, $handbook[$from_region_code][$from_city_name][$to_region_code]))
					{
						$interval = $handbook[$from_region_code][$from_city_name][$to_region_code][$to_city_name];

						if (!is_array($interval))
						{
							throw new \Exception('Интервал не является массивом');
						}

						array_walk($interval, 'intval');
						sort($interval);

						return $interval;
					}
				}
			}
		}

		throw new \Exception(
			"Интервал доставки по направлению \"{$from_region_code} {$from_city_name} {$to_region_code} {$to_city_name}\" в справочнике отсутствует"
		);
	}
}
