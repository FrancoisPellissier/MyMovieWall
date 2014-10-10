<?php
namespace modules\Film;

class FilmController extends \library\BaseController {
	
	public function index() {
		$this->titre_page = 'Liste des films';

		$film = new \modules\Film\Film();
		$last = $film->getLasts();
		
		$this->view->with('last', $last);
		$this->makeView();
	}

	public function searchAllocine() {

		if($this->request->postExists('keyword')) {
			$keyword = $this->request->postData('keyword');

			$allocine = new \modules\Allocine\Allocine();
			$datas = $allocine->search($keyword);
			$this->view->with('datas', $datas);
		}
		$this->titre_page = 'Ajouter un film';
		$this->makeView();
	}

	public function add() {
		$id = intval($this->request->getData('id'));

		$allocine = new \modules\Allocine\Allocine();
		
		$film = new \modules\Film\Film();
		$film->exists($id, true);

		// Est-ce que le film existe déjà
		if($film->exists) {
			$id = $film->infos[$film->key];
		}
		else {
			$datas = $allocine->getFilm($id);
			$film->hydrate($datas);
			$id = $film->add();

			// On parcout les genres pour les insérer/associer
			$film->assocGenre($id, $datas['genre']);

			// On parcourt les acteurs/réalisateurs pour les insérer/associer
			$film->assocPerson($id, $datas);
		}	
		
		echo $id;
		exit();
		// On redirige vers la fiche du film
		// header("Location: /movie/film/".$id);
	    //exit;
		
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
			$film->getInfos();
			$this->titre_page = $film->infos['titrevf'];

			$this->user->hasFilm($id);

			$this->view->with('curFiche', $film->infos);
			$this->makeView();
		}
	}

	public function addBiblio() {
		$id = intval($this->request->getData('id'));
		$film = new \modules\Film\Film();
		$film->exists($id);

		// Si la fiche n'existe pas, on redirige vers l'accueil du module
		if(!$film->exists) {
			return false;
		}
		else {
			$this->user->addBiblio($id, $this->request->getData('type'));
			return true;
		}
	}
}