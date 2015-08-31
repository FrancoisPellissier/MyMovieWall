<?php
namespace modules\Ticket;

class Ticket extends \library\BaseModel {
	public function __construct() {
		parent::__construct();
	    $this->table = 'ticket';
	    $this->key = 'ticketid';
	    $this->time = true;
	    
	    $this->schema = array(
	    'ticketid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID du ticket'),
	    'ticketname' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => 'Titre ticket'),
	    'userid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID du créateur'),
	    'typeid' => array('fieldtype' => 'INT', 'required' => false, 'default' => NULL, 'publicname' => 'ID du type de ticket'),
	    'statusid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '0', 'publicname' => 'ID du statut de ticket'),
	    'firstcommentid' => array('fieldtype' => 'INT', 'required' => false, 'default' => NULL, 'publicname' => 'ID du 1er commentaire')
	    );
	}

	// Récupération de la liste des tickets avec informations complémentaire
	public function getList() {
		$result = $this->db->query('SELECT
			t.ticketid,
			t.ticketname,
			t.created_at,
			t.updated_at,
			t.statusid,
			ts.statusname,
			tt.typename,
			u.realname
			FROM ticket AS t
			INNER JOIN users AS u ON t.userid = u.id
			INNER JOIN ticket_status AS ts ON t.statusid = ts.statusid
			INNER JOIN ticket_type AS tt ON t.typeid = tt.typeid
			ORDER BY ts.ordre, t.updated_at DESC')or error('Impossible de récupérer la liste des tickets', __FILE__, __LINE__, $this->db->error());

		$tickets = array();
		while($cur = $this->db->fetch_assoc($result))
			$tickets[] = $cur;

		return $tickets;
	}

	// Récupérer la liste des dernières activités
	public function getLastActivites() {
		$result = $this->db->query('SELECT
				c.commentid,
				c.userid,
				u.realname,
				c.message,
				c.created_at,
				c.updated_at,
				t.ticketid,
				t.ticketname,
				t.firstcommentid
			FROM ticket_comment AS c
			INNER JOIN users AS u ON c.userid = u.id
			INNER JOIN ticket AS t ON c.ticketid = t.ticketid
			ORDER BY commentid DESC
			LIMIT 10')or error('Impossible de récupérer la liste des dernières activités', __FILE__, __LINE__, $this->db->error());

		$comments = array();
		while($cur = $this->db->fetch_assoc($result))
			$comments[] = $cur;

		return $comments;
	}

	// Récupération des commentaires d'un ticket
	public function getComment() {
		$result = $this->db->query('SELECT
				c.commentid,
				c.userid,
				u.realname,
				c.message,
				c.created_at,
				c.updated_at
			FROM ticket_comment AS c
			INNER JOIN users AS u ON c.userid = u.id AND c.ticketid = '.$this->infos['ticketid'].'
			ORDER BY commentid')or error('Impossible de récupérer la liste des commentaires du ticket', __FILE__, __LINE__, $this->db->error());

		$comments = array();
		while($cur = $this->db->fetch_assoc($result))
			$comments[] = $cur;

		return $comments;
	}

	// Récupération de la liste des types de ticket
	public function getType() {
		$result = $this->db->query('SELECT typeid, typename FROM ticket_type ORDER BY typeid')or error('Impossible de récupérer la liste des types de ticket', __FILE__, __LINE__, $this->db->error());

		$return = array();
		while($cur = $this->db->fetch_assoc($result))
			$return[$cur['typeid']] = $cur['typename'];

		return $return;
	}

	// Récupération de la liste des statuts de ticket
	public function getStatus() {
		$result = $this->db->query('SELECT statusid, statusname FROM ticket_status ORDER BY statusname')or error('Impossible de récupérer la liste des status de ticket', __FILE__, __LINE__, $this->db->error());

		$return = array();
		while($cur = $this->db->fetch_assoc($result))
			$return[$cur['statusid']] = $cur['statusname'];

		return $return;
	}

	public function subscribe($userid, $type = true) {
		if($type) {
			$this->db->query('INSERT IGNORE INTO ticket_subscribe (userid, ticketid) VALUES('.intval($userid).','.$this->infos['ticketid'].')')or error('Impossible de supprimer l\'abonnement au ticket.', __FILE__, __LINE__, $this->db->error());
		}

		else {
			$this->db->query('DELETE FROM ticket_subscribe WHERE userid = '.intval($userid).' AND ticketid = '.$this->infos['ticketid'])or error('Impossible de supprimer l\'abonnement au ticket.', __FILE__, __LINE__, $this->db->error());
		}
	}
}