<?php
namespace modules\Index;

class IndexController extends \library\BaseController {
	public function showIndex() {
		$this->titre_page = 'Accueil';
		$this->menu_actif = 'index';

		$index = new Index();

		$this->view->with('lastOut', $index->getLastOut());
		$this->view->with('nextOut', $index->getNextOut());
		$this->view->with('lastCreated', $index->getLastCreated());
		$this->makeView();
	}
}