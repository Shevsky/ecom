<?php

require_once './bootstrap.php';
require_once './mock/PointsMock.php';

use PHPUnit\Framework\TestCase;

class RegionFormatterTest extends TestCase
{
	private $mock;

	public function __construct()
	{
		parent::__construct();
		$this->mock = new PointsMock();
	}

	protected function testGetRegionCodeConcrete($region)
	{
		echo "Region \"{$region}\" -> ";

		try
		{
			$result = \Shevsky\Ecom\Util\RegionFormatter::getRegionCode($region);
			echo "\"{$result}\"";
			$this->assertNull(null);
		}
		catch (\Exception $e)
		{
			$this->fail("Cannot detect region \"{$region}\"");
		}

		echo PHP_EOL;
	}

	public function testGetRegionCodeAll()
	{
		$points = $this->mock->getAll();
		shuffle($points);
		$points_count = count($points);

		echo "Count: {$points_count}" . PHP_EOL;

		foreach ($points as $point)
		{
			$this->testGetRegionCodeConcrete($point['address']['region']);
		}
	}

	public function testGetRegionCodeChunk()
	{
		$chunk = $this->mock->getRandomChunk(10);

		foreach ($chunk as $point)
		{
			$this->testGetRegionCodeConcrete($point['address']['region']);
		}
	}

	public function testGetCityNameConcreteCase()
	{
		$place = 'тер Старый Петергоф';
		$this->testGetCityNameConcrete($place);
	}

	protected function testGetCityNameConcrete($place)
	{
		echo "Place \"{$place}\" -> ";

		try
		{
			$result = \Shevsky\Ecom\Util\RegionFormatter::getCityName($place);
			echo "\"{$result}\"";
			$this->assertNull(null);
		}
		catch (\Exception $e)
		{
			$this->fail("Cannot detect place \"{$place}\"");
		}

		echo PHP_EOL;
	}

	public function testGetCityNameAll()
	{
		$points = $this->mock->getAll();
		shuffle($points);
		$points_count = count($points);

		echo "Count: {$points_count}" . PHP_EOL;

		foreach ($points as $point)
		{
			$this->testGetCityNameConcrete($point['address']['place']);
		}
	}

	public function testGetCityNameChunk()
	{
		$chunk = $this->mock->getRandomChunk(10);

		foreach ($chunk as $point)
		{
			$this->testGetCityNameConcrete($point['address']['place']);
		}
	}
}
