<?php

namespace Framework;

use Exception;

class ServiceContainer
{
	protected static $bindings = [];
	protected static $instances = [];

	public static function bind($abstract, $init_callable)
	{
		self::$bindings[$abstract] = $init_callable;
	}

	public static function get($abstract)
	{
		if (isset(self::$instances[$abstract])) {
			return self::$instances[$abstract];
		}

		if (!isset(self::$bindings[$abstract])) {
			throw new Exception("No binding found for {$abstract}");
		}

		$init_callable = self::$bindings[$abstract];
		$instance = $init_callable();
		self::$instances[$abstract] = $instance;

		return $instance;
	}
}
