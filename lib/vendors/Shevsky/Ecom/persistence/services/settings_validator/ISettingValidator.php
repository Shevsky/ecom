<?php

namespace Shevsky\Ecom\Persistence\Services\SettingsValidator;

interface ISettingValidator
{
	/**
	 * @param mixed $value
	 * @return boolean
	 * @throws \Exception
	 */
	public function validate($value);
}
