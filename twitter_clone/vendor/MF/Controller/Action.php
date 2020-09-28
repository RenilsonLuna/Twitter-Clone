<?php 

namespace MF\Controller;

abstract class Action 
{
	protected $view;

	public function __construct()
	{
		$this->view = new \stdClass();
	}

	protected function render($view)
	{
		$this->view->page = $view;
		require_once "../App/Views/layout.phtml";
	}

	protected function content()
	{
		$classeAtual = get_class($this);
		$classeAtual = str_replace("App\\Controllers\\", '', $classeAtual);
		$classeAtual = strtolower(str_replace('Controller', '', $classeAtual));
		require_once "../App/Views/".$classeAtual."/".$this->view->page.".phtml";
	}
}