<?php

namespace Shevsky\Ecom\Services\OtpravkaApi;

use GuzzleHttp\Client;
use LapayGroup\RussianPost\Providers;

class OtpravkaApi extends Providers\OtpravkaApi
{
	protected $protected_token;
	protected $protected_key;

	public function __construct($config, $timeout = 60)
	{
		parent::__construct($config, $timeout);

		$this->protected_token = $config['auth']['otpravka']['token'];
		$this->protected_key = $config['auth']['otpravka']['key'];
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public function getPointsJson()
	{
		$client = new Client(
			[
				'base_uri' => 'https://otpravka-api.pochta.ru/' . self::VERSION . '/',
				'headers' => [
					'Authorization' => 'AccessToken ' . $this->protected_token,
					'X-User-Authorization' => 'Basic ' . $this->protected_key,
					'Content-Type' => 'application/json',
					'Accept' => 'application/json;charset=UTF-8',
				],
				'timeout' => 60,
				'http_errors' => false,
			]
		);

		$method = 'delivery-point/findAll';

		$response = $client->get($method, ['query' => []]);

		if (!in_array($response->getStatusCode(), [200, 400, 404, 407]))
		{
			throw new \Exception(
				'Получен некорректный код ответа при попытке получения списка пунктов выдачи: '
				. $response->getStatusCode()
			);
		}

		$response_contents = $response->getBody()->getContents();
		if (empty($response_contents))
		{
			throw new \Exception('Получен пустой ответ при попытке получения списка пунктов выдачи');
		}

		return $response_contents;
	}
}
