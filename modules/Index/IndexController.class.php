<?php
namespace modules\Index;

class IndexController extends \library\BaseController {
	
	public function index() {
		

		$this->view->with('request', $this->request);
		$this->view->make();
	}
}