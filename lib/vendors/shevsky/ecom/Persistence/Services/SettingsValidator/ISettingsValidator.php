<?php

namespace Shevsky\Ecom\Persistence\Services\SettingsValidator;

interface ISettingsValidator
{
	/**
	 * @param string $name
	 * @return ISettingValidator|null
	 */
	public function getValidator($name);
}
