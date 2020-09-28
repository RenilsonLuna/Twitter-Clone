<?php 

namespace MF\Init;

abstract class Bootstrap
{
	public $routes;

	abstract protected function initRoutes();

	public function __construct()
	{
		$this->initRoutes();
		$this->run($this->getUrl());
	}

	// get e set routes
	public function getRoutes()
	{
		return $this->routes;
	}
	public function setRoutes(Array $routes)
	{
		$this->routes = $routes;
	}

	// rodando rotas
	protected function run($url)
	{
		foreach ($this->getRoutes() as $key => $route) {
			if ($url == $route['route']) {
				$class = "App\\Controllers\\".ucfirst($route['controller']);
				$controller = new $class;
				$action = $route['action'];
				$controller->$action();
			}
		}
	}

	// capturando url
	protected function getUrl()
	{
		return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	}
}