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
				[
					'format' => waNet::FORMAT_JSON,
				], [
					'Authorization' => 'AccessToken ' . $this->token,
					'X-User-Authorization' => 'Basic ' . $this->getAuthorization(),
				]
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
		$net = $this->getNet();

		$url = Constants::URL . $method->getUrl();

		try
		{
			$result = $net->query($url, $method->content, $method->getMethod());

			// \waFiles::write(\wa()->getDataPath('pochta-response.json'), json_encode($result, JSON_UNESCAPED_UNICODE));
			// for test mock

			return $result;
		}
		catch (waException $e)
		{
			// TODO log this?

			throw new \Exception($e->getMessage(), $e->getCode(), $e);
		}
	}
}
