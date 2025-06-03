<?php

namespace Framework\Exceptions;

use Exception;

class NotFoundException extends Exception
{
	protected $message = 'The requested resource was not found.';

	public function __construct($message = null, $code = 0, ?Exception $previous = null)
	{
		if ($message !== null) {
			$this->message = $message;
		}

		parent::__construct($this->message, $code, $previous);
	}
}
