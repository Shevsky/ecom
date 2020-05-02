<?php

use Shevsky\Ecom\Provider;
use Shevsky\Ecom\Services\OtpravkaApi\OtpravkaApi;
use Shevsky\Ecom\Services\PointsRegistry\PointsRegistry;
use Shevsky\Ecom\Services\PointsRegistry\PointsRegistryException;
use Shevsky\Ecom\Util\PointFormatter;

class ecomShippingBackendSyncPointsController extends waLongActionController
{
	private $otpravka_api;
	private $points_model;
	private $points_registry;

	/**
	 * @throws waDbException
	 * @throws waException
	 */
	public function __construct()
	{
		$this->points_model = new ecomShippingPointsModel();
		$this->points_registry = new PointsRegistry();
	}

	/**
	 * Initializes new process.
	 * Runs inside a transaction ($this->data and $this->fd are accessible).
	 */
	protected function init()
	{
		list($login, $password, $token) = $this->getApiDataFromRequest();

		$this->data = [
			'login' => $login,
			'password' => $password,
			'token' => $token,

			'chunk_size' => $this->getChunkSizeFromRequest(),

			'points_count' => -1,
			'processed_count' => -1,
			'offset' => -1,

			'warnings' => [],
			'error' => null,
		];

		try
		{
			$points_count = $this->points_registry->update($this->getOtpravkaApi());
		}
		catch (\Exception $e)
		{
			$this->data = [
				'error' => $e->getMessage(),
			];

			return;
		}

		$this->points_model->truncate();

		$this->data['points_count'] = $points_count;
	}

	/**
	 * Checks if it's ok to initialize a new process.
	 * @return boolean true if initialization can start
	 */
	protected function preInit()
	{
		list($login, $password, $token) = $this->getApiDataFromRequest();

		if (!$login || !$password || !$token)
		{
			return false;
		}

		return true;
	}

	/**
	 * Checks if there is any more work for $this->step() to do.
	 * Runs inside a transaction ($this->data and $this->fd are accessible).
	 *
	 * $this->getStorage() session is already closed.
	 *
	 * @return boolean whether all the work is done
	 */
	protected function isDone()
	{
		return $this->data['offset'] >= $this->data['points_count'];
	}

	/**
	 * Performs a small piece of work.
	 * Runs inside a transaction ($this->data and $this->fd are accessible).
	 * Should never take longer than 3-5 seconds (10-15% of max_execution_time).
	 * It is safe to make very short steps: they are batched into longer packs between saves.
	 *
	 * $this->getStorage() session is already closed.
	 * @return boolean false to end this Runner and call info(); true to continue.
	 */
	protected function step()
	{
		$offset = &$this->data['offset'];
		if ($offset === null || $offset === -1)
		{
			$offset = 0;
		}
		$processed_count = &$this->data['processed_count'];
		if ($processed_count === null || $processed_count === -1)
		{
			$processed_count = 0;
		}
		$warnings = &$this->data['warnings'];
		if ($warnings === null || !is_array($warnings))
		{
			$warnings = [];
		}

		$chunk_size = $this->getChunkSize();

		for ($chunk_counter = 0; $chunk_counter < $chunk_size; $chunk_counter++)
		{
			$this->points_registry->seek($offset);
			$offset++;

			try
			{
				$point = $this->points_registry->read();
				$data = PointFormatter::getPointDbDataFromApiData($point);
			}
			catch (\Exception $e)
			{
				if ($e instanceof PointsRegistryException && $e->type === PointsRegistryException::TYPE_END_OF_REGISTRY)
				{
					$offset = $this->data['points_count'];
					$processed_count = $this->data['points_count'];
					break;
				}

				$warnings[] = $e->getMessage();
				continue;
			}

			$processed_count++;

			$this->points_model->insert($data);
		}
	}

	/**
	 * Called when $this->isDone() is true
	 * $this->data is read-only, $this->fd is not available.
	 *
	 * $this->getStorage() session is already closed.
	 *
	 * @param $filename string full path to resulting file
	 * @return boolean true to delete all process files; false to be able to access process again.
	 */
	protected function finish($filename)
	{
		$this->info();

		return $this->isCleanup();
	}

	/** Called by a Messenger when the Runner is still alive, or when a Runner
	 * exited voluntarily, but isDone() is still false.
	 *
	 * This function must send $this->process_id to browser to allow user to continue.
	 *
	 * $this->data is read-only. $this->fd is not available.
	 */
	protected function info()
	{
		echo json_encode(
			[
				'points_count' => ifset($this->data, 'points_count', -1),
				'warnings' => ifset($this->data, 'warnings', []),
				'offset' => ifset($this->data, 'offset', -1),
				'processed_count' => ifset($this->data, 'processed_count', -1),
				'process_id' => $this->processId,
				'ready' => $this->isDone(),
				'error' => ifset($this->data, 'error', null),
			]
		);
	}

	/**
	 * @return bool
	 */
	private function isCleanup()
	{
		return !!waRequest::post('cleanup');
	}

	/**
	 * @return string[]
	 */
	private function getApiDataFromRequest()
	{
		$login = waRequest::post('login');
		$password = waRequest::post('password');
		$token = waRequest::post('token');

		return [$login, $password, $token];
	}

	/**
	 * @return string[]
	 */
	private function getApiData()
	{
		return [$this->data['login'], $this->data['password'], $this->data['token']];
	}

	/**
	 * @return int
	 */
	private function getChunkSizeFromRequest()
	{
		return (int)waRequest::post('chunk_size', 100, 'int');
	}

	/**
	 * @return int
	 */
	private function getChunkSize()
	{
		return (int)$this->data['chunk_size'];
	}

	/**
	 * @return OtpravkaApi
	 * @throws \Exception
	 */
	private function getOtpravkaApi()
	{
		if (!isset($this->otpravka_api))
		{
			list($login, $password, $token) = $this->getApiData();

			$this->otpravka_api = Provider::getOtpravkaApi($login, $password, $token);
		}

		return $this->otpravka_api;
	}
}
