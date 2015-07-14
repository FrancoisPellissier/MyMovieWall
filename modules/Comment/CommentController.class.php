<?php
namespace modules\Comment;

class CommentController extends \library\BaseController {
	public function addComment() {
		$id = intval($this->request->getData('id'));
		$ticket = new \modules\Ticket\Ticket();
		$ticket->exists($id);

		// Si la fiche n'existe pas, on redirige vers l'accueil du module
		if(!$ticket->exists) {
			$this->response->redirect('ticket');
		}
		else {
			// Un message a-t-il bien été posté ?
			if(!$this->request->postExists('message'))
				$this->response->redirect('ticket/'.$id);

			$message = trim($this->request->postData('message'));

			// Le message n'est pas vide ?
			if(empty($message))
				$this->response->redirect('ticket/'.$id);
			
			// Tout va bien, en traite le commentaire
			$comment = new \modules\Comment\Comment();

			$datas = array(
				'ticketid' => $id,
				'message' => $message,
				'userid' => $this->user->infos['id']
				);

			$comment->hydrate($datas);
			$new_id = $comment->add();
			$ticket->edit(array());

			$this->response->redirect('ticket/'.$id.'#'.$new_id);
		}
	}

	public function editComment() {
		// On teste le ticket
		$tid = intval($this->request->getData('tid'));
		$ticket = new \modules\Ticket\Ticket();
		$ticket->exists($tid);

		// Si la fiche n'existe pas, on redirige vers l'accueil du module
		if(!$ticket->exists)
			$this->response->redirect('ticket');

		//Sinon, on test le commentaire
		$id = intval($this->request->getData('id'));
		$comment = new \modules\Comment\Comment();
		$comment->exists($id);

		// Si la fiche n'existe pas, on redirige vers l'accueil du module
		if(!$comment->exists)
			$this->response->redirect('ticket/'.$tid);

		// On s'assure que l'on a le droit de modifier ce message
		if($comment->infos['userid'] != $this->user->infos['id'])
			$this->response->redirect('ticket/'.$tid);

		// Est-ce que le formulaire a été validé ?
		if($this->request->postExists('message')) {
			$datas = array(
				'message' => trim($this->request->postData('message'))
				);
			$comment->edit($datas);
			$ticket->edit(array());

			$this->response->redirect('ticket/'.$tid.'#'.$id);
		}
		else {
			$this->menu_actif = 'ticket_index';
			$this->titre_page = 'Modifier le commentaire';
			
			$this->view->with('ticket', $ticket->infos);
			$this->view->with('comment', $comment->infos);

			$this->makeView();
		}
	}
}