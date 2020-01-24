<?php

namespace Shevsky\Ecom\Domain\Services\SettingsValidator;

use Shevsky\Ecom\Domain\Services\DimensionTypeClassificator;
use Shevsky\Ecom\Enum;
use Shevsky\Ecom\Persistence\Services\SettingsValidator\ISettingValidator;

class DefaultDimensionSettingsValidator implements ISettingValidator
{
	/**
	 * @param mixed $settings
	 * @return boolean
	 * @throws \Exception
	 */
	public function validate($settings)
	{
		if (!array_key_exists('default_weight', $settings) || !array_key_exists('default_height', $settings)
			|| !array_key_exists('default_length', $settings)
			|| !array_key_exists('default_width', $settings))
		{
			throw new \Exception('Габариты/вес по умолчанию не указаны');
		}

		$default_weight = (float)$settings['default_weight'];
		$default_height = (int)$settings['default_height'];
		$default_length = (int)$settings['default_length'];
		$default_width = (int)$settings['default_width'];

		$dimension_type_classificator = new DimensionTypeClassificator(
			$default_weight,
			$default_height,
			$default_length,
			$default_width
		);

		try
		{
			$dimension_type = $dimension_type_classificator->getDimensionType();
		}
		catch (\Exception $e)
		{
			throw new \Exception("Некорректные габариты по умолчанию: {$e->getMessage()}", $e->getCode(), $e);
		}

		$this->validateDimensionType($settings, $dimension_type);

		return true;
	}

	/**
	 * @param mixed $settings
	 * @param string $dimension_type
	 * @throws \Exception
	 */
	private function validateDimensionType($settings, $dimension_type)
	{
		if (array_key_exists('undefined_dimension_case', $settings)
			&& $settings['undefined_dimension_case'] === Enum\UndefinedDimensionCase::FIXED_DIMENSION_TYPE)
		{
			if (!array_key_exists('dimension_type', $settings))
			{
				throw new \Exception('Укажите типоразмер по умолчанию');
			}

			$default_dimension_type = $settings['dimension_type'];

			if ($dimension_type !== $default_dimension_type)
			{
				throw new \Exception(
					"Типоразмер по умолчанию ({$default_dimension_type}) не совпадает с расчитанным типоразмером исходя из габаритов по умолчанию ({$dimension_type})"
				);
			}
		}
	}
}
