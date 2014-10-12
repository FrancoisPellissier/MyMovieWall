<?php
namespace modules\Index;

class IndexController extends \library\BaseController {
	
	public function index() {
		$this->titre_page = 'Index';

		$this->view->with('lastViewCine', $this->user->getLastViews('1'));
		$this->view->with('lastViewTele', $this->user->getLastViews('2'));
		$this->view->with('lastBiblio', $this->user->getLastBiblio());
		$this->makeView();
	}
}