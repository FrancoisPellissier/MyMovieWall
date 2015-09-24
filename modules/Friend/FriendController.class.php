<?php
namespace modules\Friend;

class FriendController extends \library\BaseController {
	
	public function __construct(\library\HTTPRequest $request, $module, $action) {
		parent::__construct($request, $module, $action);

		if($this->user->infos['is_guest'])
			$this->response->redirect();
	}

	public function index() {
		$this->titre_page = 'Amis';
		$this->menu_actif = 'friend';
		$this->side_section = 'site';
		$this->side_item = 'friend';

		$friend = new Friend($this->user->infos);

		$this->view->with('friends', $friend->getFriends());
		$this->view->with('isfriend', $friend->isFriend());
		$this->view->with('infos', $friend->getFriendsInfos());
		$this->makeView();
	}

	public function search() {
		$this->titre_page = 'Amis - Recherche';
		$this->menu_actif = 'friend';
		$this->side_section = 'site';
		$this->side_item = 'friend';

		$friend = new Friend($this->user->infos);
		$this->view->with('friends', $friend->getFriends());
		$this->view->with('users', $friend->getAll());
		$this->view->with('infos', $friend->getFriendsInfos());
		$this->makeView();		
	}

	public function add() {
		// On redirige vers l'accueil si c'est un invité
		if($this->user->infos['is_guest'])
			$this->response->redirect('');

		// Est-ce que l'ami existe
		$userid = intval($this->request->getData('id'));
		$user = new \modules\User\User();
		$user->exists($userid);

		if($user->exists) {
			$friend = new Friend($this->user->infos);
			$friend->addFriend($userid);

			// Envoyer une notification au nouvel ami ?
			if($user->infos['notif_friend'])
				$friend->sendEmail($user);
		}
		$this->response->redirect('friend/search');
	}

		public function del() {
		// On redirige vers l'accueil si c'est un invité
		if($this->user->infos['is_guest'])
			$this->response->redirect('');

		// ob_start();
		// Est-ce que l'ami existe
		$userid = intval($this->request->getData('id'));
		$user = new \modules\User\User();
		$user->exists($userid);

		if($user->exists) {
			$friend = new Friend($this->user->infos);
			$friend->delFriend($userid);
		}
		// ob_end_clean();
		$this->response->redirect('friend/search');
	}
}