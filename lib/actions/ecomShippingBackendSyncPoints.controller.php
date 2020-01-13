<?php

use Shevsky\Ecom\Api\Otpravka\MethodDeliveryPointGetAll;
use Shevsky\Ecom\Api\Otpravka\OtpravkaApi;
use Shevsky\Ecom\Util\PointFormatter;

class ecomShippingBackendSyncPointsController extends waLongActionController
{
	private $otpravka_api;
	private $points_model;

	const CHUNK_SIZE = 100;

	public function __construct()
	{
		$this->points_model = new ecomShippingPointsModel();
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

			'points' => null,
			'points_count' => -1,
			'offset' => -1,

			'error' => null,
		];

		try
		{
			$points = array_values(
				$this->getOtpravkaApi()->execute(
					new MethodDeliveryPointGetAll()
				)
			);
			$points_count = count($points);
		}
		catch (\Exception $e)
		{
			$this->data = [
				'error' => $e->getMessage(),
			];

			return;
		}

		$this->points_model->truncate();

		$this->data = [
			'points' => $points,
			'points_count' => $points_count,
		];
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
		if (($this->data['points']) === null || !is_array($this->data['points']))
		{
			$this->data['error'] = 'Не удалось получить список пунктов выдачи';
			return false;
		}

		$points = &$this->data['points'];
		$offset = &$this->data['offset'];

		$chunk = array_slice($points, $offset, self::CHUNK_SIZE);

		foreach ($chunk as $point)
		{
			try
			{
				$data = PointFormatter::getPointDbDataFromApiData($point);
			}
			catch (\Exception $e)
			{
				continue;
			}

			$this->points_model->insert($data);
			$offset++;
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
				'offset' => ifset($this->data, 'offset', -1),
				'process_id' => $this->processId,
				'ready' => $this->isDone(),
				'error' => ifset($this->data, 'error', null)
			]
		);
	}

	/**
	 * @return bool
	 */
	private function isCleanup() {
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
	 * @return OtpravkaApi
	 * @throws Exception
	 */
	private function getOtpravkaApi()
	{
		if (!isset($this->otpravka_api))
		{
			list($login, $password, $token) = $this->getApiData();

			if (!$login || !$password || !$token)
			{
				throw new \Exception('Параметры для API сервиса Отправка не указаны');
			}

			$this->otpravka_api = new OtpravkaApi($login, $password, $token);
		}

		return $this->otpravka_api;
	}
}
