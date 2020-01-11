<?php

namespace Shevsky\Ecom;

use Shevsky\Ecom\Domain\Env\Env;

class Context
{
	public $env;

	private static $self;

	/**
	 * @return Context
	 */
	public static function getInstance()
	{
		if (!isset(self::$self))
		{
			self::$self = new self();
		}

		return self::$self;
	}

	private function __construct()
	{
		$this->env = new Env();
	}
}
