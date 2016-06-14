<?php
namespace modules\Friend;

class Friend extends \library\BaseModel {
    /**
     * Person::__construct()
     * 
     * @return void
     */
    private $user;

    public function __construct($user) {
        parent::__construct();
        $this->user = $user;
        $this->table = 'users_friend';
    }

    // Demander quelqu'un en ami
    public function ask($userid) {
        $userid = intval($userid);

        // La validation est-elle bien en attente de ma part ?
        if($this->isFriendToMe($userid) == 0) {
            // Insertion de la demande
            $datas = array('userid' => $this->user['id'], 'friend_userid' => $userid, 'asked' => 'ask', 'validated_at' => NULL);
            $this->db->query(\library\Query::insert($this->table, $datas, true, true))or error($this->db->error());

            // Insertion du retour
            $datas = array('friend_userid' => $this->user['id'], 'userid' => $userid, 'asked' => 'answer', 'validated_at' => NULL);
            $this->db->query(\library\Query::insert($this->table, $datas, true, true))or error($this->db->error());

            $this->db->query('UPDATE '.$this->table.' SET validated_at = NULL WHERE validated_at = \'0000-00-00 00:00:00\'');

            return true;
        }
        else {
            return false;
        }
    }

    // Récupérer les demandes en attente de validation
    public function getUnvalidated() {
        $result = $this->db->query('SELECT u.id, u.realname, f.asked, f.created_at FROM '.$this->table.' AS f INNER JOIN users AS u ON f.friend_userid = u.id AND f.userid = '.$this->user['id'].' AND validated_at IS NULL ORDER BY asked DESC, f.created_at')or error('Impossible de récupérer les amis en attente de validation', __FILE__, __LINE__, $this->db->error());

        $friends = array();

        if($this->db->num_rows($result)) {
            while($cur = $this->db->fetch_assoc($result))
                $friends[$cur['id']] = $cur;
        }
        return $friends;   
    }

    // Accepter la demander d'un ami
    public function validate($userid) {
        $userid = intval($userid);

        // La validation est-elle bien en attente de ma part ?
        if($this->isFriendToMe($userid) == 4) {
            $where = $userid. ', '.$this->user['id'];

            $sql = 'UPDATE '.$this->table.' SET validated_at = NOW(), updated_at = NOW() WHERE userid IN('.$where.') AND friend_userid IN('.$where.')';
            $this->db->query($sql)or error($this->db->error());
            return true;
        }
        else {
            return false;
        }
    }

    // Refuser la demande d'un ami ou supprimer un lien existant
    public function delete($userid) {
        $userid = intval($userid);
        $where = $userid. ', '.$this->user['id'];

        $this->db->query('DELETE FROM '.$this->table.' WHERE userid IN('.$where.') AND friend_userid IN('.$where.')')or error('Impossible de supprimer le lien', __FILE__, __LINE__, $this->db->error());
    }

    // Cette personne est-elle mon amie ?
    public function isFriendToMe($friendid) {
        $result = $this->db->query('SELECT userid, asked, validated_at FROM '.$this->table.' WHERE userid = '.intval($friendid).' AND friend_userid = '.$this->user['id'])or error('Impossible de tester le lien', __FILE__, __LINE__, $this->db->error());

        if($this->db->num_rows($result)) {
            $cur = $this->db->fetch_assoc($result);
            
            // Lien en attente
            if($cur['validated_at'] == null) {
                // 3 : En attente de sa réponse
                if($cur['ask'] == 'ask') {
                    return 3;
                }
                // 4 : En attente de ma réponse
                else {
                    return 4;
                }

            }
            // 1 : C'est mon ami
            else {
                return 1;
            }
        }
        // 0 : Ce n'est pas mon ami et il n'y a pas de demande en cours
        else {
            return 0;
        }
    }

    // Envoi de l'email de demande ou de validation
    public function sendEmail($user, $type) {
        require PUN_ROOT.'include/email.php';

        if($type == 'ask') {
            $mail_tpl = trim(file_get_contents(ROOT.'assets/mail_template/friend_ask.tpl'));
            $email = $user->infos['email'];
        }
        else if($type == 'validate') {
            $mail_tpl = trim(file_get_contents(ROOT.'assets/mail_template/friend_valide.tpl'));
            $email = $user->infos['email'];
        }

        // Un type d'email a été choisi ?
        if($mail_tpl != '') {
            // The first row contains the subject
            $first_crlf = strpos($mail_tpl, "\n");
            $mail_subject = trim(substr($mail_tpl, 8, $first_crlf-8));
            $mail_subject = str_replace('<user_name>', $this->user['realname'], $mail_subject);

            $mail_message = trim(substr($mail_tpl, $first_crlf));
            $mail_message = str_replace('<user_name>', $this->user['realname'], $mail_message);
            $mail_message = str_replace('<user_url>', WWW_ROOT.'user/'.$this->user['id'], $mail_message);
            $mail_message = str_replace('<friend_url>', WWW_ROOT.'friend', $mail_message);
            $mail_message = str_replace('<board_mailer>', 'My Movie Wall', $mail_message);

            pun_mail($email, $mail_subject, $mail_message);
        }
    }

    // Récupérer la liste de mes amis validés
    public function getFriends() {
        $result = $this->db->query('SELECT u.id, u.realname FROM '.$this->table.' AS f INNER JOIN users AS u ON f.friend_userid = u.id AND f.userid = '.$this->user['id'].' AND f.validated_at IS NOT NULL ORDER BY u.realname')or error('Impossible de récupérer les amis', __FILE__, __LINE__, $this->db->error());

        $friends = array();
        if($this->db->num_rows($result)) {
            while($cur = $this->db->fetch_assoc($result))
                $friends[$cur['id']] = $cur;
        }
        return $friends;
    }

    // Récupérer les informations associées à chaque utilisateur
    public function getFriendsInfos() {
        $infos = array();

        // Nombre de films dans leur bibliothèque
        $result = $this->db->query('SELECT userid, count(*) AS nb FROM users_biblio GROUP BY userid')or error('Impossible de récupérer le nb de films par ami', __FILE__, __LINE__, $this->db->error());

        while($cur = $this->db->fetch_assoc($result))
            $infos[$cur['userid']]['biblio'] = $cur['nb'];

        // Nombre de films vus
        $result = $this->db->query('SELECT userid, count(*) AS nb FROM users_views GROUP BY userid')or error('Impossible de récupérer le nb de films vu par ami', __FILE__, __LINE__, $this->db->error());

        while($cur = $this->db->fetch_assoc($result))
            $infos[$cur['userid']]['view'] = $cur['nb'];

        return $infos;
    }

    // Récupérer la liste de tous les utilisateurs hors MOI et mes amis (quelque soit le statut)
    public function getAllOut() {
        $result = $this->db->query('SELECT u.id, u.realname FROM users AS u LEFT JOIN users_friend AS f ON u.id = f.friend_userid AND f.userid = '.$this->user['id'].' WHERE u.id != 1 AND u.id != '.$this->user['id'].' AND f.userid IS NULL ORDER BY realname')or error('Impossible de récupérer la liste des utilisateurs', __FILE__, __LINE__, $this->db->error());

        $users = array();
        if($this->db->num_rows($result)) {
            while($cur = $this->db->fetch_assoc($result))
                $users[] = $cur;
        }
        return $users;
    }
}