<?php
namespace modules\Login;

class LoginController extends \library\BaseController {
	public function login() {
		if(!$this->user->infos['is_guest'])
			$this->response->redirect();

		// Le formulaire a été validé ?
		if($this->request->postExists('form_sent')) {
			$username = pun_trim($this->request->postData('req_username'));
			$password = pun_trim($this->request->postData('req_password'));

			$login = new Login(true);
			$valid = $login->login($username, $password);

			// Si tout va bien on redirige vers l'accueil
			if($valid)
				$this->response->redirect('');
			else {
				$this->titre_page = 'Connexion';
				$this->makeView();
			}
		}
		else {
			$this->titre_page = 'Connexion';
			$this->makeView();
		}
	}

	public function logout() {
		if($this->user->infos['is_guest'])
			$this->response->redirect();

		if ($pun_user['is_guest'] || !isset($_GET['id']) || $_GET['id'] != $this->user->infos['id'] || !isset($_GET['csrf_token']) || $_GET['csrf_token'] != pun_hash($this->user->infos['id'].pun_hash(get_remote_address())))
			$this->response->redirect();

		$login = new Login(true);
		$login->logout($this->user->infos);

		$this->response->redirect();
	}

	public function forget() {
		if(!$this->user->infos['is_guest'])
			$this->response->redirect('');

		// Le formulaire a été validé ?
		if($this->request->postExists('form_sent')) {
			$username = pun_trim($this->request->postData('email'));

			$user = new \modules\User\User();
			$user->exists($username, false, true);

			if($user->exists) {
				// Génération d'un mot de passe aléatoire
        		$password = random_pass(8);
        		$user->changePassword($password, $password, false);

        		// Envoi du nouveau mot de passe
        		$login = new Login(true);
        		$login->sendChangePassword($username, $password, $username);
        		}
        	else {
				$this->view->with('error', true);
        	}

			$this->view->with('complete', true);
			$this->titre_page = 'Mot de passe oublié';
			$this->makeView();
		}
		else {
			$this->titre_page = 'Mot de passe oublié';
			$this->makeView();
		}
	}
}