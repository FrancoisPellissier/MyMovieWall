<?php
namespace modules\Avis;

class Avis extends \library\BaseModel {
	public function __construct() {
		parent::__construct();
	    $this->table = 'avis';
	    $this->key = 'avisid';
	    $this->time = true;
	    
	    $this->schema = array(
	    'avisid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID de l avis'),
	    'movieid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID du film associe'),
	    'userid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID du créateur'),
	    'message' => array('fieldtype' => 'TEXT', 'required' => false, 'default' => '', 'publicname' => 'Message de l avis')
	    );
	}

	public function getFilmAvis($movieid) {
		$result = $this->db->query('SELECT a.avisid, a.userid, a.message, a.created_at, u.realname FROM avis AS a INNER JOIN users AS u ON u.id = a.userid AND movieid = '.intval($movieid).' ORDER BY a.created_at DESC')or error('Impossible de recuperer les avis de ce film', __FILE__, __LINE__, $this->db->error());

		$avis = array();
		while($cur = $this->db->fetch_assoc($result))
			$avis[] = $cur;

		return $avis;
	}
}