<?php

namespace Shevsky\Ecom\Domain\Template;

use Shevsky\Ecom\Context;
use Shevsky\Ecom\Persistence\Template\ITemplate;

abstract class ViewTemplate implements ITemplate
{
	private $view;

	/**
	 * @param mixed[] $vars
	 */
	public function assign(array $vars = [])
	{
		$this->getView()->assign($vars);
	}

	/**
	 * @return string
	 */
	public function render()
	{
		if (!$this->isTemplateExists())
		{
			return '';
		}

		$assets = [
			'css' => [],
			'js' => [],
		];
		foreach ($this->getCss() as $css)
		{
			if ($this->isCssExists($css))
			{
				$assets['css'][] = $this->getCssUrl($css);
			}
		}
		foreach ($this->getJs() as $js)
		{
			if ($this->isJsExists($js))
			{
				$assets['js'][] = $this->getJsUrl($js);
			}
		}

		$this->assign([
			'assets' => $assets
		]);

		return $this->fetch($this->getTemplatePath());
	}

	/**
	 * @return string
	 */
	protected function getName()
	{
		return '';
	}

	/**
	 * @return string
	 */
	protected function getTemplatePath()
	{
		return Context::getInstance()->env->getPluginPath() . "templates/{$this->getName()}.html";
	}

	/**
	 * @return string[]
	 */
	protected function getJs()
	{
		return [];
	}

	/**
	 * @return string[]
	 */
	protected function getCss()
	{
		return [];
	}

	/**
	 * @param string $template
	 * @return string
	 */
	protected function fetch($template)
	{
		return $this->getView()->fetch($template);
	}

	/**
	 * @param string $file
	 * @return string
	 */
	private function getPath($file)
	{
		return Context::getInstance()->env->getPluginPath() . $file;
	}

	/**
	 * @param string $file
	 * @return string
	 */
	private function getUrl($file)
	{
		return Context::getInstance()->env->getPluginUrl() . $file;
	}

	/**
	 * @param string $js
	 * @return string
	 */
	private function getJsUrl($js)
	{
		return $this->getUrl("js/{$js}.js");
	}

	/**
	 * @param string $css
	 * @return string
	 */
	private function getCssUrl($css)
	{
		return $this->getUrl("css/{$css}.css");
	}

	/**
	 * @return bool
	 */
	private function isTemplateExists()
	{
		return file_exists($this->getTemplatePath());
	}

	/**
	 * @param string $js
	 * @return bool
	 */
	private function isJsExists($js)
	{
		return file_exists($this->getPath("js/{$js}.js"));
	}

	/**
	 * @param string $css
	 * @return bool
	 */
	private function isCssExists($css)
	{
		return file_exists($this->getPath("css/{$css}.css"));
	}

	/**
	 * @return \waSmarty3View
	 */
	private function getView()
	{
		if (!isset($this->view))
		{
			$this->view = new \waSmarty3View(wa());
		}

		return $this->view;
	}
}
