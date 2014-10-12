<?php
namespace modules\Film;

class Film extends \library\BaseModel {
	/**
	 * Film::__construct()
	 * 
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	    $this->table = 'movie';
	    $this->key = 'movieid';
	    $this->time = true;
	    $this->picture = 'affiche';
	    
	    $this->schema = array(
	    'movieid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID du film'),
	    'titrevo' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => 'Titre original'),
	    'titrevf' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => 'Titre français'),
	    'datesortie' => array('fieldtype' => 'DATE', 'required' => false, 'default' => NULL, 'publicname' => 'Date sortie en France'),
	    'duree' => array('fieldtype' => 'INT', 'required' => false, 'default' => '0', 'publicname' => 'Durée'),
	    'synopsis' => array('fieldtype' => 'TEXT', 'required' => false, 'default' => NULL, 'publicname' => 'Résumé'),
	    'realisateur' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => 'Réalisateur'),
	    'acteur' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => 'Acteurs principaux'),
		'code' => array('fieldtype' => 'INT', 'required' => false, 'default' => 0, 'publicname' => 'Code Allocine')
	    );
	}

	public function getInfos() {
		$result = $this->db->query('SELECT p.personid, p.fullname, mp.role FROM movie_person AS mp INNER JOIN person AS p ON mp.personid = p.personid AND mp.movieid = '.$this->infos['movieid'].' AND mp.type = \'1\' ORDER BY mp.roleid');

		$acteurs = array();
		
		while($cur = $this->db->fetch_assoc($result)) {
			$acteurs[] = array(
				'personid' => $cur['personid'],
				'fullname' => $cur['fullname'],
				'role' => $cur['role'],
				'folder' => 'img/person/'.intval($cur['personid'] / 100).'/'
				);
		}
		$this->infos['acteurs'] = $acteurs;
	}

	public function getFilms() {
		$result = $this->db->query('SELECT * FROM '.$this->table.' ORDER BY titrevf');

		$last = array();
		while($cur = $this->db->fetch_assoc($result)) {
			$last[] = $cur;
		}
		return $last;
	}
}
