<?php

namespace Shevsky\Ecom\Services\Tracking;

use Shevsky\Ecom\Util\IArrayConvertable;
use Shevsky\Ecom\Util\IMemento;

class TrackingRecord implements IArrayConvertable, IMemento
{
	private $record;

	/**
	 * @param array $record
	 */
	public function __construct($record)
	{
		$this->record = $record;
	}

	/**
	 * @param array $record
	 * @return self
	 */
	public static function build($record)
	{
		return new self($record);
	}

	/**
	 * @param \stdClass $raw_record
	 * @return TrackingRecord
	 */
	public static function buildWithHistoryRecordObject($raw_record)
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

		if (is_object($raw_record))
		{
			if (property_exists($raw_record, 'OperationParameters'))
			{
				if (property_exists($raw_record->OperationParameters, 'OperDate'))
				{
					try
					{
						$record['date'] = \waDateTime::format(
							'humandatetime',
							$raw_record->OperationParameters->OperDate
						);
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
		}

		return new self($record);
	}

	/**
	 * @return string|null
	 */
	public function getDate()
	{
		return $this->record['date'];
	}

	/**
	 * @return string|null
	 */
	public function getAddressDescription()
	{
		return $this->record['address_description'];
	}

	/**
	 * @return string|null
	 */
	public function getAddressIndex()
	{
		return $this->record['address_index'];
	}

	/**
	 * @return string|null
	 */
	public function getAttrId()
	{
		return $this->record['attr_id'];
	}

	/**
	 * @return string|null
	 */
	public function getAttrName()
	{
		return $this->record['attr_name'];
	}

	/**
	 * @return string|null
	 */
	public function getTypeId()
	{
		return $this->record['type_id'];
	}

	/**
	 * @return string|null
	 */
	public function getTypeName()
	{
		return $this->record['type_name'];
	}

	/**
	 * @return array
	 */
	public function memento()
	{
		return $this->toArray();
	}

	/**
	 * @param $data
	 * @return static
	 */
	public static function restore($data)
	{
		return new self($data);
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return $this->record;
	}
}
