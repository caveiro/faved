<?php

namespace Utils;

function groupTagsByParent($tags)
{
	$tags_by_parent = [];
	foreach ($tags as $tag_id => $tag) {
		$tags_by_parent[$tag['parent']][] = $tag_id;
	}
	return $tags_by_parent;
}


function getPinnedTags($tags)
{
	$tags = array_filter($tags, function ($tag) {
		return !empty($tag['pinned']);
	});
	return array_column($tags, 'id');
}

function getTagColors()
{
	return [
		'gray' => 'secondary',
		'green' => 'success',
		'red' => 'danger',
		'yellow' => 'warning',
		'aqua' => 'info',
		'white ' => 'light',
		'black' => 'dark',
	];
}

function findURLMatches($checked_url, $items, &$host_matches)
{
	$domain = parse_url($checked_url)['host'];
	if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $matches)) {
		$domain = $matches['domain'];
	}


	$url_matches = [];
	$host_matches = [];
	foreach ($items as $item) {
		if ($item['url'] === $checked_url) {
			$url_matches[] = $item;
		} elseif (str_contains($item['url'], $domain)) {
			$host_matches[] = $item;
		}
	}
	return $url_matches;
}
