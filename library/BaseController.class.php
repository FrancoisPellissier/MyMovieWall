<?php
namespace library;

abstract class BaseController {
	protected $action = '';
	protected $module = '';
	protected $view = '';
	protected $request;
	protected $titre_page;
	protected $user;

	public function __construct(\library\HTTPRequest $request, $module, $action) {
		$this->setModule($module);
		$this->setAction($action);
		$this->request = $request;

		$this->user = new \modules\User\User();
		$this->view = new \library\View($module, $action);
	}

	public function execute() {
		$method = $this->action;
		 
		if (!is_callable(array($this, $method))) {
			throw new \RuntimeException('L\'action "'.$this->action.'" n\'est pas définie sur ce module');
		}
		
		$this->$method($this->request);
	}

	public function setModule($module) {
		$this->module = $module;
	}
	 
	public function setAction($action) {
		$this->action = $action;
	}

	public function makeView() {	    
		$this->view->with('titre_page', $this->titre_page);
		$this->view->with('user', $this->user->infos);
		$this->view->make();
	}
}