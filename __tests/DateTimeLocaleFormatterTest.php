<?php

require_once './bootstrap.php';

use PHPUnit\Framework\TestCase;
use \Shevsky\Ecom\Util\DateTimeLocaleFormatter;

class DateTimeLocaleFormatterTest extends TestCase
{
	public function testFormat()
	{
		$datetime = new DateTime('2020-12-15');

		$this->assertSame(
			DateTimeLocaleFormatter::format($datetime),
			'15 декабря 2020'
		);

		$this->assertSame(
			DateTimeLocaleFormatter::format($datetime, false),
			'15 декабря'
		);

		$this->assertSame(
			DateTimeLocaleFormatter::format($datetime, false, false),
			'15'
		);
	}

	public function testFormatInterval()
	{
		$this->assertSame(
			DateTimeLocaleFormatter::formatInterval([]),
			''
		);

		$this->assertSame(
			DateTimeLocaleFormatter::formatInterval([new DateTime('2020-12-15')]),
			'15 декабря 2020'
		);

		$this->assertSame(
			DateTimeLocaleFormatter::formatInterval([new DateTime('2020-12-15')], false),
			'15 декабря'
		);

		$this->assertSame(
			'от 15 до 18 декабря',
			DateTimeLocaleFormatter::formatInterval(
				[
					new DateTime('2020-12-15'),
					new DateTime('2020-12-18'),
				],
				false
			)
		);

		$this->assertSame(
			'от 15 до 18 декабря 2020',
			DateTimeLocaleFormatter::formatInterval(
				[
					new DateTime('2020-12-15'),
					new DateTime('2020-12-18'),
				]
			)
		);

		$this->assertSame(
			'от 15 ноября до 18 декабря',
			DateTimeLocaleFormatter::formatInterval(
				[
					new DateTime('2020-11-15'),
					new DateTime('2020-12-18'),
				],
				false
			)
		);

		$this->assertSame(
			'от 15 ноября до 18 декабря 2020',
			DateTimeLocaleFormatter::formatInterval(
				[
					new DateTime('2020-11-15'),
					new DateTime('2020-12-18'),
				]
			)
		);


		$this->assertSame(
			'от 15 ноября 2019 до 18 декабря 2020',
			DateTimeLocaleFormatter::formatInterval(
				[
					new DateTime('2019-11-15'),
					new DateTime('2020-12-18'),
				],
				false
			)
		);

		$this->assertSame(
			'от 15 ноября 2019 до 18 декабря 2020',
			DateTimeLocaleFormatter::formatInterval(
				[
					new DateTime('2019-11-15'),
					new DateTime('2020-12-18'),
				]
			)
		);
	}
}
