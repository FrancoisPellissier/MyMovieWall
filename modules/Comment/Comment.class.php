<?php
namespace modules\Comment;

class Comment extends \library\BaseModel {
	public function __construct() {
		parent::__construct();
	    $this->table = 'ticket_comment';
	    $this->key = 'commentid';
	    $this->time = true;
	    
	    $this->schema = array(
	    'commentid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID du commentaire'),
	    'ticketid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID du ticket associé'),
	    'userid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID du créateur'),
	    'message' => array('fieldtype' => 'TEXT', 'required' => false, 'default' => '', 'publicname' => 'Message du commentaire')
	    );
	}
}