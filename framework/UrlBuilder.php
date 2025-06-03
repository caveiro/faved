<?php

namespace Framework;
class UrlBuilder
{
	private $base_url;

	public function __construct($base_url = '')
	{
		$this->base_url = $base_url;
	}

	public function build($route, array $params = [])
	{
		$url = $this->base_url . '?route=' . $route;

		foreach ($params as $key => $value) {
			$url .= '&' . urlencode($key) . '=' . urlencode($value);
		}

		return $url;
	}
}
