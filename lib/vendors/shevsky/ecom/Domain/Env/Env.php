<?php

namespace Shevsky\Ecom\Domain\Env;

use Shevsky\Ecom\Persistence\Env\IEnv;
use Shevsky\Ecom\Plugin;

class Env implements IEnv
{
	public $id = Plugin\Config::ID;

	/**
	 * @return string
	 */
	public function getPluginUrl()
	{
		return wa()->getRootUrl(true) . "wa-plugins/shipping/{$this->id}/";
	}

	/**
	 * @return string
	 */
	public function getPluginPath()
	{
		return wa()->getConfig()->getPath('plugins') . "/shipping/{$this->id}/";
	}
}
