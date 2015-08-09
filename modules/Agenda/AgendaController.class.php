<?php
namespace modules\Agenda;

class AgendaController extends \library\BaseController {

	public function showAgenda() {
		$this->titre_page = 'Agenda';
		$this->menu_actif = 'agenda_index';

		// On récupère les sorties à venir
		$agenda = new Agenda();
		$this->view->with('films', $agenda->getFilms());
		$this->view->with('towatch', $this->user->getToWatchList(1));
		
		$this->makeView();
	}
}