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
        $this->view->with('toValidated', $friend->getUnvalidated());
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
        $this->view->with('users', $friend->getAllOut());
        $this->view->with('infos', $friend->getFriendsInfos());
        $this->makeView();      
    }

    // RSupprimer une demande d'amitié
    public function delete() {
        // Est-ce que l'ami existe
        $userid = intval($this->request->getData('id'));
        $user = new \modules\User\User();
        $user->exists($userid);

        if($user->exists) {
            // On demande une-double validation
            if($this->request->getExists('validate')) {
                $friend = new Friend($this->user->infos);
                $friend->delete($userid);
                $this->response->redirect('friend');
            }
            else {
                $this->menu_actif = 'friend';
                $this->side_section = 'site';
                $this->side_item = 'friend';
                $this->titre_page = 'Suppression lien - Amis';
                $this->view->with('friend', $user->infos);
                $this->makeView();      
            }
        }
    }

    // Refuser une demande d'amitié
    public function decline() {
        // Est-ce que l'ami existe
        $userid = intval($this->request->getData('id'));
        $user = new \modules\User\User();
        $user->exists($userid);

        if($user->exists) {
            // On demande une-double validation
            if($this->request->getExists('validate')) {
                $friend = new Friend($this->user->infos);
                $friend->delete($userid);
                $this->response->redirect('friend');
            }
            else {
                $this->menu_actif = 'friend';
                $this->side_section = 'site';
                $this->side_item = 'friend';
                $this->titre_page = 'Refus de lien - Ami';
                $this->view->with('friend', $user->infos);
                $this->makeView();      
            }
        }
    }

    // Demande quelqu'un en ami
    public function ask() {
        $userid = intval($this->request->getData('id'));
        $user = new \modules\User\User();
        $user->exists($userid);

        // Est-ce que l'ami existe ?
        if($user->exists) {
            $friend = new Friend($this->user->infos);
            
            if($friend->ask($userid)) {
                // On envoie l'email si la demande s'est bien passée
                $friend->sendEmail($user, 'ask');
            }
        }
        $this->response->redirect('friend');
    }

    // Valider une demande d'ami en attente
    public function validate() {
        // Est-ce que l'ami existe
        $userid = intval($this->request->getData('id'));
        $user = new \modules\User\User();
        $user->exists($userid);

        if($user->exists) {            
            $friend = new Friend($this->user->infos);
            if($friend->validate($userid)) {
                $friend->sendEmail($user, 'validate');   
            }
        }
        $this->response->redirect('friend');
    }

}