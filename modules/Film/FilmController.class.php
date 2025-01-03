<?php
namespace modules\Film;

class FilmController extends \library\BaseController {
	public function searchFilm() {
		$this->titre_page = 'Tous les films';
		$this->menu_actif = 'film_index';
		$this->side_section = 'site';
		$this->side_item = 'film_index';

		// On récupère l'ensemble des genres pour les passer dans la vue
		$genre = new \modules\Genre\Genre();
		$genres = $genre->all();
		$this->view->with('genres', $genres);

		$search = new \modules\Search\Search();

		// Est-ce que le formulaire a été validé ?
		if($this->request->postExists('search')) {
			$params = $search->cleanPost($_POST);
			$films = $search->search($params, $this->user->infos['id']);
		}
		// Sinon on récupère les derniers films ajoutés
		else {
			$film = new \modules\Film\Film();
			$films = $film->getLastFilms(18);
			$params = $search->defaultParams();
		}

		$this->view->with('params', $params);
		$this->view->with('films', $films);
		$this->view->with('force_biblio', false);
		$this->makeView();
	}
	
	public function index() {
		$this->titre_page = 'Liste des films';
		$this->menu_actif = 'film_index';
		$this->side_section = 'site';
		$this->side_item = 'film_index';

		$film = new \modules\Film\Film();
		$films = $film->getFilms();
		
		$this->view->with('films', $films);
		$this->makeView();
	}

	public function showFilmsToAssociate() {
		$this->titre_page = 'Films à associer';
		$this->menu_actif = 'film_index';
		$this->side_section = 'site';
		$this->side_item = 'film_index';

		$film = new \modules\Film\Film();
		$films = $film->getFilmToAssociate();
		
		$this->view->with('films', $films);
		$this->makeView();
	}

	public function filmsGenre() {
		$this->titre_page = 'Liste des films';
		$this->menu_actif = 'film_index';
		$this->side_section = 'site';
		$this->side_item = 'film_index';

		// On récupère l'ensemble des genres pour les passer dans la vue
		$genre = new \modules\Genre\Genre();
		$genres = $genre->all();
		$this->view->with('genres', $genres);

		$film = new \modules\Film\Film();

		// Un genre est fourni ?
		if($this->request->getExists('genreid')) {
			$genreid = intval($this->request->getData('genreid'));
			$films = $film->getFilmsGenre($genreid);
			$this->view->with('genreid', $genreid);
			}
		else {
			$films = $film->getLastFilms(18);
			$this->view->with('genreid', 0);
		}

		$this->view->with('genreid', $genreid);
		$this->view->with('films', $films);
		$this->makeView();
	}

	public function searchAllocine() {
		// On redirige vers l'accueil si c'est un invité
		if($this->user->infos['is_guest'])
			$this->response->redirect('');

		// Une recherche est passée en GET ou en POST ?
		if($this->request->getExists('keyword'))
			$keyword = $this->request->getData('keyword');
		else if($this->request->postExists('keyword'))
			$keyword = $this->request->postData('keyword');

		if(isset($keyword)) {
			$tmdb = new \modules\Tmdb\Tmdb();
			$datas = $tmdb->searchFilm($keyword);
			$this->view->with('datas', $datas);
			$this->view->with('keyword', $keyword);

			/*
			$keyword = str_replace('+', ' ', $keyword);
			$allocine = new \modules\Allocine\Allocine();
			$datas = $allocine->search($keyword);
			$this->view->with('datas', $datas);
			$this->view->with('keyword', $keyword);
			*/
		}
		$this->titre_page = 'Ajouter un film';
		$this->side_section = 'site';
		$this->jsfile = 'film_searchAllocine';
		$this->makeView();
	}

