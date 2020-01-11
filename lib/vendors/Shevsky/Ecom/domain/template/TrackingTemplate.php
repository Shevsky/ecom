<?php

namespace Shevsky\Ecom\Domain\Template;

class TrackingTemplate extends ViewTemplate
{
	private $tracking_id;

	/**
	 * @param string $tracking_id
	 */
	public function __construct($tracking_id)
	{
		$this->tracking_id = $tracking_id;
	}

	/**
	 * @return string
	 */
	public function render()
	{
		return $this->fetch("string:test {$this->tracking_id}");
	}
}
