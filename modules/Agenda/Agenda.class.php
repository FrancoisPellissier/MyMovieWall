<?php
namespace modules\Agenda;

class Agenda extends \library\BaseModel {
	public function getFilms() {
		$result = $this->db->query('SELECT * FROM movie WHERE datesortie > CURDATE() ORDER BY datesortie, titrevf, titrevo');

	    $last = array();
	    while($cur = $this->db->fetch_assoc($result)) {
	        $last[] = $cur;
	    }
	    return $last;
	}
}