<?php

namespace Shevsky\Ecom\Api\Otpravka;

use waException;
use waNet;

class OtpravkaApi
{
	private $login;
	private $password;
	private $token;

	/**
	 * Api constructor.
	 * @param string $login
	 * @param string $password
	 * @param string $token
	 */
	public function __construct($login, $password, $token)
	{
		$this->login = $login;
		$this->password = $password;
		$this->token = $token;
	}

	/**
	 * @return string
	 */
	protected function getAuthorization()
	{
		return base64_encode($this->login . ':' . $this->password);
	}

	/**
	 * @return waNet
	 */
	private function getNet()
	{
		if (!isset($this->net))
		{
			$this->net = new waNet(
				array(
					'format' => waNet::FORMAT_JSON,
				), array(
					'Authorization' => 'AccessToken ' . $this->token,
					'X-User-Authorization' => 'Basic ' . $this->getAuthorization(),
				)
			);
		}

		return $this->net;
	}

	/**
	 * @param IMethod $method
	 * @return array
	 * @throws \Exception
	 */
	public function execute(IMethod $method)
	{
		\waLog::log('executing otpravka method: ' . $method->getUrl(), 'ecom.log');

		$net = $this->getNet();

		$url = Constants::URL . $method->getUrl();

		try
		{
			$result = $net->query($url, $method->content, $method->getMethod());

			return $result;
		}
		catch (waException $e)
		{
			throw new \Exception($e->getMessage());
		}
	}
}