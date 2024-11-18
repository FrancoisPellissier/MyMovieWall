<?php
namespace modules\Login;

class Login extends \modules\User\User {
	public function login($username, $password) {
		global $pun_config;

	// On regarde si un utilisateur existe avec ce couple username / password
	$result = $this->db->query('SELECT * FROM users WHERE `username` = \''.$this->db->escape($username).'\' AND `password` = \''.pun_hash($password).'\'') or error('Unable to fetch user info', __FILE__, __LINE__, $db->error());

	// Erreur d'identifiants si on ne trouve personne
	if(!$this->db->num_rows($result))
		return false;
	else {
		$cur_user = $this->db->fetch_assoc($result);

		// Update the status if this is the first time the user logged in
		if ($cur_user['group_id'] == PUN_UNVERIFIED) {
			$this->db->query('UPDATE users SET group_id = 4 WHERE id = '.$cur_user['id']) or error('Unable to update user status', __FILE__, __LINE__, $db->error());
		}

		// On supprime la ligne online correspondant a cet invité
		$this->db->query('DELETE FROM online WHERE ident=\''.$this->db->escape(get_remote_address()).'\'') or error('Unable to delete from online list', __FILE__, __LINE__, $db->error());

		$expire = ($save_pass == '1') ? time() + 1209600 : time() + $pun_config['o_timeout_visit'];
		pun_setcookie($cur_user['id'], pun_hash($password), $expire);

		return true;
		}
	}

	public function logout($pun_user) {
		// Remove user from "users online" list
		$this->db->query('DELETE FROM '.$db->prefix.'online WHERE user_id='.$pun_user['id']) or error('Unable to delete from online list', __FILE__, __LINE__, $db->error());

		// Update last_visit (make sure there's something to update it with)
		if (isset($pun_user['logged']))
			$this->db->query('UPDATE '.$db->prefix.'users SET last_visit='.$pun_user['logged'].' WHERE id='.$pun_user['id']) or error('Unable to update user visit data', __FILE__, __LINE__, $db->error());

		pun_setcookie(1, pun_hash(uniqid(rand(), true)), time() + 31536000);
	}

	public function sendChangePassword($username, $password, $email) {
        require PUN_ROOT.'include/email.php';

        // Load the "welcome" template
        $mail_tpl = trim(file_get_contents(ROOT.'assets/mail_template/change_password.tpl'));

        // The first row contains the subject
        $first_crlf = strpos($mail_tpl, "\n");
        $mail_subject = trim(substr($mail_tpl, 8, $first_crlf-8));
        $mail_message = trim(substr($mail_tpl, $first_crlf));

        $mail_subject = str_replace('<board_title>', 'My Movie Wall', $mail_subject);
        $mail_message = str_replace('<base_url>', WWW_ROOT, $mail_message);
        $mail_message = str_replace('<username>', $username, $mail_message);
        $mail_message = str_replace('<password>', $password, $mail_message);
        $mail_message = str_replace('<login_url>', WWW_ROOT.'login', $mail_message);
        $mail_message = str_replace('<board_mailer>', 'My Movie Wall', $mail_message);

        pun_mail($email, $mail_subject, $mail_message);
    }
}