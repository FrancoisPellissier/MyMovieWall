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
}
