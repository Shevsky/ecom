<?php

namespace Shevsky\Ecom\Domain\Template;

class TrackingSimpleTemplate extends ViewTemplate
{
	/**
	 * @return string
	 */
	protected function getName()
	{
		return 'tracking_simple';
	}

	/**
	 * @return string[]
	 */
	protected function getCss()
	{
		return [
			'tracking',
		];
	}
}
