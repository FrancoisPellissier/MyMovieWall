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

	public function sendComment($ticket, $userid) {
	    require PUN_ROOT.'include/email.php';

	    // Load the "welcome" template
	    $mail_tpl = trim(file_get_contents(ROOT.'assets/mail_template/ticket_comment.tpl'));

	    // The first row contains the subject
	    $first_crlf = strpos($mail_tpl, "\n");
	    $mail_subject = trim(substr($mail_tpl, 8, $first_crlf-8));
	    $mail_subject = str_replace('<ticket_title>', $ticket->infos['ticketname'], $mail_subject);

	    $mail_message = trim(substr($mail_tpl, $first_crlf));
	    $mail_message = str_replace('<ticket_title>', $ticket->infos['ticketname'], $mail_message);
	    $mail_message = str_replace('<ticket_num>', $ticket->infos['ticketid'], $mail_message);
	    $mail_message = str_replace('<ticket_url>', WWW_ROOT.'ticket/'.$this->infos['ticketid'].'#'.$this->infos['commentid'], $mail_message);
	    $mail_message = str_replace('<board_mailer>', 'My Movie Wall', $mail_message);

	    // On récupère les personnes inscrits
	    $result = $this->db->query('SELECT u.id, email FROM `ticket_subscribe` AS ts INNER JOIN users AS u ON ts.userid = u.id AND ts.ticketid = '.$this->infos['ticketid'].' AND u.id != '.intval($userid))or error('Impossible de récupérer les abonnements à ce ticket', __FILE__, __LINE__, $this->db->error());

	    while($cur = $this->db->fetch_assoc($result)) {

	    	pun_mail($cur['email'], $mail_subject, $mail_message);
	    }
	}
}