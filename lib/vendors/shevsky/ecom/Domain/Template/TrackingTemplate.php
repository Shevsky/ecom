<?php

namespace Shevsky\Ecom\Domain\Template;

use Shevsky\Ecom\Services\Tracking\TrackingMaintenance;

class TrackingTemplate extends ViewTemplate
{
	private $tracking_id;
	private $tracking_maintenance;

	/**
	 * @param string $tracking_id
	 * @param TrackingMaintenance $tracking_maintenance
	 */
	public function __construct($tracking_id, TrackingMaintenance $tracking_maintenance)
	{
		$this->tracking_id = $tracking_id;
		$this->tracking_maintenance = $tracking_maintenance;
	}

	/**
	 * @return string
	 */
	public function render()
	{
		try
		{
			$history = $this->tracking_maintenance->getHistory($this->tracking_id);
		}
		catch (\Exception $e)
		{
			$this->assign(
				[
					'error' => $e->getMessage(),
				]
			);
			$history = [];
		}

		$this->assign(
			[
				'history' => $history,
			]
		);

		return parent::render();
	}

	/**
	 * @return string
	 */
	protected function getName()
	{
		return 'tracking';
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
