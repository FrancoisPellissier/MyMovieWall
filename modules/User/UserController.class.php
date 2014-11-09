<?php
namespace modules\User;

class UserController extends \library\BaseController {
	private $curUser;

	public function __construct(\library\HTTPRequest $request, $module, $action) {
		parent::__construct($request, $module, $action);

		$this->curUser = new User(true);

		// Un user est passé en paramètre ?
		if($this->request->getExists('id')) {
			$id = intval($this->request->getData('id'));
			
			$this->curUser->exists($id);

			// Si l'utilisateur n'existe pas on redirige vers l'accueil
			if(!$this->curUser->exists)
				$this->response->redirect();
		}
		// Rien en paramètres, on regarde si on est connecté
		else {
			if(!$this->user->infos['is_guest'])
				$this->curUser->exists($this->user->infos['id']);
			else
				$this->curUser->exists(2);
		}
		
		// On passe l'utilisateur voulu dans le vue
		$this->view->with('curUser', $this->curUser->infos);
	}

	public function showResume() {
		$this->titre_page = 'Résumé';

		$this->view->with('lastViewCine', $this->curUser->getLastViews('1'));
		$this->view->with('lastViewTele', $this->curUser->getLastViews('2'));
		$this->view->with('lastBiblio', $this->curUser->getLastBiblio());
		$this->makeView();
	}

	public function lastview() {
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
		$this->view->with('lastView', $this->curUser->getLastViews($type, false));
		$this->view->with('type', $type);
		$this->makeView();
	}

	public function login() {
		if(!$this->user->infos['is_guest'])
			$this->response->redirect();
		
		$this->titre_page = 'Connexion';
		$this->makeView();
	}
}