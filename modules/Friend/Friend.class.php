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
}