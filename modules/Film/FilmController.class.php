<?php
namespace modules\Film;

class FilmController extends \library\BaseController {
	
	public function index() {
		$this->titre_page = 'Liste des films';
		$this->menu_actif = 'film_index';

		$film = new \modules\Film\Film();
		$last = $film->getFilms();
		
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
		$this->menu_actif = 'film_add';
		$this->jsfile = 'film_searchAllocine';
		$this->makeView();
	}

	public function add() {
		ob_start();
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
		ob_end_clean();
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
			$this->menu_actif = 'film_index';

			$this->user->hasFilm($id);
			$this->user->hasViewFilm($id);
			$this->jsfile = 'film_show';

			$this->view->with('curFiche', $film->infos);
			$this->makeView();
		}
	}

	public function addBiblio() {
		ob_start();
		$id = intval($this->request->getData('id'));
		$film = new \modules\Film\Film();
		$film->exists($id);

		// Si la fiche n'existe pas, on redirige vers l'accueil du module
		if($film->exists) {
			$this->user->addBiblio($id, $this->request->getData('type'));
		}
		ob_end_clean();
	}

	public function delBiblio() {
		ob_start();
		$id = intval($this->request->getData('id'));
		$film = new \modules\Film\Film();
		$film->exists($id);

		// Si la fiche n'existe pas, on redirige vers l'accueil du module
		if($film->exists) {
			$this->user->addBiblio($id, $this->request->getData('type'), '0');
		}
		ob_end_clean();
	}

	public function addView() {
		$id = intval($this->request->getData('id'));
		$film = new \modules\Film\Film();
		$film->exists($id);

		// Si la fiche n'existe pas, on redirige vers l'accueil du module
		if(!$film->exists) {
				
		}
		else {
			$this->user->addView($id, $this->request->postData('type'), $this->request->postData('viewdate'));
		}
		header("Location: /movie/film/".$id);
	    exit;
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
		$this->view->with('lastView', $this->user->getLastViews($type, false));
		$this->makeView();
	}
}