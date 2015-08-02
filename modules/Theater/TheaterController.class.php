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
		$this->menu_actif = '';
		$this->makeView();
	}

	// Chercher un cinéma par code postal
	public function search() {

	}

	// Afficher le résultat de la recherche
	public function searchPost() {

	}

	// Ajouter à mon profil
	public function addToUser() {

	}

	// Supprimer du profil
	public function delFromUser() {

	}
}