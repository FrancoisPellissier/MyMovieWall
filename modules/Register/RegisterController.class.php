<?php
namespace modules\Register;

class RegisterController extends \library\BaseController {
	public function register() {
		if(!$this->user->infos['is_guest'])
			$this->response->redirect();

		$errors = array();
		$complete = false;
		$newUser = new Register(true);

		if($this->request->postExists('form_sent')) {
			$errors = $newUser->register();

			if(empty($errors))
				$complete = true;
		}

		// On affiche le formulaire s'il y a des erreurs ou si on n'a pas validé le formulaire
		$this->view->with('errors', $errors);
		$this->view->with('complete', $complete);
		$this->titre_page = 'Inscription';
		$this->makeView();	
	}

	public function validate() {
		if(!$this->user->infos['is_guest'])
			$this->response->redirect();

		$this->titre_page = 'Validation de l\'inscription';
		$this->makeView();	
	}
}