	public function search() {
		$film = new \modules\Film\Film();
		$datas = $film->search($this->request->postData('keyword'));
		
		$this->view->with('datas', $datas);
		$this->view->with('keyword', $this->request->postData('keyword'));
		
		$this->titre_page = 'Chercher un film';
		$this->side_section = 'site';

		// On récupère l'ensemble des genres pour les passer dans la vue
		$genre = new \modules\Genre\Genre();
		$genres = $genre->all();
		$this->view->with('genres', $genres);

		// Si on est connecté, on propose des films de l'API à ajouter

		if(!$this->user->infos['is_guest']) {
			$tmdb = new \modules\Tmdb\Tmdb();
			$datas = $tmdb->searchFilm($this->request->postData('keyword'));
			$this->view->with('datasAPI', $datas);
			
			/*
			$keyword = str_replace('+', ' ', $this->request->postData('keyword'));

			$allocine = new \modules\Allocine\Allocine();
			$datas = $allocine->search($keyword);
			$this->view->with('datasAPI', $datas);
			*/

			$this->jsfile = 'film_searchAllocine';
		}
		$this->makeView();
	}

	public function add() {
		// On redirige vers l'accueil si c'est un invité
		if($this->user->infos['is_guest'])
			$this->response->redirect('');

		ob_start();
		$id = intval($this->request->getData('id'));

		$tmdb = new \modules\Tmdb\Tmdb();

		// $allocine = new \modules\Allocine\Allocine();
		
		$film = new \modules\Film\Film();
		$film->exists($id, true);

		// Est-ce que le film existe déjà
		if($film->exists) {
			$id = $film->infos[$film->key];
		}
		else {
			// $datas = $allocine->getFilm($id);
			$datas = $tmdb->getFilm($id);			
			$film->hydrate($datas);

			$id = $film->add();

			// On parcout les genres pour les insérer/associer
			$film->assocGenre($id, $datas['genre']);

			// On parcourt les acteurs/réalisateurs pour les insérer/associer
			$film->assocPerson($id, $datas);

			// Ajout de l'affiche textuelle si pas d'image
			\library\Image::generateAffiche('img/movie/'.intval($id / 100).'/'.$id.'.jpg', $film->infos['titrevf']);

			// On track l'ajout du film
			$this->track('movie_add', $id);
		}	
		ob_end_clean();
		// On renvoi l'id au code JS
		echo $id;
		
	}

	public function show() {
		$id = intval($this->request->getData('id'));
		$film = new \modules\Film\Film();
		$film->exists($id);

		// Si la fiche n'existe pas, on redirige vers l'accueil du module
		if(!$film->exists) {
			$this->response->redirect('film');
		}
		else {
			$film->getInfos();
			$this->titre_page = $film->infos['titrevf'];
			$this->menu_actif = 'film_index';
			$this->side_section = 'site';

			$this->user->hasFilm($id);
			$this->user->hasViewFilm($id);
			$this->user->wishFilm($id);
			$this->user->FriendhasFilm($id);
			$this->user->FriendToWtach($id);
			$this->user->getRate($id);
			$this->jsfile = 'film_show';

			$this->view->with('curFiche', $film->infos);

			// Génération des Meta OG
			$meta = array();
			$meta['type'] = "video.movie";
			$meta['url'] = WWW_ROOT.'film/'.$id;
			$meta['title'] = $film->infos['titrevf'];
			$meta['description'] = $film->infos['synopsis'];
			$meta['image'] = WWW_ROOT.\library\Image::getUrl('movie', $film->infos['movieid']);
			$this->view->with('meta', $meta);

			return $film;
		}
	}

		public function showAssociate() {
		// On redirige vers l'accueil si c'est un invité
		if($this->user->infos['is_guest'])
			$this->response->redirect('');
		
		$id = intval($this->request->getData('id'));
		$film = new \modules\Film\Film();
		$film->exists($id);

		if($film->exists) {
			$this->titre_page = $film->infos['titrevf']." - Associer ficher TMDB";

			// Récupération des informations depuis l'API TMDB
			$tmdb = new \modules\Tmdb\Tmdb();
			$datas = $tmdb->searchFilm($film->infos['titrevf']);

			$this->view->with('datasAPI', $datas);
			$this->view->with('curId', $id);
			$this->makeView();	
		}
		else
			$this->response->redirect('');
	}

	public function showCasting() {
		$film = $this->show();
		$this->view->with('vueActif', 'casting');
		$this->makeView();
	}

