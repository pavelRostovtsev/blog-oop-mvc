<?php 

namespace public_html\application\core;

// use public_html\application\core\DB;
// use public_html\application\core\DBDriver;

abstract class Model 
{
	private $db;
	private $dbDriver;
	private $errors;

	public function __construct()
	{
		$this->db = DB::getConnect();
		$this->dbDriver = new DBDriver($this->getDb()); 

	}

	public function getDb()
	{
		return $this->db;
	}

	public function getDBDriver()
	{
		return $this->dbDriver;
	}



	public function errorsRecording($error)
	{
		$this->errors = $error;
	}
	
	public function errorsReporting()
	{
		return $this->errors;
	}

	public function postsCount() {

		$this->getDBDriver()->column('SELECT COUNT(id) FROM posts');		
	}

	public function postsList($route) {

		$max = 10;
		$params = [
			'max' => $max,
			'start' => ((($route['page'] ?? 1) - 1) * $max),
		];
		return $this->getDBDriver()->select('SELECT * FROM posts ORDER BY id DESC LIMIT :start, :max', $params);
	}

	public function isPostExists($id) {
		$db = DB::getConnect();
		$dbDriver = new DBDriver($db);		
		$params = [
			'id' => $id,
		];
		return $this->getDBDriver()->column('SELECT id FROM posts WHERE id = :id',$params);
		
	}
	public function postData($id) {
		$db = DB::getConnect();
		$dbDriver = new DBDriver($db);		
		$params = [
			'id' => $id,
		];
		return $this->getDBDriver()->select('SELECT * FROM posts WHERE id = :id', $params);
	}

	
}