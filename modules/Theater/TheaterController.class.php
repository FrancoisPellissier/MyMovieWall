<?php
namespace modules\Theater;

class TheaterController extends \library\BaseController {
	public function __construct(\library\HTTPRequest $request, $module, $action) {
		parent::__construct($request, $module, $action);

		// Seuls les connectés y ont accès
		if($this->user->infos['is_guest'])
			$this->response->redirect();
	}

	// Afficher mes cinémas
	public function myTheaters() {
		$this->titre_page = 'Mes cinémas';
		$this->side_section = 'profil';
		$this->side_item = 'theater';
		
		$this->user->getTheaters();
		$this->view->with('theaters', $this->user->infos['theaters']);
		$this->makeView();
	}

	// Chercher un cinéma par code postal
	public function search() {
		$this->titre_page = 'Chercher un cinéma';
		$this->side_section = 'profil';
		$this->side_item = 'theater';
		$this->makeView();
	}

	// Afficher le résultat de la recherche
	public function searchPost() {
		// Un code postal a-t-il bien été envoyé ?
		if(!$this->request->postExists('keyword'))
			$this->response->redirect('theater');

		// Le code postal n'est pas vide ?
		$keyword = trim($this->request->postData('keyword'));
		if(empty($keyword))
			$this->response->redirect('ticket');

		$allocine = new \modules\Allocine\Allocine();
		$return = $allocine->searchTheater($keyword);

		$this->titre_page = 'Chercher un cinéma';
		$this->side_section = 'profil';
		$this->side_item = 'theater';
		$this->jsfile = 'theater';
		$this->view->with('theaters', $return);
		$this->view->with('keywords', $keyword);

		$this->user->getTheaters();
		$this->view->with('hasTheaters', $this->user->infos['theaters']);

		$this->makeView();
	}

	// Ajouter à mon profil
	public function addToUser() {
		// ob_start();

		$code = $this->request->getData('code');
		
		$theater = new Theater();
		$theater->exists($code, true);

		if($theater->exists) {
			$id = $theater->infos[$theater->key];
		}
		else {
			$allocine = new \modules\Allocine\Allocine();
			$datas = $allocine->getTheater($code);
			
			if(!$datas)
				$this->response->redirect('theater');

			$theater->hydrate($datas);

			$id = $theater->add();
			$theater->exists($id);
		}
		// On associe le Cinéma avec l'utilisateurs
		$theater->addToUser($this->user->infos['id']);

		$this->response->redirect('theater');
	}

	// Supprimer du profil
	public function delFromUser() {
		$theater = new Theater();
		$id = $this->request->getData('id');
		$theater->exists($id);
		
		if(!$theater->exists)
			$this->response->redirect('theater');
		
		$theater->delFromUser($this->user->infos['id']);
		$this->response->redirect('theater');
	}
}