<?php

namespace Utils;

use Framework\ServiceContainer;
use Framework\UrlBuilder;

class TagList
{
	private $url_builder;

	public function __construct(protected array $tags_by_parent, protected array $all_tags, protected ?string $selected_tag, protected array $excluded_tag_ids)
	{
		$this->url_builder = ServiceContainer::get(UrlBuilder::class);
	}

	public function render($tags_pool)
	{
		$output = '';

		foreach ($tags_pool as $tag_id) {
			if (in_array($tag_id, $this->excluded_tag_ids)) {
				continue;
			}

			$tag = $this->all_tags[$tag_id];

			$is_selected = $this->selected_tag == $tag_id;
			$tag_color = $tag['color'] ?? 'gray';
			$colors = getTagColors();

			$label_class = $is_selected ? 'text-bg-primary' : "text-bg-{$colors[$tag_color]}";

			$tag_description = $tag['description'];
			$html_attrs = '';
			$description_icon = '';
			if (!empty($tag_description)) {
				$html_attrs = 'data-bs-container="body" data-bs-toggle="popover" data-bs-placement="right" data-bs-trigger="hover" data-bs-html="true"  data-bs-content="' . nl2br( htmlspecialchars($tag_description)) . '"';
				$description_icon = ' <i class="bi bi-sticky"></i>';
			}

			$pinned_icon = '';
			if (isset($tag['pinned']) && $tag['pinned']) {
				$pinned_icon = ' <i class="bi bi-pin"></i>';
			}

			$tag_title = $tag['title'];

			$output .= '<li class="tag tag' . $tag_id . '">'
				. '<div class="tag-container">'
					. '<a href="' . $this->url_builder->build('/', ['tag' => $tag_id]) . '">'
						. '<span ' . $html_attrs . ' class="badge ' . $label_class . '">'
							. htmlspecialchars($tag_title)
							. $description_icon
							. $pinned_icon
						. '</span>'
					. '</a>'
					. ' <a class="tag-edit-button" href="' . $this->url_builder->build('/tag', ['tag-id' => $tag_id]) . '">'
						. '<i class="bi bi-pencil-square"></i>'
					. '</a>'
				. '</div>';

			if (isset($this->tags_by_parent[$tag_id])) {
				$output .= '<ul class="sublevel">'
					. $this->render($this->tags_by_parent[$tag_id])
					. '</ul>';
			}
			$output .= '</li>';
		}

		return $output;
	}
}
