<?php
namespace modules\Agenda;

class AgendaController extends \library\BaseController {

	public function showAgenda() {
		$this->titre_page = 'Agenda';
		$this->menu_actif = 'agenda_index';
		$this->side_section = 'site';
		$this->jsfile = 'agenda';

		// On récupère les sorties à venir
		$agenda = new Agenda();
		$this->view->with('films', $agenda->getFilms());
		$this->view->with('towatch', $this->user->getToWatchList(1));
		$this->view->with('viewed', $this->user->getViewedList());
		
		$this->makeView();
	}
}