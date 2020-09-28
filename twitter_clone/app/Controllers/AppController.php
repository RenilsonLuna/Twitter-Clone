<?php

namespace App\Controllers;

// recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action
{
	public function validar()
	{
		session_start();
		if (isset($_SESSION['id']) && isset($_SESSION['nome'])) {
			return true;
		}else{
			header("Location: /");
		}
	}
	public function timeline()
	{
		$this->validar();
		
		$tweet = Container::getModel('tweet');
		$tweet->__set('id_usuario', $_SESSION['id']);
		$this->view->tweets = $tweet->getAll();
		
		$usuario = Container::getModel('usuario');
		$usuario->__set('id', $_SESSION['id']);
		$this->view->infoUser = $usuario->getInfoUser();
		$this->view->totalTweets = $usuario->totalTweets();
		$this->view->totalSeguidores = $usuario->totalSeguidores();
		$this->view->totalSeguindo = $usuario->totalSeguindo();

		// renderizando view
		$this->render('timeline');
	}

	public function tweet()
	{
		$this->validar();
		$tweet = Container::getModel('tweet');
		$tweet->__set('id_usuario', $_SESSION['id']);
		$tweet->__set('tweet', $_POST['tweet']);
		$tweet->salvar();
		header('Location: /timeline');
	}

	public function seguir()
	{
		// validando acesso à página
		$this->validar();
		
		$q = isset($_GET['q']) ? $_GET['q'] : '';
		$this->view->usuarios = array();
		if ($q != '') {	
			$usuario = Container::getModel('usuario');
			$usuario->__set('nome', $q);
			$usuario->__set('id', $_SESSION['id']);
			$usuarios = $usuario->getAll();
			$this->view->usuarios = $usuarios;
		}
		

		// renderizando pagina seguir
		$this->render('seguir');
	}

	public function acao()
	{
		$this->validar();

		$acao = isset($_GET['acao']) ? $_GET['acao'] : '';
		$usuarioSeguido = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';
		$usuario = Container::getModel('usuario');
		
		if ($acao == 'seguir') {
			$usuario->__set('id', $_SESSION['id']);
			$usuario->seguirUsuario($usuarioSeguido);
		}else{
			$usuario->__set('id', $_SESSION['id']);
			$usuario->desSeguirUsuario($usuarioSeguido);
		}
		header(sprintf('Location: %s', $_SERVER['HTTP_REFERER']));
	}

	// remover tweets
	public function remover()
	{
		$this->validar();
		$tweet = Container::getModel('tweet');
		$tweet->__set('id', $_GET['tweet']);
		$tweet->removerTweet();
		header('Location: /timeline');
	}
}