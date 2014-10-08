<?php
namespace modules\Film;

class FilmController extends \library\BaseController {
	
	public function index() {
		$film = new \modules\Film\Film();
		$last = $film->getLasts();
		
		$this->view->with('last', $last);
		$this->view->make();
	}

	public function searchAllocine() {

		if($this->request->postExists('keyword')) {
			$keyword = $this->request->postData('keyword');

			$allocine = new \modules\Allocine\Allocine();
			$datas = $allocine->search($keyword);
			$this->view->with('datas', $datas);
		}

		$this->view->make();
	}

	public function add() {
		$id = intval($this->request->getData('id'));

		$allocine = new \modules\Allocine\Allocine();
		
		$film = new \modules\Film\Film();
		$film->exists($id, true);

		// Est-ce que le film existe déjà
		if($film->exists) {
			header("Location: /movie/film/".$film->infos[$film->key]);
	    	exit;
		}
		else {
			$datas = $allocine->getFilm($id);
			$film->hydrate($datas);
			$id = $film->add();

			// On parcout les genres pour les insérer/associer
			$film->assocGenre($id, $datas['genre']);

			// On parcourt les acteurs/réalisateurs pour les insérer/associer
			$film->assocPerson($id, $datas);
			
			header("Location: /movie/film/".$id);
	    	exit;
		}	
		
	}

	public function show() {
		$id = intval($this->request->getData('id'));
		$film = new \modules\Film\Film();
		$film->exists($id);

		// Si la fiche n'existe pas, on redirige vers l'accueil du module
		if(!$film->exists) {
			header("Location: /movie/film");
	    	exit;
		}
		else {
			$this->view->with('curFiche', $film->infos);
			$this->view->make();
		}
	}
}