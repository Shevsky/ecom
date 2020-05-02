<?php

namespace Shevsky\Ecom\Services\PointsRegistry;

use Throwable;

class PointsRegistryException extends \Exception
{
	const TYPE_END_OF_REGISTRY = 'end_of_registry';
	const TYPE_UNKNOWN = 'unknown';

	public $type = self::TYPE_UNKNOWN;

	/**
	 * @param string $type
	 * @param int $code
	 * @param \Throwable|null $previous
	 */
	public function __construct($type = '', $code = 0, Throwable $previous = null)
	{
		parent::__construct('', $code, $previous);

		$this->type = $type;
	}
}
