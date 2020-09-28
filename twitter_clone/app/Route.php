<?php 

namespace App;
use MF\Init\Bootstrap;

class Route extends Bootstrap
{	
	// rotas existentes
	protected function initRoutes()
	{
		$route['home'] = array(
			'route' => '/',
			'controller' => 'indexController',
			'action' => 'index',
		);

		$route['inscreverse'] = array(
			'route' => '/inscreverse',
			'controller' => 'indexController',
			'action' => 'inscreverse'
		);

		$route['registrar'] = array(
			'route' => '/registrar',
			'controller' => 'indexController',
			'action' => 'registrar'
		);

		$route['autenticar'] = array(
			'route' => '/autenticar',
			'controller' => 'AuthController',
			'action' => 'autenticar'
		);

		$route['timeline'] = array(
			'route' => '/timeline',
			'controller' => 'AppController',
			'action' => 'timeline'
		);

		$route['sair'] = array(
			'route' => '/sair',
			'controller' => 'AuthController',
			'action' => 'sair'
		);

		$route['tweet'] = array(
			'route' => '/tweet',
			'controller' => 'AppController',
			'action' => 'tweet'
		);

		$route['seguir'] = array(
			'route' => '/seguir',
			'controller' => 'AppController',
			'action' => 'seguir'
		);

		$route['acao'] = array(
			'route' => '/acao',
			'controller' => 'AppController',
			'action' => 'acao'
		);

		$route['remover'] = array(
			'route' => '/remover',
			'controller' => 'AppController',
			'action' => 'remover'
		);

		$this->setRoutes($route);
	}
}