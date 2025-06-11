<?php

class Config
{
	protected const DB_NAME_DEFAULT = 'faved';
	protected const STORAGE_DIR = ROOT_DIR . '/storage';

	public static function getDBPath()
	{
		$db_name = $_SERVER['DB_NAME'] ?? self::DB_NAME_DEFAULT;
		$db_path = sprintf('%s/%s.db', self::STORAGE_DIR, $db_name);
		return $db_path;
	}
}