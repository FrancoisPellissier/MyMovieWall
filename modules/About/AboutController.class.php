<?php
namespace modules\About;

class AboutController extends \library\BaseController {
	
	public function index() {
		$this->view->with('request', $this->request);
		$this->view->make();
	}
}