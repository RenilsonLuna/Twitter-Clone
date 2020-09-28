<?php

namespace App\Controllers;

// recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action 
{
	public function autenticar()
	{
		session_start();
		if (isset($_SESSION['id']) && isset($_SESSION['nome'])) {
			header("Location: /timeline");
		}else{
			return true;
		}
	}
	public function index()
	{
		$this->autenticar();
		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
		$this->render('index');
	}
	public function inscreverse()
	{
		$this->autenticar();
		$this->view->erroCadastro = false;
		$this->view->usuario = array('nome' => '', 'email' => '');
		$this->render('inscreverse');
	}
	
	public function registrar()
	{
		$this->autenticar();
		// receber dados do formulario
		if (!isset($_POST['nome']) || !isset($_POST['email']) || !isset($_POST['senha'])) {
			$_POST['nome'] = '';
			$_POST['email'] = '';
			$_POST['senha'] = '';
		}
		$usuario = Container::getModel('Usuario');
		$usuario->__set('nome', $_POST['nome']);
		$usuario->__set('email', $_POST['email']);
		$usuario->__set('senha', md5($_POST['senha']));

		if ($usuario->validarCadastro() == true && count($usuario->getByEmail()) == 0) {
			$usuario->salvar(); 
		}else{
			$this->view->usuario = array('nome' => $_POST['nome'], 'email' => $_POST['email']);

			$this->view->erroCadastro = true;
			$this->render('inscreverse');
		}
		$this->render('cadastro');
	}
}