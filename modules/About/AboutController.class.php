<?php
namespace modules\About;

class AboutController extends \library\BaseController {
	
	public function index() {
		$this->titre_page = 'A propos';
		$this->menu_actif = 'about';
		$this->makeView();
	}
}