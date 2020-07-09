<?php

namespace public_html\application\core;
use PDO;
Class DB 
{
	private static $instance;

	public static function getConnect() {
		if (self::$instance === null) {
		 	self::$instance = self::getPDO();
		 } 
		 return self::$instance;
	}

	private static function getPDO() {
		$DBConfig = require 'public_html/application/config/DBConfig.php';
		return new PDO("mysql:host=" . $DBConfig['host'] . "; dbname=". $DBConfig['dbname'] . "; charset=UTF8", $DBConfig['user'], $DBConfig['password']);
	}
}


