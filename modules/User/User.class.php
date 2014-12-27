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

    public function changePassword($password1, $password2) {
        global $pun_config;
        
        // On vérifie la présence des mots de passes et leur égalité
        if(!empty($password1) && !empty($password2) && $password1 == $password2) {
        $this->db->query(\library\Query::update('users', array('password' => pun_hash($password1)), array('id' => $this->infos['id']), false))or error($this->db->error());

            pun_setcookie($this->infos['id'], pun_hash($password1), time() + $pun_config['o_timeout_visit']);
            return true;
        }
        else
            return false;
    }

    public function changeRealname($realname) {
        
        if(!empty($realname) && $realname != $this->infos['realname']) {
           $this->db->query(\library\Query::update('users', array('realname' => $realname), array('id' => $this->infos['id']), false))or error($this->db->error());
            return true;
        }
        else
            return false;
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
        $result = $this->db->query('SELECT viewid, type, viewdate FROM users_views WHERE userid = '.$this->infos['id'].' AND movieid = '.intval($movieid).' ORDER BY viewdate DESC');

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

    public function getLastBiblio($limit = 6) {
        $result = $this->db->query('SELECT m.* FROM movie AS m INNER JOIN users_biblio AS ub ON m.movieid = ub.movieid AND ub.userid = '.$this->infos['id'].' ORDER BY ub.updated_at DESC LIMIT '.$limit);

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

    public function getGenres() {
            $result = $this->db->query('SELECT g.* FROM movie AS m INNER JOIN users_biblio AS ub ON m.movieid = ub.movieid AND ub.userid = '.$this->infos['id'].' INNER JOIN movie_genre AS mg ON m.movieid = mg.movieid INNER JOIN genre AS g ON mg.genreid = g.genreid GROUP BY g.genreid ORDER BY genrename');

        $genres = array();
        while($cur = $this->db->fetch_assoc($result)) {
            $genres[$cur['genreid']] = $cur;
        }
        return $genres;
    }

    public function getBiblio($genreid) {
        $result = $this->db->query('SELECT m.* FROM movie AS m INNER JOIN users_biblio AS ub ON m.movieid = ub.movieid AND ub.userid = '.$this->infos['id'].' INNER JOIN movie_genre AS mg ON m.movieid = mg.movieid AND mg.genreid = '.$this->db->escape(intval($genreid)).' ORDER BY titrevf');

        $films = array();
        while($cur = $this->db->fetch_assoc($result)) {
            $films[] = $cur;
        }
        return $films;
    }

    public function delView($movieid, $viewid) {
        $result = $this->db->query('SELECT viewid FROM users_views WHERE userid = '.$this->infos['id'].' AND movieid = '.intval($movieid).' AND viewid = '.$viewid.' ORDER BY viewdate DESC')or error('Impossible de tester le visionnage', __FILE__, __LINE__, $this->db->error());

        if($this->db->num_rows($result))
            $this->db->query('DELETE FROM users_views WHERE viewid ='.$viewid)or error('Impossible de supprimer le visionnage', __FILE__, __LINE__, $this->db->error());
    }

    public function addWish($movieid, $type, $value = 1) {
        // Le film existe ?
        $film = new \modules\Film\Film();
        $film->exists($movieid);

        if($film->exists && in_array($type, array('view', 'buy'))) {
            $datas = array(
                'userid' => $this->infos['id'],
                'movieid' => $movieid,
                $type => $value
                );
            
            $this->db->query(\library\Query::insertORupdate('users_wish', $datas, array($type), true))or error($this->db->error());
            // On supprime la ligne s'il ne reste plus aucune possession
            $this->db->query('DELETE FROM users_wish WHERE movieid = '.$movieid.' AND userid = '.$this->infos['id'].' AND buy = \'0\' AND view = \'0\'');
        }
    }

    public function wishFilm($movieid) {
        $result = $this->db->query('SELECT view, buy FROM users_wish WHERE userid = '.$this->infos['id'].' AND movieid = '.intval($movieid));

        if($this->db->num_rows($result))
            $this->infos['wishFilm'] = $this->db->fetch_assoc($result);
        else
            $this->infos['wishFilm'] = array('view' => '0', 'buy' => '0');
    }

    public function getWishlist() {
        $result = $this->db->query('SELECT m.* FROM movie AS m INNER JOIN users_wish AS uw ON m.movieid = uw.movieid AND uw.userid = '.$this->infos['id'].' AND uw.buy = \'1\' ORDER BY titrevf, titrevo');

        $wish = array();
        while($cur = $this->db->fetch_assoc($result)) {
            $wish[] = $cur;
        }
        return $wish;
    }

    public function getToWatchlist() {
        $result = $this->db->query('SELECT m.* FROM movie AS m INNER JOIN users_wish AS uw ON m.movieid = uw.movieid AND uw.userid = '.$this->infos['id'].' AND uw.view = \'1\' ORDER BY datesortie');

        $wish = array();
        while($cur = $this->db->fetch_assoc($result)) {
            $wish[] = $cur;
        }
        return $wish;
    }
}