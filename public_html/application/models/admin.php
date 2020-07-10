<?php 

namespace public_html\application\models;
use public_html\application\core\DB;
use public_html\application\core\DBDriver;
use public_html\application\core\Model;

use Imagick;

class Admin extends Model 
{

	private $error;
	private $dbDriver;

	public function __construct($dbDriver) 
	{
		$this->dbDriver = new DBDriver(DB::getConnect());
	}
	public function getDBDriver()
	{
		return $this->dbDriver;
	}

	public function loginValidate($post) {
		$config = require 'public_html/application/config/admin.php';
		if ($config['login'] != $post['login'] or $config['password'] != $post['password']) {
			$this->errorsRecording('Логин или пароль указан неверно');
			return false;
		}
		return true;
	}

	public function postValidate($post, $type) 
	{
		$nameLen = iconv_strlen($post['name']);
		$descriptionLen = iconv_strlen($post['description']);
		$textLen = iconv_strlen($post['text']);
		if ($nameLen < 3 or $nameLen > 100) {
			$this->errorsRecording('Название должно содержать от 3 до 100 символов');
			return false;
		} elseif ($descriptionLen < 3 or $descriptionLen > 100) {
			$this->errorsRecording('Описание должно содержать от 3 до 100 символов');
			return false;
		} elseif ($textLen < 10 or $textLen > 5000) {
			$this->errorsRecording('Текс должен содержать от 3 до 100 символов');
			return false;
		}
		if (empty($_FILES['img']['tmp_name']) and $type == 'add') {
			$this->errorsRecording('изображение не выбранно');
			return false;
		}
		return true;
	}

	public function postAdd($post) 
	{
		
		$params = [
			'name' => $post['name'],
			'description' => $post['description'],
			'text' => $post['text'],
		];
		return $this->getDBDriver()->insert('posts',$params);

	}

	public function postEdit($post, $id) 
	{		
		$params = [
			'name' => $post['name'],
			'description' => $post['description'],
			'text' => $post['text'],
		];
		$this->getDBDriver()->update('posts',$params,
			[
				'id',
				'=',
				$id
			]);
	}

	public function postUploadImage($path,$id) 
	{
		$img = new Imagick($path);
		$img->cropThumbnailImage(1080, 600);
		$img->setImageCompressionQuality(80);
		$img->writeImage(__DIR__.'/../../public/materials/'.$id.'.jpg');
	}

	
	public function postDelete($id) {		
		$where = [
			'id',
			'=',
			$id
		];
		$this->getDBDriver()->delete('posts',$where);
		unlink(__DIR__.'/../../public/materials/'.$id.'.jpg');
	}

	

}