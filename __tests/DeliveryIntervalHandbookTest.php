<?php

require_once './bootstrap.php';

use PHPUnit\Framework\TestCase;
use Shevsky\Ecom\Services\DeliveryIntervalHandbook\DeliveryIntervalHandbook;

class DeliveryIntervalHandbookTest extends TestCase
{
	public function testGetIntervals()
	{
		$handbook = new DeliveryIntervalHandbook();

		$this->assertEquals(
			[2, 4],
			$handbook->getInterval('66', 'Екатеринбург', '36', 'Воронеж')
		);

		$this->assertEquals(
			[3, 5],
			$handbook->getInterval('18', 'Ижевск', '54', 'Новосибирск')
		);

		$this->assertEquals(
			[1, 3],
			$handbook->getInterval('66', 'Екатеринбург', '78', 'Санкт-Петербург')
		);

		$this->assertEquals(
			[1, 1],
			$handbook->getInterval('77', 'Москва', '77', 'Москва')
		);
	}

	public function testInvalidParams()
	{
		$this->expectException(\Exception::class);

		(new DeliveryIntervalHandbook())->getInterval('66', 'Екатеринбург', '77', '');
	}
}
