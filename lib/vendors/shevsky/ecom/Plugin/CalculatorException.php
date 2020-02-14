<?php

namespace Shevsky\Ecom\Plugin;

class CalculatorException extends \Exception
{
	const TYPE_ERROR = 'error';
	const TYPE_WARNING = 'warning';

	const BAD_COUNTRY = 1;
	const NO_INDEX = 2;
	const TARIFFICATOR_ERROR = 3;
	const NO_POINTS = 4;
	const UNDEFINED_DIMENSION_TYPE = 5;
	const OTPRAVKA_API_ERROR = 6;

	const ERROR_MESSAGE_POOL = [
		self::BAD_COUNTRY => 'Доставка возможна только по территории Российской Федерации',
		self::NO_INDEX => 'Не указан индекс доставки',
		self::TARIFFICATOR_ERROR => 'Недоступно',
		self::NO_POINTS => 'Пунктов выдачии ЕКОМ для этого региона не найдено',
		self::UNDEFINED_DIMENSION_TYPE => 'Недоступно',
		self::OTPRAVKA_API_ERROR => 'Недоступно',
	];

	private $type;

	/**
	 * @return bool
	 */
	public function isError()
	{
		return $this->type === self::TYPE_ERROR;
	}

	/**
	 * @return bool
	 */
	public function isWarning()
	{
		return $this->type === self::TYPE_WARNING;
	}

	/**
	 * @param string $type
	 * @param int $code
	 * @param \Throwable|null $previous
	 */
	public function __construct($type, $code, \Throwable $previous = null)
	{
		parent::__construct(self::ERROR_MESSAGE_POOL[$code], $code, $previous);

		$this->type = $type;
	}

	/**
	 * @param int $code
	 * @param \Throwable|null $previous
	 * @return self
	 */
	public static function error($code, \Throwable $previous = null)
	{
		return new CalculatorException(self::TYPE_ERROR, $code, $previous);
	}

	/**
	 * @param int $code
	 * @param \Throwable|null $previous
	 * @return self
	 */
	public static function warning($code, \Throwable $previous = null)
	{
		return new CalculatorException(self::TYPE_WARNING, $code, $previous);
	}
}