	public function showTrailer() {
		$film = $this->show();
		$film->getTrailers();
		$this->view->with('trailers', $film->infos['trailers']);
		$this->view->with('vueActif', 'trailer');
		$this->makeView();
	}

	public function showSeance() {
		$film = $this->show();

		if($this->user->infos['is_guest'])
			$this->response->redirect('film/'.$film->infos['movieid']);

		$this->user->getTheaters();
		$film->getSeances($this->user->infos['theaters']);
		
		$this->view->with('seances', $film->infos['seances']);
		$this->view->with('vueActif', 'seance');
		$this->makeView();
	}

	public function showAvis() {
		$film = $this->show();
		$this->view->with('vueActif', 'avis');
		$this->makeView();
	}

	public function addBiblio() {
		ob_start();
		$id = intval($this->request->getData('id'));
		$film = new \modules\Film\Film();
		$film->exists($id);

		// Si la fiche n'existe pas, on redirige vers l'accueil du module
		if($film->exists) {
			$this->user->addBiblio($id, $this->request->getData('type'));
			$this->user->addWish($id, 'buy', '0');

			// On track l'ajout du film dans la biblio
			$this->track('biblio_add', $id);
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

			// On track la suppression du film dans la biblio
			$this->track('biblio_del', $id);
		}
		ob_end_clean();
	}

	public function addView() {
		// On redirige vers l'accueil si c'est un invité
		if($this->user->infos['is_guest'])
			$this->response->redirect('');

		$id = intval($this->request->getData('id'));
		$film = new \modules\Film\Film();
		$film->exists($id);

		// Si la fiche n'existe pas, on redirige vers l'accueil du module
		if(!$film->exists) {
				
		}
		else {
			$this->user->addView($id, $this->request->postData('type'), $this->request->postData('viewdate'));
			$this->user->addWish($id, 'view', '0');

			// On track la vue du film
			$this->track('view_add', $id);
		}
		$this->response->redirect('film/'.$id);
	}

	public function delView() {
		// On redirige vers l'accueil si c'est un invité
		if($this->user->infos['is_guest'])
			$this->response->redirect('');

		// On supprime la vue si user et film sont OK
		$movieid = intval($this->request->getData('id'));
		$viewid = intval($this->request->getData('viewid'));

		$this->user->delView($movieid, $viewid);

		$this->response->redirect('film/'.$movieid);
	}

	public function addWish() {
		ob_start();
		$id = intval($this->request->getData('id'));
		$film = new \modules\Film\Film();
		$film->exists($id);

		// Si la fiche n'existe pas, on redirige vers l'accueil du module
		if($film->exists) {
			$this->user->addWish($id, $this->request->getData('type'));

			// On track l'ajout du film dans la wishlist
			if($this->request->getData('type') == 'buy') {
				$this->track('wish_add', $id);
			}
			else {
				$this->track('towatch_add', $id);
			}

		}
		ob_end_clean();
	}

	public function delWish() {
		ob_start();
		$id = intval($this->request->getData('id'));
		$film = new \modules\Film\Film();
		$film->exists($id);

		// Si la fiche n'existe pas, on redirige vers l'accueil du module
		if($film->exists) {
			$this->user->addWish($id, $this->request->getData('type'), '0');

			// On track l'ajout du film dans la wishlist
			if($this->request->getData('type') == 'buy') {
				$this->track('wish_del', $id);
			}
			else {
				$this->track('towatch_del', $id);
			}
		}
		ob_end_clean();
	}

	public function majAffiche() {
		// On redirige vers l'accueil si c'est un invité
		if($this->user->infos['is_guest'])
			$this->response->redirect('');
		
		$id = intval($this->request->getData('id'));
		$film = new \modules\Film\Film();
		$film->exists($id);

		// Si la fiche n'existe pas, on redirige vers l'accueil du module
		if($film->exists) {
			// On récupère les informations allocine
			$allocine = new \modules\Allocine\Allocine();
			$datas = $allocine->getFilm($film->infos['code']);
			// dump($datas);

			if($datas['affiche'] != '') {
				$film->pictureurl = $datas['affiche'];
				$film->getPoster($id);

			}
	    	$this->response->redirect('film/'.$id);		
		}
		else
			$this->response->redirect('');		
	}

