<?php

namespace Shevsky\Ecom\Domain\Services\SettingsValidator;

use Shevsky\Ecom\Persistence\Services\SettingsValidator\ISettingValidator;

class ExtraChargeSettingValidator implements ISettingValidator
{
	/**
	 * @param mixed $value
	 * @return boolean
	 * @throws \Exception
	 */
	public function validate($value)
	{
		try
		{
			$view = wa()->getView();
			$view->assign('order', 1000);
			$view->assign('shipping', 100);

			$extra_charge = @$view->fetch('string:' . $value);

			if (!preg_match('/^[0-9\.]+$/', $extra_charge))
			{
				throw new \Exception(
					'Поле "Дополнительная наценка к стоимости доставки" должны быть либо числом, либо возвращать в результате Smarty-расчетов число'
				);
			}
		}
		catch (\SmartyCompilerException $e)
		{
			throw new \Exception($e);
		}

		return true;
	}
}
