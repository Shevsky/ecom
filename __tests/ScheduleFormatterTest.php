<?php

require_once './bootstrap.php';
require_once './mock/PointsMock.php';

use PHPUnit\Framework\TestCase;

class ScheduleFormatterTest extends TestCase
{
	private $mock;

	public function __construct()
	{
		parent::__construct();
		$this->mock = new PointsMock();
	}

	public function testParseScheduleChunk()
	{
		$chunk = $this->mock->getRandomChunk(10);

		foreach ($chunk as $point)
		{
			$this->testParseScheduleConcrete($point['work-time']);
		}
	}

	public function testParseScheduleAll()
	{
		$points = $this->mock->getAll();
		shuffle($points);
		$points_count = count($points);

		echo "Count: {$points_count}" . PHP_EOL;

		foreach ($points as $point)
		{
			$this->testParseScheduleConcrete($point['work-time']);
		}
	}

	protected function testParseScheduleConcrete($raw_schedules)
	{
		foreach ($raw_schedules as $raw_schedule)
		{
			try
			{
				\Shevsky\Ecom\Util\ScheduleFormatter::parseSchedule($raw_schedule);

				$this->assertNull(null);
			}
			catch (\Exception $e)
			{
				$this->fail("Cannot parse schedule \"{$raw_schedule}\"");
			}
		}
	}
}
