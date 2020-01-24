<?php

namespace Shevsky\Ecom\Domain\Template;

class SettingsTemplate extends ViewTemplate
{
	/**
	 * @return string
	 */
	protected function getName()
	{
		return 'settings';
	}

	/**
	 * @return string[]
	 */
	protected function getJs()
	{
		return [
			'settings',
		];
	}

	/**
	 * @return string[]
	 */
	protected function getCss()
	{
		return [
			'settings',
		];
	}
}
