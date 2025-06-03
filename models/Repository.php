<?php

namespace Models;

use Exception;
use PDO;

class Repository
{
	private $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	public function getTags()
	{
		$tags = [];
		$stmt = $this->pdo->query('SELECT * FROM tags ORDER BY title ASC');
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$tags[(int)$row['id']] = $row;
		}
		return $tags;
	}

	public function getItems()
	{
		$items_tags = $this->getItemsTags();

		$items = [];
		$stmt = $this->pdo->query('SELECT * FROM items ORDER BY id DESC');
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$items[(int)$row['id']] = array_merge($row, [
				'tags' => $items_tags[(int)$row['id']] ?? []
			]);
		}
		return $items;
	}

	/**
	 * Get items tags relation
	 */
	public function getItemsTags()
	{
		$items_tags = [];
		$stmt = $this->pdo->query('SELECT item_id, tag_id FROM items_tags ORDER BY tag_id ASC');
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$items_tags[(int)$row['item_id']][] = (int)$row['tag_id'];
		}
		return $items_tags;
	}

	public function updateItemTags(array $item_tags, int $item_id)
	{
		// 1. delete all tags relations
		$this->deleteItemTags($item_id);

		// 2. insert new tags relations
		$this->attachItemTags($item_tags, $item_id);
	}

	public function deleteItemTags(int $item_id)
	{
		$stmt = $this->pdo->prepare('DELETE FROM items_tags WHERE item_id = :item_id');
		$stmt->execute([':item_id' => $item_id]);
	}

	protected function attachItemTags(array $item_tags, int $item_id)
	{
		if (empty($item_tags)) {
			return;
		}

		$sqlData = [];

		foreach ($item_tags as $tag_id) {
			array_push($sqlData, $item_id, (int)$tag_id);
		}

		$sql = 'INSERT INTO items_tags (item_id, tag_id) VALUES ' . implode(',', array_fill(0, count($sqlData) / 2, '(?, ?)'));
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($sqlData);
	}

	public function createItem($title, $description, $url, $comments, $image)
	{
		$stmt = $this->pdo->prepare(
			'INSERT INTO items (title, description, url, comments, image, created_at)
    VALUES (:title, :description, :url, :comments, :image, :created_at)'
		);
		$stmt->execute([
			':title' => $title,
			':description' => $description,
			':url' => $url,
			':comments' => $comments,
			':image' => $image,
			':created_at' => date('Y-m-d H:i:s'),
		]);
		return $this->pdo->lastInsertId();
	}

	public function updateItem($title, $description, $url, $comments, $image, $item_id)
	{
		$stmt = $this->pdo->prepare(
			'UPDATE items SET title = :title, description = :description, url = :url, comments = :comments, image = :image, updated_at = :updated_at
    WHERE id = :id'
		);
		$result = $stmt->execute([
			':title' => $title,
			':description' => $description,
			':url' => $url,
			':comments' => $comments,
			':image' => $image,
			':updated_at' => date('Y-m-d H:i:s'),
			':id' => $item_id,
		]);

		return $result;
	}

	public function updateTag($tag_id, $title, $description, $parent_tag_id, $color, bool $pinned)
	{
		$stmt = $this->pdo->prepare(
			'UPDATE tags 
			SET title = :title, description = :description, parent = :parent, color = :color, pinned = :pinned, 
			updated_at = :updated_at
			WHERE id = :id'
		);

		$result = $stmt->execute([
			':title' => $title,
			':description' => $description,
			':parent' => $parent_tag_id,
			':color' => $color,
			':pinned' => (int)$pinned,
			':updated_at' => date('Y-m-d H:i:s'),
			':id' => $tag_id
		]);

		return $result;
	}

	public function deleteItemTag($tag_id)
	{
		$stmt = $this->pdo->prepare("DELETE FROM items_tags WHERE tag_id = :tag_id");

		$result = $stmt->execute([':tag_id' => $tag_id]);
		return $result;

	}

	public function deleteTag($tag_id)
	{
		$stmt = $this->pdo->prepare("DELETE FROM tags WHERE id = :tag_id");

		$result = $stmt->execute([':tag_id' => $tag_id]);
		return $result;

	}


	public function deleteItem($item_id)
	{
		$stmt = $this->pdo->prepare("DELETE FROM items WHERE id = :item_id");

		$result = $stmt->execute([':item_id' => $item_id]);
		return $result;

	}

	/**
	 * Check if database tables exist
	 *
	 * @return bool
	 */
	public function checkDatabaseExists()
	{
		try {
			$stmt = $this->pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='items'");
			return $stmt->fetch() !== false;
		} catch (Exception $e) {
			return false;
		}
	}

	/**
	 * Set up database tables
	 *
	 * @return bool
	 */
	public function setupDatabase()
	{
		try {
			// Create items table
			$this->pdo->exec('CREATE TABLE IF NOT EXISTS items (
				id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
				title TEXT NOT NULL,
				description TEXT NOT NULL,
				url TEXT NOT NULL,
				comments TEXT NOT NULL,
				image TEXT NOT NULL,
				created_at TEXT DEFAULT(NULL),
				updated_at TEXT DEFAULT(NULL)
			)');

			// Create tags table
			$this->pdo->exec('CREATE TABLE IF NOT EXISTS tags (
				id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
			   title TEXT NOT NULL,
			   description TEXT NOT NULL,
			   color TEXT NOT NULL,
			   parent INTEGER NOT NULL DEFAULT(0),
			   pinned INTEGER NOT NULL DEFAULT(0),
			   created_at TEXT DEFAULT(NULL),
			   updated_at TEXT DEFAULT(NULL)
			)');

			// Create items_tags relationship table
			$this->pdo->exec('CREATE TABLE IF NOT EXISTS items_tags (
				item_id INTEGER NOT NULL,
				tag_id INTEGER NOT NULL,
				PRIMARY KEY(item_id, tag_id) 
				FOREIGN KEY(item_id) REFERENCES items(id) 
				ON DELETE CASCADE 
				FOREIGN KEY(tag_id) REFERENCES tags(id) 
				ON DELETE CASCADE
			)');

			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}
