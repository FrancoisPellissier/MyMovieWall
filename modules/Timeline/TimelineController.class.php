<?php
namespace modules\Timeline;

class TimelineController extends \library\BaseController {
    public function showLast() {
        $this->titre_page = 'Timeline';
        $this->menu_actif = 'timeline';
        $this->side_section = 'site';
        $this->side_item = 'timeline';

        $timeline = new \modules\Timeline\Timeline();
       
        $this->view->with('timelines', $timeline->getLast());
        $this->makeView();
    }
}