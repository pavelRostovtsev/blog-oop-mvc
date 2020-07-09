<?php 

namespace public_html\application\models;

use public_html\application\core\Model;
use public_html\application\core\DB;
use public_html\application\core\DBDriver;
class Main extends Model {



	public $error;

	public function contactValidate($post) {
		$nameLen = iconv_strlen($post['name']);
		$textLen = iconv_strlen($post['text']);
		if ($nameLen < 3 or $nameLen > 20) {
			$this->errorsRecording('Имя должно содержать от 3 до 20 символов');
			return false;
		} elseif (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->errorsRecording('E-mail указан неверно');
			return false;
		} elseif ($textLen < 10 or $textLen > 500) {
			$this->errorsRecording('Сообщение должно содержать от 10 до 500 символов');
			return false;
		}
		return true;
	}


	

}