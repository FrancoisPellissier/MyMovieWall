<?php
namespace modules\User;

class UserController extends \library\BaseController {
	public function showResume() {
		$this->titre_page = 'Résumé';

		$this->view->with('lastViewCine', $this->user->getLastViews('1'));
		$this->view->with('lastViewTele', $this->user->getLastViews('2'));
		$this->view->with('lastBiblio', $this->user->getLastBiblio());
		$this->makeView();
	}

	public function lastview() {
		// On redirige vers l'accueil si c'est un invité
		if($this->user->infos['is_guest'])
			$this->response->redirect('');

		$type = $this->request->getData('type');

		if($type) {
			if($type == 'cinema')
				$type = '1';
			else
				$type = '2';
		}
		else
			$type = 'all';

		$this->titre_page = 'Derniers films vus';
		$this->menu_actif = 'film_index';
		$this->view->with('lastView', $this->user->getLastViews($type, false));
		$this->view->with('type', $type);
		$this->makeView();
	}
}