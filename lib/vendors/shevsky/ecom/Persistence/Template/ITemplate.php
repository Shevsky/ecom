<?php

namespace Shevsky\Ecom\Persistence\Template;

interface ITemplate
{
	/**
	 * @param mixed[] $vars
	 */
	public function assign(array $vars = []);

	/**
	 * @return string
	 */
	public function render();
}
