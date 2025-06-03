<?php

namespace Utils;

class TagData
{

	public function __construct(protected array $tags_by_parent, protected array $all_tags, protected array $excluded_tag_ids)
	{

	}

	public function build($parent_id): array
	{

		$tags_pool = $this->tags_by_parent[$parent_id] ?? [];

		static $parent_tags = [];
		$array = [];

		foreach ($tags_pool as $tag_id) {
			if (in_array($tag_id, $this->excluded_tag_ids)) {
				continue;
			}

			$tag_segments = array_merge($parent_tags, [$this->all_tags[$tag_id]['title']]);
			$array[] = [
				'id' => $tag_id,
				'text' => implode(' / ', $tag_segments),
			];

			if (isset($this->tags_by_parent[$tag_id])) {
				$parent_tags[] = $this->all_tags[$tag_id]['title'];
				$array = array_merge($array, $this->build($tag_id));
				array_pop($parent_tags);
			}
		}

		return $array;
	}
}