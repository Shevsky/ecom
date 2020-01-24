<?php

namespace Shevsky\Ecom\Plugin;

/**
 * @mixin \ecomShipping
 */
trait ShopScript
{
	/**
	 * @return bool
	 */
	protected function isInstalledShopScript8()
	{
		try
		{
			$version = wa()->getVersion('shop');
		}
		catch (\Exception $e)
		{
			return false;
		}

		return $version >= '8.0';
	}

	/**
	 * @return bool
	 */
	protected function isShopScript()
	{
		return $this->app_id === 'shop';
	}

	/**
	 * @return bool
	 */
	protected function isShopScript8Checkout()
	{
		return $this->isShopScript() && $this->isInstalledShopScript8() && \waRequest::param('module') === 'order'
			&& \waRequest::param('action') === 'calculate';
	}
}
