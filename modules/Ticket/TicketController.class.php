<?php
namespace modules\Ticket;

class TicketController extends \library\BaseController {
	public function showList() {
		$this->titre_page = 'Tickets';
		$this->menu_actif = 'ticket_index';

		$ticket = new \modules\Ticket\Ticket();
		$this->view->with('tickets', $ticket->getList());
		$this->makeView();
	}

	public function addTicket() {
		// On redirige vers l'accueil si c'est un invité
		if($this->user->infos['is_guest'])
			$this->response->redirect('ticket');

		$this->titre_page = 'Ouvrir un ticket';
		$this->menu_actif = 'ticket_index';

		$ticket = new \modules\Ticket\Ticket();
		$this->view->with('type', $ticket->getType());
		$this->makeView();
	}

	public function addTicketPost() {
		// On redirige vers l'accueil si c'est un invité
		if($this->user->infos['is_guest'])
			$this->response->redirect('ticket');
		
		// Un message a-t-il bien été envoyé ?
		if(!$this->request->postExists('message'))
			$this->response->redirect('ticket');

		// Le message n'est pas vide ?
		$message = trim($this->request->postData('message'));
		if(empty($message))
			$this->response->redirect('ticket');

		// Un message a-t-il bien été envoyé ?
		if(!$this->request->postExists('ticketname'))
			$this->response->redirect('ticket');

		// Le message n'est pas vide ?
		$ticketname = trim($this->request->postData('ticketname'));
		if(empty($ticketname))
			$this->response->redirect('ticket');

		// On récupère le type de ticket
		$typeid = intval($this->request->postData('typeid'));
		$typeid = ($typeid < 1 ? 1 : $typeid);

		// Tout va bien, on créé le ticket
		$ticket = new \modules\Ticket\Ticket();
		$datas = array(
				'ticketname' => $ticketname,
				'userid' => $this->user->infos['id'],
				'statusid' => 1,
				'typeid' => $typeid
			);
		$ticket->hydrate($datas);
		$id = $ticket->add();

		// On créé le commentaire
		$comment = new \modules\Comment\Comment();
		$datas = array(
			'ticketid' => $id,
			'message' => $message,
			'userid' => $this->user->infos['id']
			);

		$comment->hydrate($datas);
		$new_id = $comment->add();
		
		// On met à jour l'information de premier commentaire
		$ticket->hydrate(array('ticketid' => $id));
		$ticket->edit(array('firstcommentid' => $new_id));

		$this->response->redirect('ticket/'.$id);
	}

	public function showTicket() {
		$id = intval($this->request->getData('id'));
		$ticket = new \modules\Ticket\Ticket();
		$ticket->exists($id);

		// Si la fiche n'existe pas, on redirige vers l'accueil du module
		if(!$ticket->exists) {
			$this->response->redirect('ticket');
		}
		else {
			$this->menu_actif = 'ticket_index';
			$this->titre_page = pun_htmlspecialchars($ticket->infos['ticketname']);
			
			$this->view->with('ticket', $ticket->infos);
			$this->view->with('comments', $ticket->getComment());
			$this->view->with('status', $ticket->getStatus());
			$this->view->with('type', $ticket->getType());

			$this->makeView();
		}
	}

	public function editStatus() {
		// On teste le ticket
		$id = intval($this->request->getData('id'));
		$ticket = new \modules\Ticket\Ticket();
		$ticket->exists($id);

		// Si la fiche n'existe pas, on redirige vers l'accueil du module
		if(!$ticket->exists)
			$this->response->redirect('ticket');

		// On s'assure que l'on a le droit de modifier le statut
		if($this->user->infos['id'] != 2)
			$this->response->redirect('ticket/'.$id);

		$status = $ticket->getStatus();

		// Est-ce que le formulaire a été validé ?
		if($this->request->postExists('statusid')) {
			$statusid = intval($this->request->postData('statusid'));
			// On génère le message de changement de statut du ticket
			$comment = new \modules\Comment\Comment();
			$datas = array(
				'ticketid' => $id,
				'message' => 'Changement de statut : '.$status[$statusid],
				'userid' => $this->user->infos['id']
				);

			$comment->hydrate($datas);
			$new_id = $comment->add();
			
			// On met à jour l'information de statut
			$ticket->edit(array('statusid' => $statusid));
			
			// On redirige vers le ticket
			$this->response->redirect('ticket/'.$id.'#'.$new_id);
		}
		else {
			$this->menu_actif = 'ticket_index';
			$this->titre_page = 'Modifier le statut du ticket';
			
			$this->view->with('ticket', $ticket->infos);
			$this->view->with('status', $status);
			$this->makeView();
		}
	}
}