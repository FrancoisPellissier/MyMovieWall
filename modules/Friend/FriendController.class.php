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

		$friend = new Friend($this->user->infos);

		$this->view->with('friends', $friend->getFriends());
		$this->view->with('isfriend', $friend->isFriend());
		$this->view->with('infos', $friend->getFriendsInfos());
		$this->makeView();
	}
}