<?php

namespace Framework;

use Framework\Responses\ResponseInterface;

interface ControllerInterface
{
	public function __invoke() : ResponseInterface;
}