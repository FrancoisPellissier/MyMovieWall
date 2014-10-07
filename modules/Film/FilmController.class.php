<?php
namespace modules\Film;

class FilmController extends \library\BaseController {
	
	public function index() {
		
		$this->view->with('request', $this->request);
		$this->view->make();
	}

	public function searchAllocine() {

		if($this->request->postExists('keyword')) {
			$keyword = $this->request->postData('keyword');

			$allocine = new \modules\Allocine\Allocine();
			$datas = $allocine->search($keyword);
			$this->view->with('datas', $datas);
		}

		$this->view->make();
	}
}