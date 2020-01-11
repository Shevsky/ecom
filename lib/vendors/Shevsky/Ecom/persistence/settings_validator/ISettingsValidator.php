<?php

namespace Shevsky\Ecom\Persistence\SettingsValidator;

interface ISettingsValidator
{
	/**
	 * @param string $name
	 * @return ISettingValidator|null
	 */
	public function getValidator($name);
}
