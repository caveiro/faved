<?php

namespace Utils;

use Framework\ServiceContainer;
use Framework\UrlBuilder;

class TagRenderer
{
	private $url_builder;

	public function __construct(protected array $all_tags, protected ?string $selected_tag, protected array $colors)
	{
		$this->url_builder = ServiceContainer::get(UrlBuilder::class);
	}

	public function render($tag_id)
	{
		$tag = $this->all_tags[$tag_id];

		$is_selected = $this->selected_tag == $tag_id;
		$tag_color = $tag['color'] ?? 'gray';
		$label_class = $is_selected ? 'text-bg-primary' : "text-bg-{$this->colors[$tag_color]}";

		$output = '<span class="tag-path badge ' . $label_class . '">';
		$output .= $this->renderSegment($tag);
		$output .= '</span> ';
		return $output;
	}

	protected function renderSegment($tag)
	{
		$output = '';
		if (!empty($tag['parent'])) {
			$parent_tag = $this->all_tags[$tag['parent']];
			$output .= $this->renderSegment($parent_tag);
			$output .= '/';
		}
		$output .= '<a href="' . $this->url_builder->build('/', ['tag' => $tag['id']]) . '" class="tag-segment">' . htmlspecialchars($tag['title']) . '</a>';
		return $output;
	}
}
