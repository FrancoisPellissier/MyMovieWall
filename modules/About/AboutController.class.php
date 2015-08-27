<?php
namespace modules\About;

class AboutController extends \library\BaseController {
	public function showPage($page) {
		// Vérifier l'existence du fichier
		if(file_exists('modules/About/text/'.$page.'.txt')) {
			$texte = file_get_contents('modules/About/text/'.$page.'.txt');
			$parser = new \library\Parsedown();
			$this->view->with('texte', $parser->text($texte));
		}
		else
			$this->response->redirect('');
	}

	public function about() {
		$this->titre_page = 'A propos';
		$this->menu_actif = 'about';
		$this->side_section = 'site';
		$this->showPage('about');
		$this->makeView();
	}

	public function mentions() {
		$this->titre_page = 'Mentions légales';
		$this->menu_actif = '';
		$this->side_section = 'site';
		$this->showPage('mentions');
		$this->makeView();
	}

	public function syntaxe() {
		$this->titre_page = 'Syntaxe';
		$this->menu_actif = '';
		$this->side_section = 'site';
		$this->showPage('syntaxe');
		$this->makeView();
	}
}