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

			// On récupère les droits de l'utilisateur
			$this->curUser->getRights();
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
		$this->side_section = 'site';
		$this->side_item = '';

		$this->view->with('lastViewCine', $this->curUser->getLastViews('1'));
		$this->view->with('lastViewTele', $this->curUser->getLastViews('2'));
		$this->view->with('lastBiblio', $this->curUser->getLastBiblio());
		$this->makeView();
	}

	public function showBiblio() {
		$this->titre_page = 'Mes films';
		$this->side_section = 'site';
		$this->side_item = 'biblio';

		// On récupère l'ensemble des genres pour les passer dans la vue
		$genres = $this->curUser->getGenres();
		$this->view->with('genres', $genres);

		$search = new \modules\Search\Search();

		// Est-ce que le formulaire a été validé ?
		if($this->request->postExists('search')) {

			$params = $search->cleanPost($_POST);
			$params['biblio'] = true;
			$films = $search->search($params, $this->curUser->infos['id']);
		}
		// Sinon on récupère les derniers films ajoutés
		else {
			$films = $this->curUser->getLastBiblio(18);
			$params = $search->defaultParams();
			$params['biblio'] = true;
		}

		$this->view->with('params', $params);
		$this->view->with('films', $films);
		$this->view->with('force_biblio', true);
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

		// Voir un mois en particulier
		$annee = intval($this->request->getData('annee'));
		$mois = intval($this->request->getData('mois'));
		
		$this->titre_page = 'Derniers films vus';
		$this->side_section = 'site';
		$this->side_item = 'lastview';
		$this->view->with('lastView', $this->curUser->getLastViews($type, false, $annee, $mois));
		$this->view->with('type', $type);
		$this->view->with('annee', $annee);
		$this->view->with('mois', $mois);

		$this->makeView();
	}

	public function edit() {
		// On redirige vers l\'accueil si ce n'est pas notre profil
		if($this->user->infos['id'] != $this->curUser->infos['id'])
			$this->response->redirect();

		$modifyRealname = false;
		$modifyPassword = false;

		// Le formulaire a été validé ?
		if($this->request->postExists('realname')) {

			$realname = pun_trim($this->request->postData('realname'));
			$password1 = pun_trim($this->request->postData('password1'));
			$password2 = pun_trim($this->request->postData('password2'));

			$modifyRealname = $this->user->changeRealname($realname);
			$modifyPassword = $this->user->changePassword($password1, $password2);

			$this->response->redirect('user/'.$this->user->infos['id'].'/edit');
		}

		$this->titre_page = 'Profil';
		$this->side_section = 'profil';
		$this->side_item = 'general';
		$this->view->with('modifRealname', $modifRealname);
		$this->view->with('modifyPassword', $modifyPassword);
		$this->makeView();
	}

	public function wishlist() {
		// On redirige vers l\'accueil si ce n'est pas notre profil
		if($this->user->infos['is_guest'])
			$this->response->redirect('user/'.$this->curUser->infos['id']);

		$this->titre_page = 'Wishlist';
		$this->side_section = 'site';
		$this->side_item = 'wishlist';
		$this->view->with('wishlist', $this->curUser->getWishlist());
		$this->makeView();
	}

	public function towatchlist() {
		$type = $this->request->getData('type');

		if(!$type)
			$type = '1';
		else {
			if($type == 'cinema')
				$type = '1';
			else
				$type = '2';
		}

		$this->titre_page = 'To Watch List';
		$this->side_section = 'site';
		$this->side_item = 'towatchlist';
		$this->view->with('films', $this->curUser->getToWatchList($type));
		$this->view->with('type', $type);
		$this->makeView();
	}

	public function showStats() {
		$type = $this->request->getData('type');

		if(!$type)
			$type = 'all';
		else {
			if($type == 'cinema')
				$type = '1';
			else
				$type = '2';
		}

		$this->titre_page = 'Statistiques';
		$this->side_section = 'site';
		$this->side_item = 'stats';
		$this->view->with('stats', $this->curUser->getNbViewsMonth($type));
		$this->view->with('type', $type);

		$this->view->with('statsGenre', $this->curUser->getStatsNb('genre'));
		$this->view->with('statsActeur', $this->curUser->getStatsNb('acteur'));
		$this->view->with('statsRealisateur', $this->curUser->getStatsNb('realisateur'));

		$this->makeView();
	}

	public function notification() {
		// On redirige vers l\'accueil si ce n'est pas notre profil
		if($this->user->infos['id'] != $this->curUser->infos['id'])
			$this->response->redirect();

		// Le formulaire a été validé ?
		if($this->request->postExists('form_sent')) {

			$values = array(
				'notif_ticket' => 0,
				'notif_friend' => 0
				);

			foreach($values AS $key=>$value) {
				if($this->request->postExists($key)) {
					$values[$key] = 1;
					$this->user->infos[$key] = 1;
				}
				else {
					$this->user->infos[$key] = 0;
				}
			}

			$this->user->changeNotification($values);			
			$this->view->with('valid', true);
		}
		$this->titre_page = 'Notifications';
		$this->side_section = 'profil';
		$this->side_item = 'notification';
		$this->makeView();
	}

	public function right() {
		// On redirige vers l\'accueil si ce n'est pas notre profil
		if($this->user->infos['id'] != $this->curUser->infos['id'])
			$this->response->redirect();

		// Le formulaire a été validé ?
		if($this->request->postExists('form_sent')) {
			// Initialisation des valeurs vides
			$right = array();
        	$right['biblio'] = array('guest' => '0', 'member' => '0', 'friend' => '0');
        	$right['towatchlist'] = array('guest' => '0', 'member' => '0', 'friend' => '0');
        	$right['lastview'] = array('guest' => '0', 'member' => '0', 'friend' => '0');
	        $right['wishlist'] = array('guest' => '0', 'member' => '0', 'friend' => '0');
        	$right['stats'] = array('guest' => '0', 'member' => '0', 'friend' => '0');

        	// On parcourt les actions et les droits
        	foreach($right AS $action => $values) {
        		$droits = array('guest', 'member', 'friend');

        		foreach($droits AS $droit) {
        			if(isset($_POST['right'][$action][$droit])) {
        				$right[$action][$droit] = '1';
        				$this->user->infos['right'][$action][$droit] = '1';
        			}
        			else {
        				$this->user->infos['right'][$action][$droit] = '0';
        				$right[$action][$droit] = '0';
        			}
        		}
        		// On sauvegarde les modifications
        		$this->user->updateRight($right[$action], $action);
        	}

		}
		$this->titre_page = 'Droits';
		$this->side_section = 'profil';
		$this->side_item = 'right';
		$this->makeView();
	}

	public function showAvis() {
		$this->titre_page = 'Mes avis';
		$this->side_section = 'site';
		$this->side_item = 'avis';
		$this->view->with('avis', $this->curUser->getAvis());

		$this->makeView();
	}
}