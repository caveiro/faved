<?php

namespace Framework\Middleware;

abstract class MiddlewareAbstract
{
	public function __construct(protected $next = null)
	{
	}

	abstract public function handle();
}