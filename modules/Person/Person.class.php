<?php
namespace modules\Person;

class Person extends \library\BaseModel {
    /**
     * Person::__construct()
     * 
     * @return void
     */
    public function __construct() {
    	parent::__construct();
        $this->table = 'person';
        $this->key = 'personid';
        $this->time = true;
        $this->picture = 'picture';
        
        $this->schema = array(
        'personid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID de la personne'),
        'fullname' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => 'Nom complet de la personne'),
        'code' => array('fieldtype' => 'INT', 'required' => false, 'default' => 0, 'publicname' => 'Code Allocine'),
        'tmdbid' => array('fieldtype' => 'INT', 'required' => false, 'default' => 0, 'publicname' => 'Code TMDB')
        );
    }

    public function getFilms($id, $type) {
        $type = intval($type);
        if(!in_array($type, array(1, 2)))
            $type = 1;

        $result = $this->db->query('SELECT m.*, mp.role FROM movie AS m INNER JOIN movie_person AS mp ON m.movieid = mp.movieid AND mp.personid = '.$this->db->escape(intval($id)).' AND mp.type = \''.$type.'\' ORDER BY datesortie DESC');

        $films = array();
        while($cur = $this->db->fetch_assoc($result)) {
            $films[] = $cur;
        }
        return $films;
    }

    public function getFilmsIds($id) {
        $result = $this->db->query('SELECT DISTINCT m.tmdbid FROM movie AS m INNER JOIN movie_person AS mp ON m.movieid = mp.movieid AND mp.personid = '.$this->db->escape(intval($id)).' ORDER BY m.tmdbid');

        $films = array();
        while($cur = $this->db->fetch_assoc($result)) {
            $films[$cur['tmdbid']] = $cur;
        }
        return $films;
    }
}