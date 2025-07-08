<?php

namespace Framework;


use Framework\Responses\PageResponse;
use Framework\Responses\RedirectResponse;

function page($page_name, $data)
{
	return new PageResponse($page_name, $data);
}

function redirect($location, $code = 303)
{
	return new RedirectResponse($location, $code);
}


function flattenArray(array $array, string $prefix = ''): array
{
	$result = [];

	foreach ($array as $key => $value) {
		$new_key = $prefix === '' ? $key : $prefix . $key;

		if (is_array($value)) {
			$result += flattenArray($value, $new_key); // Recursive call
		} else {
			$result[$new_key] = $value;
		}
	}

	return $result;
}