<?php
namespace modules\Person;

class PersonController extends \library\BaseController {

	public function showFilms() {
		// Une personne est passée en paramètre ?
		if($this->request->getExists('id')) {
			$id = intval($this->request->getData('id'));
			
			$person = new Person();
			$person->exists($id);

			// Si la personne n'existe pas on redirige vers l'accueil
			if(!$person->exists)
				$this->response->redirect('');

			$this->view->with('filmActeur', $person->getFilms($id, 1));
			$this->view->with('filmReal', $person->getFilms($id, 2));
		}
		else
			$person->response->redirect('');		
		
		$this->titre_page = $person->infos['fullname'];
		$this->view->with('person', $person->infos);
		$this->side_section = 'site';
		$this->makeView();
	}

	public function moreFilmsFrom() {
		// Une personne est passée en paramètre ?
		if($this->request->getExists('id')) {
			$id = intval($this->request->getData('id'));
			
			$person = new Person();
			$person->exists($id);

			// Si la personne n'existe pas on redirige vers l'accueil
			if(!$person->exists)
				$this->response->redirect('');

			$filmsId = $person->getFilmsIds($id);
			$tmdb = new \modules\Tmdb\Tmdb();
			$datas = $tmdb->getMoreFilmsFrom($person->infos['tmdbid'], $filmsId);

			$this->view->with('filmActeur', $datas['casting'] );
			$this->view->with('filmReal',  $datas['director'] );
		}
		else
			$person->response->redirect('');		
		
		$this->titre_page = $person->infos['fullname']. ' - Plus de films';
		$this->view->with('person', $person->infos);
		$this->side_section = 'site';
		$this->jsfile = 'film_searchAllocine';
		$this->makeView();
	}
}
	