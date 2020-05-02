<?php

namespace Shevsky\Ecom\Services\PointsRegistry;

use JsonMachine\JsonMachine;
use Shevsky\Ecom\Services\OtpravkaApi\OtpravkaApi;

class PointsRegistry
{
	private $file;

	public function update(OtpravkaApi $otpravka_api)
	{
		$points_json = $otpravka_api->getPointsJson();
		$points_generator = JsonMachine::fromString($points_json)->getIterator();

		\waFiles::create($this->getPath());
		$file = fopen($this->getPath(), 'w');

		$points_count = 0;

		foreach ($points_generator as $item)
		{
			if (!empty($item))
			{
				fwrite($file, json_encode($item) . PHP_EOL);
				$points_count++;
			}
		}

		return $points_count;
	}

	/**
	 * @return \SplFileObject
	 */
	public function getFile()
	{
		if (!isset($this->file))
		{
			$this->file = new \SplFileObject($this->getPath());
		}

		return $this->file;
	}

	/**
	 * @param int $line
	 * @return void
	 */
	public function seek($line)
	{
		$this->getFile()->seek($line);
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	public function read()
	{
		$point_json = $this->getFile()->getCurrentLine();
		if (empty($point_json))
		{
			throw new PointsRegistryException(PointsRegistryException::TYPE_END_OF_REGISTRY);
		}

		$point = json_decode($point_json, true);

		if ($point === null || json_last_error() !== JSON_ERROR_NONE)
		{
			throw new \Exception('Не удалось прочитать строку с пунктом выдачи из реестра');
		}

		return $point;
	}

	/**
	 * @param int $start_line
	 * @param int $chunk_size
	 * @return \Generator
	 */
	public function getChunkIterator($start_line, $chunk_size)
	{
		for ($line = $start_line; $line < $chunk_size; $line++)
		{
			$this->getFile()->seek($line);

			$point_json = $this->getFile()->getCurrentLine();
			$point = \GuzzleHttp\json_decode($point_json, true);

			if ($point !== null)
			{
				yield $point;
			}
		}
	}

	/**
	 * @return string
	 */
	protected function getPath()
	{
		return \waConfig::get('wa_path_cache') . "/ecom/points-registry.csv";
	}
}
