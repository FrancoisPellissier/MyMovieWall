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
        global $pun_user;
    	parent::__construct();
        $this->user = $user;
    }

    public function getFriends() {
        $result = $this->db->query('SELECT u.id, u.realname FROM users_friend AS f INNER JOIN users AS u ON f.friend_userid = u.id AND f.userid = '.$this->user['id'])or error('Impossible de récupérer les amis', __FILE__, __LINE__, $this->db->error());

        $friends = array();
        if($this->db->num_rows($result)) {
            while($cur = $this->db->fetch_assoc($result))
                $friends[$cur['id']] = $cur;
        }
        return $friends;
    }

    public function isFriend() {
        $result = $this->db->query('SELECT u.id, u.realname, f.wishlist FROM users_friend AS f INNER JOIN users AS u ON f.userid = u.id AND f.friend_userid = '.$this->user['id'])or error('Impossible de récupérer ce qui m\'ont en ami', __FILE__, __LINE__, $this->db->error());

        $friends = array();
        if($this->db->num_rows($result)) {
            while($cur = $this->db->fetch_assoc($result))
                $friends[$cur['id']] = $cur;
        }
        return $friends;
    }

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

    public function getAll() {
        $result = $this->db->query('SELECT id, realname FROM users WHERE id != 1 ORDER BY realname')or error('Impossible de récupérer la liste des utilisateurs', __FILE__, __LINE__, $this->db->error());

        $users = array();
        if($this->db->num_rows($result)) {
            while($cur = $this->db->fetch_assoc($result))
                $users[] = $cur;
        }
        return $users;
    }

    public function addFriend($userid) {
        $userid = intval($userid);

        $datas = array('userid' => $this->user['id'], 'friend_userid' => $userid);
        $this->db->query(\library\Query::insert('users_friend', $datas, true, true))or error($this->db->error());
    }

    public function delFriend($userid) {
        $this->db->query('DELETE FROM users_friend WHERE userid = '.$this->user['id'].' AND friend_userid = '.intval($userid))or error('Impossible de supprimer le lien', __FILE__, __LINE__, $this->db->error());

    }

    public function editRight() {

    }

    public function isFriendToMe($friendid) {
        $result = $this->db->query('SELECT userid FROM users_friend WHERE userid = '.intval($friendid).' AND friend_userid = '.$this->user['id'])or error('Impossible de tester le lien', __FILE__, __LINE__, $this->db->error());

        if($this->db->num_rows($result))
            return true;
        else
            return false;
    }

    public function sendEmail($user) {
        require PUN_ROOT.'include/email.php';

        if($this->isFriendToMe($user->infos['id']))
            $mail_tpl = trim(file_get_contents(ROOT.'assets/mail_template/friend_added_friend.tpl'));
        else
            $mail_tpl = trim(file_get_contents(ROOT.'assets/mail_template/friend_added_notfriend.tpl'));

        // The first row contains the subject
        $first_crlf = strpos($mail_tpl, "\n");
        $mail_subject = trim(substr($mail_tpl, 8, $first_crlf-8));
        $mail_subject = str_replace('<user_name>', $this->user['realname'], $mail_subject);

        $mail_message = trim(substr($mail_tpl, $first_crlf));
        $mail_message = str_replace('<user_name>', $this->user['realname'], $mail_message);
        $mail_message = str_replace('<user_url>', WWW_ROOT.'user/'.$this->user['id'], $mail_message);
        $mail_message = str_replace('<addfriend_url>', WWW_ROOT.'friend/add/'.$this->user['id'], $mail_message);
        $mail_message = str_replace('<board_mailer>', 'My Movie Wall', $mail_message);

        pun_mail($user->infos['email'], $mail_subject, $mail_message); 
    }
}