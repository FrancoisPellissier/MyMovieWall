<?php
namespace modules\Index;

class Index extends \library\BaseModel {
	public function getLastOut() {
	    $result = $this->db->query('SELECT * FROM movie WHERE datesortie <= CURDATE() ORDER BY datesortie DESC, titrevf, titrevo DESC LIMIT 6');

	    $last = array();
	    while($cur = $this->db->fetch_assoc($result)) {
	        $last[] = $cur;
	    }
	    return $last;
	}

	public function getNextOut() {
	    $result = $this->db->query('SELECT * FROM movie WHERE datesortie > CURDATE() ORDER BY datesortie, titrevf, titrevo DESC LIMIT 6');

	    $last = array();
	    while($cur = $this->db->fetch_assoc($result)) {
	        $last[] = $cur;
	    }
	    return $last;
	}	

	public function getLastCreated() {
	    $result = $this->db->query('SELECT * FROM movie ORDER BY created_at DESC LIMIT 6');

	    $last = array();
	    while($cur = $this->db->fetch_assoc($result)) {
	        $last[] = $cur;
	    }
	    return $last;
	}
}