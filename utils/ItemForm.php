<?php

namespace Utils;

use Framework\ServiceContainer;
use Framework\UrlBuilder;

class ItemForm
{
	public string $action;
	public string $heading;
	public string $tab_name;

	public ?int $item_id;
	public string $url;
	public string $title;
	public string $description;
	public string $comments;
	public string $image;
	public array $tags;
	public ?string $created_at;
	public ?string $updated_at;
	public bool $from_bookmarklet;


	public function __construct(array $form_data, bool $from_bookmarklet)
	{
		$url_builder = ServiceContainer::get(UrlBuilder::class);

		$item_id = $form_data['id'] ?? null;
		if ($item_id) {
			$this->action = $url_builder->build('/item', ['item-id' => $item_id]);
			$this->heading = 'Edit item';
			$this->tab_name = 'Edit existing item';
		} else {
			$this->action = $url_builder->build('/item');
			$this->heading = 'Create item';
			$this->tab_name = 'Add new item';
		}

		$this->item_id = $form_data['id'] ?? null;
		$this->url = $form_data['url'] ?? '';
		$this->title = $form_data['title'] ?? '';
		$this->description = $form_data['description'] ?? '';
		$this->comments = $form_data['comments'] ?? '';
		$this->image = $form_data['image'] ?? '';
		$this->tags = $form_data['tags'] ?? [];
		$this->created_at = $form_data['created_at'] ?? null;
		$this->updated_at = $form_data['updated_at'] ?? null;

		$this->from_bookmarklet = $from_bookmarklet;
	}
}