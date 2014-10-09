<?php
namespace modules\Index;

class IndexController extends \library\BaseController {
	
	public function index() {
		
		$this->titre_page = 'Index';
		$this->makeView();
	}
}