<?php
namespace modules\User;

class User extends \library\BaseModel {
    /**
     * Person::__construct()
     * 
     * @return void
     */
    public function __construct($new = false) {
        global $pun_user;
    	parent::__construct();
        $this->table = 'users';
        $this->key = 'id';
        $this->time = false;

        if(!$new)
            $this->infos = $pun_user;
        
        $this->schema = array(
        'id' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID du user'),
        'username' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => ''),
        'password' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => ''),
        'email' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => ''),
        'realname' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => ''),
        'group_id' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID du groupe'),
        'language' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => ''),
        'style' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => ''),
        'registered' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => ''),
        'last_visit' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => ''),
        'registration_ip' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => '')
        );
    }

    public function addBiblio($movieid, $type, $value = 1) {
        // Le film existe ?
        $film = new \modules\Film\Film();
        $film->exists($movieid);

        if($film->exists && in_array($type, array('bluray', 'dvd', 'numerique'))) {
            $datas = array(
                'userid' => $this->infos['id'],
                'movieid' => $movieid,
                $type => $value
                );
            
            $this->db->query(\library\Query::insertORupdate('users_biblio', $datas, array($type), true))or error($this->db->error());
            // On supprime la ligne s'il ne reste plus aucune possession
            $this->db->query('DELETE FROM users_biblio WHERE movieid = '.$movieid.' AND userid = '.$this->infos['id'].' AND bluray = \'0\' AND dvd = \'0\' AND numerique = \'0\'');
        }
    }

    public function hasFilm($movieid) {
        $result = $this->db->query('SELECT bluray, dvd FROM users_biblio WHERE userid = '.$this->infos['id'].' AND movieid = '.intval($movieid));

        if($this->db->num_rows($result))
            $this->infos['hasFilm'] = $this->db->fetch_assoc($result);
        else
            $this->infos['hasFilm'] = array('bluray' => '0', 'dvd' => '0');
    }

    public function addView($movieid, $type, $date = null) {
        $film = new \modules\Film\Film();
        $film->exists($movieid);

        if($film->exists && in_array($type, array('1', '2'))) {

            $datas = array(
                'userid' => $this->infos['id'],
                'movieid' => $movieid,
                'type' => $type
                );

            if($date)
                $datas['viewdate'] = $date;

            $this->db->query(\library\Query::insert('users_views', $datas, true))or error($this->db->error());
        }
    }

    public function hasViewFilm($movieid) {
        $result = $this->db->query('SELECT type, viewdate FROM users_views WHERE userid = '.$this->infos['id'].' AND movieid = '.intval($movieid).' ORDER BY viewdate DESC');

        $this->infos['hasViewFilm'] = array();

        if($this->db->num_rows($result)) {
            while($cur = $this->db->fetch_assoc($result))
                $this->infos['hasViewFilm'][] = $cur;
        }
    }

    public function getLastViews($type = 'all', $index = true) {
        if(!in_array($type, array('1', '2')))
            $type = 'all';

        $result = $this->db->query('SELECT m.*, uv.type, uv.viewdate FROM movie AS m INNER JOIN users_views AS uv ON m.movieid = uv.movieid AND uv.userid = '.$this->infos['id'].($type == 'all' ? '' : ' AND uv.type = \''.$type.'\'').' ORDER BY viewdate DESC, created_at DESC'.($index ? ' LIMIT 6' : ''));

        $last = array();
        while($cur = $this->db->fetch_assoc($result)) {
            $last[] = $cur;
        }
        return $last;
    }

    public function getLastBiblio() {
        $result = $this->db->query('SELECT m.* FROM movie AS m INNER JOIN users_biblio AS ub ON m.movieid = ub.movieid AND ub.userid = '.$this->infos['id'].' ORDER BY ub.created_at DESC LIMIT 6');

        $last = array();
        while($cur = $this->db->fetch_assoc($result)) {
            $last[] = $cur;
        }
        return $last;
    }

    public function emailExists($email) {
        $result = $this->db->query('SELECT email FROM users WHERE email = \''.$this->db->escape($email).'\'')or error('Impossible de tester l\'existence de l\'email', __FILE__, __LINE__, $this->db->error());

         if($this->db->num_rows($result))
            return true;
        else
            return false;
    }
}