	public function majFiche() {
		// On redirige vers l'accueil si c'est un invité
		if($this->user->infos['is_guest'])
			$this->response->redirect('');
		
		$id = intval($this->request->getData('id'));
		$film = new \modules\Film\Film();
		$film->exists($id);

		// Si la fiche n'existe pas, on redirige vers l'accueil du module
		if($film->exists) {
			// On récupère les informations allocine
			/*
			$allocine = new \modules\Allocine\Allocine();
			$datas = $allocine->getFilm($film->infos['code']);
			*/

			// Récupération des informations depuis l'API TMDB
			$tmdb = new \modules\Tmdb\Tmdb();
			$datas = $tmdb->getFilm($film->infos['tmdbid']);

			$film->hydrate($datas);
			$film->edit($film->infos);
			// Mise à jour des genres
			$film->assocGenre($id, $datas['genre']);
			// Mise à jour du casting
			$film->assocPerson($id, $datas);
			// Mise à jour de l'affiche
			if($film->pictureurl != '') {
				$film->getPoster($id);
				\library\Image::generateAffiche($film->infos['folder'].$film->infos['movieid'].'.jpg', $film->infos['titrevf']);
			}

	    	$this->response->redirect('film/'.$id);		
		}
		else
			$this->response->redirect('');
	}

	public function associate() {
		$id = intval($this->request->getData('id'));
		$tmdbid = intval($this->request->getData('tmdbid'));
		
		$film = new \modules\Film\Film();
		$film->exists($id);

		if($film->exists) {
			$film->infos['tmdbid'] = $tmdbid;

			$tmdb = new \modules\Tmdb\Tmdb();
			$datas = $tmdb->getFilm($film->infos['tmdbid']);

			$film->hydrate($datas);
			$film->edit($film->infos);
			// Mise à jour des genres
			$film->assocGenre($id, $datas['genre']);
			// Mise à jour du casting
			$film->assocPerson($id, $datas);
			// Mise à jour de l'affiche
			if($film->pictureurl != '') {
				$film->getPoster($id);
				\library\Image::generateAffiche($film->infos['folder'].$film->infos['movieid'].'.jpg', $film->infos['titrevf']);
			}

	    	$this->response->redirect('film/'.$id);		
			}
		else
			$this->response->redirect('');
	}

	public function majFiches() {
		// On redirige vers l'accueil si c'est un invité
		if($this->user->infos['is_guest'])
			$this->response->redirect('');
		
		$id = intval($this->request->getData('id'));
		$film = new \modules\Film\Film();
		$id = $film->getToUpdate();

		if($id == 0)
			$this->response->redirect('');
	
		$film->exists($id);

		// Si la fiche n'existe pas, on redirige vers l'accueil du module
		if($film->exists) {
			// On récupère les informations allocine
			/*
			$allocine = new \modules\Allocine\Allocine();
			$datas = $allocine->getFilm($film->infos['code']);
			*/

			$tmdb = new \modules\Tmdb\Tmdb();
			$datas = $tmdb->getFilm($film->infos['tmdbid']);

			$film->hydrate($datas);
			$film->edit($film->infos);
			// Mise à jour des genres
			$film->assocGenre($id, $datas['genre']);
			// Mise à jour du casting
			$film->assocPerson($id, $datas);
			// Mise à jour de l'affiche
			if($film->pictureurl != '') {
				$film->getPoster($id);
				\library\Image::generateAffiche($film->infos['folder'].$film->infos['movieid'].'.jpg', $film->infos['titrevf']);
			}

	    	$this->response->redirect('film/cron/update/'.$id);		
		}
		else
			$this->response->redirect('');
	}

	public function rate() {
		ob_start();
		$id = intval($this->request->getData('id'));
		$film = new \modules\Film\Film();
		$film->exists($id);

		if($film->exists) {
			$this->user->rateFilm($id, $this->request->getData('rate'));

			// On track la notation du film
			$this->track('rate_add', $id);
		}
		ob_end_clean();
	}
}