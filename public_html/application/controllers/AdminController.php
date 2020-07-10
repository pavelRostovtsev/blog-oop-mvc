<?php

namespace public_html\application\controllers;

use public_html\application\core\Controller;
use public_html\application\lib\Pagination;

class AdminController extends Controller 
{

	public function __construct($route) 
	{
		parent::__construct($route);
		$this->view->layout = 'admin';
	}

	public function loginAction() 
	{
		if (isset($_SESSION['admin'])) {
			$this->view->redirect('admin/add');
		}
		if (!empty($_POST)) {
			if (!$this->model->loginValidate($_POST)) {
				$this->view->message('error', $this->model->errorsReporting());
			}
			$_SESSION['admin'] = true;
			$this->view->location('admin/add');
		}
		$this->view->render('Вход');
	}

	public function addAction() 
	{
		if (!empty($_POST)) {
			if (!$this->model->postValidate($_POST, 'add')) {
				$this->view->message('error', $this->model->errorsReporting());
			}
			$id = $this->model->postAdd($_POST);
			$this->model->postUploadImage($_FILES['img']['tmp_name'],$id);
			$this->view->message('success', 'Пост добавлен');
		}
		$this->view->render('Добавить пост');
	}

	public function editAction() 
	{
		if (!$this->model->isPostExists($this->route['id'])) {
			$this->view->errorCode(404);
		}
		if (!empty($_POST)) {
			if (!$this->model->postValidate($_POST, 'edit')) {
				$this->view->message('error', $this->model->errorsReporting());
			}
			$this->model->postEdit($_POST, $this->route['id']);
			if ($_FILES['img']['tmp_name']) {
				$this->model->postUploadImage($_FILES['img']['tmp_name'], $this->route['id']);
			}
			$this->view->message('success', 'Сохранено');
		}
		$vars = [
			'data' => $this->model->postData($this->route['id'])[0],
		];
		$this->view->render('Редактировать пост', $vars);
	}

	public function deleteAction() 
	{
		if (!$this->model->isPostExists($this->route['id'])) {
			$this->view->errorCode(404);
		}
		$this->model->postDelete($this->route['id']);
		$this->view->redirect('admin/posts');
	}

	public function logoutAction() 
	{
		unset($_SESSION['admin']);
		$this->view->redirect('admin/login');
	}

	public function postsAction() 
	{
		$pagination = new Pagination($this->route, $this->model->postsCount());
		$vars = [
			'pagination' => $pagination->get(),
			'list' => $this->model->postsList($this->route),
		];
		$this->view->render('Посты', $vars);
	}
}