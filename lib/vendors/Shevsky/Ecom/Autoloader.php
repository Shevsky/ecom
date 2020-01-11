<?php

namespace Shevsky\Ecom;

class Autoloader
{
	private static $namespace = ['Shevsky', 'Ecom'];
	private static $registered;

	/**
	 * @param bool $prepend
	 */
	public static function register($prepend = false)
	{
		if (self::$registered === true)
		{
			return;
		}
		spl_autoload_register(
			[__CLASS__, 'autoload'],
			true,
			$prepend
		);
		self::$registered = true;
	}

	/**
	 * @param string[] $classname_parts
	 * @return bool
	 */
	private static function verifyRootNamespace(&$classname_parts)
	{
		$root_namespace = self::$namespace;
		if (is_string(self::$namespace))
		{
			$root_namespace = [$root_namespace];
		}
		foreach ($root_namespace as $root_namespace_part)
		{
			$namespace_part = array_shift($classname_parts);
			if ($namespace_part !== $root_namespace_part)
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * @param string $classname_part
	 * @return string
	 */
	private static function convertClassnamePart($classname_part)
	{
		$classname_part = preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $classname_part);
		$classname_part = strtolower($classname_part);

		return $classname_part;
	}

	/**
	 * @param string $full_classname
	 */
	private static function autoload($full_classname)
	{
		$classname_parts = explode('\\', $full_classname);
		if (count($classname_parts) < 2)
		{
			return;
		}
		if (!self::verifyRootNamespace($classname_parts))
		{
			return;
		}
		$classname = array_pop($classname_parts);
		$path_parts = [__DIR__];
		$path_parts = array_merge($path_parts, array_map([__CLASS__, 'convertClassnamePart'], $classname_parts));
		$path_parts[] = "{$classname}.php";
		$path = implode('/', $path_parts);
		if (file_exists($path))
		{
			require_once $path;
		}
		else
		{
			echo "File not found: {$path}";
		}
	}
}
