<?php
namespace modules\Avis;

class AvisController extends \library\BaseController {
	public function add() {
		// On redirige vers l'accueil si c'est un invité
		if($this->user->infos['is_guest'])
			$this->response->redirect('ticket');

		// On récupère le film
		$id = intval($this->request->getData('id'));
		$film = new \modules\Film\Film();
		$film->exists($id);

		// Si la fiche n'existe pas, on redirige vers l'accueil du site
		if(!$film->exists) {
			$this->response->redirect('');
		}
		else {
			// Un message a été posté ?
			if($this->request->postExists('message')) {
				$message = trim($this->request->postData('message'));

				// Le message n'est pas vide ?
				if(empty($message))
					$this->response->redirect('avis/add/'.$id);
				
				// Tout va bien, en traite le commentaire
				$avis = new Avis();

				$datas = array(
					'movieid' => $id,
					'userid' => $this->user->infos['id'],
					'message' => $message
					);

				$avis->hydrate($datas);
				$new_id = $avis->add();
				$avis->hydrate(array('commentid' => $new_id));

				$this->response->redirect('film/'.$id.'/avis#'.$new_id);

				

				$this->response->redirect('ticket/'.$tid.'#'.$id);
			}
			else {
				$this->menu_actif = 'film_index';
				$this->side_section = 'site';
				$this->side_item = 'film_index';
				$this->titre_page = 'Donner son avis sur '.$film->infos['titrevf'];
				
				$this->view->with('film', $film->infos);
				$this->makeView();
			}
		}
	}

	public function edit() {
		// On redirige vers l'accueil si c'est un invité
		if($this->user->infos['is_guest'])
			$this->response->redirect('');
		
		// On teste l'avis
		$id = intval($this->request->getData('id'));
		$avis = new \modules\Avis\Avis();
		$avis->exists($id);

		if(!$avis->exists)
			$this->response->redirect('');

		// On teste le film
		$film = new \modules\Film\Film();
		$film->exists($avis->infos['movieid']);

		if(!$film->exists)
			$this->response->redirect('');

		// On s'assure que l'on a le droit de modifier ce message
		if($avis->infos['userid'] != $this->user->infos['id'])
			$this->response->redirect('film/'.$avis->infos['movieid'].'/avis');

		// Est-ce que le formulaire a été validé ?
		if($this->request->postExists('message')) {
			$datas = array(
				'message' => trim($this->request->postData('message'))
				);
			$avis->edit($datas);

			$this->response->redirect('film/'.$avis->infos['movieid'].'/avis#'.$id);
		}
		else {
			$this->menu_actif = 'film_index';
			$this->side_section = 'site';
			$this->side_item = 'film_index';
			$this->titre_page = 'Modifier son avis sur '.$film->infos['titrevf'];
			
			$this->view->with('film', $film->infos);
			$this->view->with('avis', $avis->infos);
			$this->makeView();
		}
	}
}