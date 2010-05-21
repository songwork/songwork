<?php
# Singleton Registry.  requires function __autoload($class_name) from init.php
# Right now used for database connections but could be used for templates or anything Singleton-ish.
class Get
	{
	static function db($code, $cache=true)
		{
		static $objects;
		if(!is_array($objects)) { $objects = array(); }
		$key = ($cache) ? $code : uniqid();
		if(isset($objects[$key]))
			{
			return $objects[$key];
			}
		$dbname = (defined('WE_ARE_TESTING')) ? $code . '_test' : $code;
		switch($code)
			{
			default:
				require_once 'PgDB.php';
				$objects[$key] = new PgDB($dbname, $code);
				break;
			}
		return $objects[$key];
		}
	}
?>
