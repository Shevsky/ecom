<?php

namespace Shevsky\Ecom\Persistence\SettingsValidator;

interface ISettingValidator
{
	/**
	 * @param mixed $value
	 * @return boolean
	 * @throws \Exception
	 */
	public function validate($value);
}
