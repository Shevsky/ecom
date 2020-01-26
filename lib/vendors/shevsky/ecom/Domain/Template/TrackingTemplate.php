<?php

namespace Shevsky\Ecom\Domain\Template;

use LapayGroup\RussianPost\Providers\Tracking;

class TrackingTemplate extends ViewTemplate
{
	private $tracking_id;
	private $tracking;

	/**
	 * @param string $tracking_id
	 * @param Tracking $tracking
	 */
	public function __construct($tracking_id, Tracking $tracking)
	{
		$this->tracking_id = $tracking_id;
		$this->tracking = $tracking;
	}

	/**
	 * @return string
	 */
	public function render()
	{
		try
		{
			/**
			 * @var \stdClass[] $operations
			 */
			$raw_history = $this->tracking->getOperationsByRpo($this->tracking_id);
			$history = array_map([__CLASS__, 'historyRecordToArray'], $raw_history);
			krsort($history);
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

	/**
	 * @param \stdClass $raw_record
	 * @return array
	 */
	private function historyRecordToArray($raw_record)
	{
		$record = [
			'date' => null,
			'address_description' => null,
			'address_index' => null,
			'attr_id' => null,
			'attr_name' => null,
			'type_id' => null,
			'type_name' => null,
		];

		if (property_exists($raw_record, 'OperationParameters'))
		{
			if (property_exists($raw_record->OperationParameters, 'OperDate'))
			{
				try
				{
					$record['date'] = \waDateTime::format('humandatetime', $raw_record->OperationParameters->OperDate);
				}
				catch (\waException $e)
				{
					$record['date'] = $raw_record->OperationParameters->OperDate;
				}
			}

			if (property_exists($raw_record->OperationParameters, 'OperAttr'))
			{
				if (property_exists($raw_record->OperationParameters->OperAttr, 'Id'))
				{
					$record['attr_id'] = $raw_record->OperationParameters->OperAttr->Id;
				}

				if (property_exists($raw_record->OperationParameters->OperAttr, 'Name'))
				{
					$record['attr_name'] = $raw_record->OperationParameters->OperAttr->Name;
				}
			}

			if (property_exists($raw_record->OperationParameters, 'OperType'))
			{
				if (property_exists($raw_record->OperationParameters->OperType, 'Id'))
				{
					$record['type_id'] = $raw_record->OperationParameters->OperType->Id;
				}

				if (property_exists($raw_record->OperationParameters->OperType, 'Name'))
				{
					$record['type_name'] = $raw_record->OperationParameters->OperType->Name;
				}
			}
		}

		if (property_exists($raw_record, 'AddressParameters'))
		{
			if (property_exists($raw_record->AddressParameters, 'OperationAddress'))
			{
				if (property_exists($raw_record->AddressParameters->OperationAddress, 'Description'))
				{
					$record['address_description'] = $raw_record->AddressParameters->OperationAddress->Description;
				}

				if (property_exists($raw_record->AddressParameters->OperationAddress, 'Index'))
				{
					$record['address_index'] = $raw_record->AddressParameters->OperationAddress->Index;
				}
			}
		}

		return $record;
	}
}
