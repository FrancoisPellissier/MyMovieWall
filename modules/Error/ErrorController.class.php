<?php
namespace modules\Error;

class ErrorController extends \library\BaseController {
	
	public function error_404() {
		$this->titre_page = 'Page introuvable';
		$this->view->addHeader('HTTP/1.0 404 Not Found');
		$this->makeView();
	}

	public function maintenance() {
		$this->titre_page = 'Maintenance';
		$this->makeView();
	}
}