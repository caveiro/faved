<?php

namespace Framework;

function renderPage($page_name, $layout_name, $data)
{
	extract($data);

	ob_start();
	require ROOT_DIR . "/views/pages/{$page_name}.php";
	$content = ob_get_clean();

	ob_start();
	require ROOT_DIR . "/views/layouts/{$layout_name}.php";
	return ob_get_clean();
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