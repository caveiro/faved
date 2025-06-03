<?php

namespace Models;

class TagCreator
{
	private $stmt;
	private $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
		$this->stmt = $pdo->prepare(
			'INSERT INTO tags (title, description, color, parent, pinned, created_at) 
			VALUES (:title, :description, :color, :parent, :pinned, :created_at)'
		);
	}

	public function createTag(string $tag_title, int $tag_parent)
	{
		$this->stmt->execute([
			':title' => $tag_title,
			':description' => '',
			':color' => 'gray',
			':parent' => $tag_parent,
			':pinned' => 0,
			':created_at' => date('Y-m-d H:i:s')
		]);

		return $this->pdo->lastInsertId();
	}
}