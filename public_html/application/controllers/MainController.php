<?php

namespace public_html\application\controllers;

use public_html\application\core\Controller;
use public_html\application\lib\Pagination;

class MainController extends Controller {

	public function indexAction() {
		$pagination = new Pagination($this->route, 10);
		$vars = [
			'pagination' => $pagination->get(),
			'list' => $this->model->postsList($this->route),
		];
		$this->view->render('Главная страница', $vars);
	}

	public function aboutAction() {
		$this->view->render('Обо мне');
	}

	public function contactAction() {
		if (!empty($_POST)) {
			if (!$this->model->contactValidate($_POST)) {
				$this->view->message('error', $this->model->errorsReporting());
			}
			mail('titef@p33.org', 'Сообщение из блога', $_POST['name'].'|'.$_POST['email'].'|'.$_POST['text']);
			$this->view->message('success', 'Сообщение отправлено Администратору');
		}
		$this->view->render('Контакты');
	}

	public function postAction() {
		if (!$this->model->isPostExists($this->route['id'])) {
			$this->view->errorCode(404);
		}
		$vars = [
			'data' => $this->model->postData($this->route['id'])[0],
		];
		$this->view->render('Пост', $vars);
	}

}