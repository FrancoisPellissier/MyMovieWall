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
		$result = $this->db->query('SELECT p.personid, p.fullname, mp.role, mp.type FROM movie_person AS mp INNER JOIN person AS p ON mp.personid = p.personid AND mp.movieid = '.$this->infos['movieid'].' ORDER BY mp.type, mp.roleid');

		$acteurs = array();
		$realisateurs = array();
		
		while($cur = $this->db->fetch_assoc($result)) {
			if($cur['type'] == '1') {
				$acteurs[] = array(
					'personid' => $cur['personid'],
					'fullname' => $cur['fullname'],
					'role' => $cur['role'],
					'folder' => 'img/person/'.intval($cur['personid'] / 100).'/'
					);
				}
			if($cur['type'] == '2') {
				$realisateurs[] = array(
					'personid' => $cur['personid'],
					'fullname' => $cur['fullname'],
					'role' => '',
					'folder' => 'img/person/'.intval($cur['personid'] / 100).'/'
					);
				}
		}
		$this->infos['acteurs'] = $acteurs;
		$this->infos['realisateurs'] = $realisateurs;

		// Récupération des genres
		$result = $this->db->query('SELECT g.genreid, g.genrename FROM movie_genre AS mg INNER JOIN genre AS g ON mg.genreid = g.genreid AND mg.movieid = '.$this->infos['movieid']);

		$genres = array();
		while($cur = $this->db->fetch_assoc($result))
			$genres[] = array('genreid' => $cur['genreid'], 'genrename' => $cur['genrename']);
		
		$this->infos['genres'] = $genres;
	}

	public function getFilms() {
		$result = $this->db->query('SELECT * FROM '.$this->table.' ORDER BY titrevf');

		$films = array();
		while($cur = $this->db->fetch_assoc($result)) {
			$films[] = $cur;
		}
		return $films;
	}

	public function getFilmsGenre($genreid) {
		$result = $this->db->query('SELECT m.* FROM movie AS m INNER JOIN movie_genre AS mg ON m.movieid = mg.movieid AND mg.genreid = '.$this->db->escape(intval($genreid)).' ORDER BY titrevf');

		$films = array();
		while($cur = $this->db->fetch_assoc($result)) {
			$films[] = $cur;
		}
		return $films;
	}

	public function getLastFilms($limit = 18) {
		$limit = intval($limit);
		if($limit < 1 OR $limit > 100)
			$limit = 18;

		$result = $this->db->query('SELECT * FROM movie ORDER BY created_at DESC LIMIT '.$limit);

		$films = array();
		while($cur = $this->db->fetch_assoc($result)) {
			$films[] = $cur;
		}
		return $films;
	}

	public function search($keywords) {
		$datas = array();

		if(str_replace(' ', '', $keywords) != '') {
			$words = explode(' ', $this->db->escape($keywords));

			// On parcourt les mots clés pour supprimer les articles
			foreach($words AS $id => $word) {
				if(in_array($word, array('le', 'la', 'les', 'un', 'une', 'des', 'de', 'à', 'the', 'of', 'and', 'a')))
					unset($words[$id]);
			}

			$sql = 'WHERE titrevf LIKE \'%'.implode('%\' OR titrevf LIKE \'%', $words).'%\'';
			$sql .= ' OR titrevo LIKE \'%'.implode('%\' OR titrevo LIKE \'%', $words).'%\'';
			$result = $this->db->query('SELECT * FROM movie '.$sql.' ORDER BY titrevf');

			while($cur = $this->db->fetch_assoc($result))
				$datas[] = $cur;
		}

		return $datas;
	}
}
