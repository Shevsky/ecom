<?php

namespace Shevsky\Ecom\Persistence\Env;

interface IEnv
{
	/**
	 * @return string
	 */
	public function getPluginUrl();

	/**
	 * @return string
	 */
	public function getPluginPath();
}
