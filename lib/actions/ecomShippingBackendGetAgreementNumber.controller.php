<?php

use LapayGroup\RussianPost\Exceptions\RussianPostException;
use Shevsky\Ecom\Provider;

class ecomShippingBackendGetAgreementNumberController extends waJsonController
{
	public function execute()
	{
		$login = waRequest::post('login');
		$password = waRequest::post('password');
		$token = waRequest::post('token');

		try
		{
			$settings = Provider::getOtpravkaApi($login, $password, $token)->settings();

			if (!empty($settings['agreement-number']))
			{
				$this->response = $settings['agreement-number'];
				return;
			}

			throw new \Exception('Не удалось получить номер договора');
		}
		catch (RussianPostException $e)
		{
			throw new \waException($e->getMessage());
		}
		catch (\Exception $e)
		{
			$this->setError($e->getMessage());

			return;
		}
	}
}
