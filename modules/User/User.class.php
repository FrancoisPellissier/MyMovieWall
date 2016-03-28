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

        if(!$new) {
            $this->infos = $pun_user;
            $this->getRights();   
        }
        
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
        'registration_ip' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => ''),
        'notif_sortie' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => ''),
        'notif_ticket' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => ''),
        'notif_friend' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => '')
        );
    }

    public function changePassword($password1, $password2, $cookie = true) {
        global $pun_config;
        
        // On vérifie la présence des mots de passes et leur égalité
        if(!empty($password1) && !empty($password2) && $password1 == $password2) {
            $this->db->query(\library\Query::update('users', array('password' => pun_hash($password1)), array('id' => $this->infos['id']), false))or error($this->db->error());

            if($cookie)
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

    public function changeNotification($values) {
        $this->db->query(\library\Query::update('users', $values, array('id' => $this->infos['id']), false))or error($this->db->error());
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
        $result = $this->db->query('SELECT bluray, dvd, numerique FROM users_biblio WHERE userid = '.$this->infos['id'].' AND movieid = '.intval($movieid));

        if($this->db->num_rows($result))
            $this->infos['hasFilm'] = $this->db->fetch_assoc($result);
        else
            $this->infos['hasFilm'] = array('bluray' => '0', 'dvd' => '0', 'numerique' => '0');
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

        $result = $this->db->query('SELECT m.*, uv.type, uv.viewdate, ur.rate FROM movie AS m INNER JOIN users_views AS uv ON m.movieid = uv.movieid AND uv.userid = '.$this->infos['id'].($type == 'all' ? '' : ' AND uv.type = \''.$type.'\'').' LEFT JOIN users_rate AS ur ON uv.userid = ur.userid AND uv.movieid = ur.movieid ORDER BY viewdate DESC, uv.created_at DESC'.($index ? ' LIMIT 6' : ''));

        $last = array();
        while($cur = $this->db->fetch_assoc($result)) {
            $cur['rate'] = $this->displayRate($cur['rate']);
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
            $this->db->query('DELETE FROM users_wish WHERE buy = \'0\' AND view = \'0\'');
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

    public function getToWatchlist($type = '1') {
        if($type == 2)
            $sql = ' AND m.datesortie < ADDDATE(CURDATE(), INTERVAL -1 MONTH) ORDER BY titrevf';
        else
            $sql = ' AND m.datesortie >= ADDDATE(CURDATE(), INTERVAL -1 MONTH) ORDER BY datesortie, titrevf';

        $result = $this->db->query('SELECT m.* FROM movie AS m INNER JOIN users_wish AS uw ON m.movieid = uw.movieid AND uw.userid = '.$this->infos['id'].' AND uw.view = \'1\''.$sql);

        $wish = array();
        while($cur = $this->db->fetch_assoc($result)) {
            $wish[$cur['movieid']] = $cur;
        }
        return $wish;
    }

    public function getViewedList() {
        $result = $this->db->query('SELECT m.movieid FROM movie AS m INNER JOIN users_views AS uw ON m.movieid = uw.movieid AND uw.userid = '.$this->infos['id'].' GROUP BY m.movieid');

        $wish = array();
        while($cur = $this->db->fetch_assoc($result)) {
            $wish[$cur['movieid']] = $cur;
        }
        return $wish;
    }

    public function getNbViewsMonth($type = 'all') {
        $where = '';
        if($type == 1 OR $type == 2)
            $where = ' AND `type` = \''.$type.'\'';
        
        $stats = array();
        $result = $this->db->query('SELECT YEAR(viewdate) AS annee, MONTH(viewdate) AS mois, COUNT(*) AS nb FROM `users_views` WHERE userid = '.$this->infos['id'].$where.' GROUP BY annee, mois');

        while($cur = $this->db->fetch_assoc($result)){
            $stats[$cur['annee']][$cur['mois']] = $cur['nb'];
        }
        return $stats;
    }

    public function friendHasFilm($movieid) {
        $result = $this->db->query('SELECT b.userid, u.realname, b.bluray, b.dvd, b.numerique FROM users_friend AS f INNER JOIN users_biblio AS b ON f.friend_userid = b.userid AND f.userid = '.$this->infos['id'].' AND b.movieid = '.intval($movieid).' INNER JOIN users AS u ON f.friend_userid = u.id ORDER BY u.realname');

        $this->infos['friendHasFilm'] = array();

        while($cur = $this->db->fetch_assoc($result))
            $this->infos['friendHasFilm'][] = $cur;
    }

    public function FriendToWtach($movieid) {
        $result = $this->db->query('SELECT w.userid, u.realname FROM users_friend AS f INNER JOIN users_wish AS w ON f.friend_userid = w.userid AND f.userid = '.$this->infos['id'].' AND w.movieid = '.intval($movieid).' AND w.view = \'1\' INNER JOIN users AS u ON f.friend_userid = u.id ORDER BY u.realname');

        $this->infos['friendToWtach'] = array();

        while($cur = $this->db->fetch_assoc($result))
            $this->infos['friendToWtach'][] = $cur;
    }

    public function getStatsNb($type = 'genre') {
        if($type == 'acteur')
            $sql = 'SELECT p.personid AS id, p.fullname AS libelle, COUNT(*) AS nb FROM users_views AS uv INNER JOIN movie_person AS mp ON uv.movieid = mp.movieid AND userid = '.$this->infos['id'].' AND mp.`type` = \'1\' INNER JOIN person AS p ON mp.personid = p.personid GROUP BY id ORDER BY nb DESC LIMIT 10';
        else if($type == 'realisateur')
            $sql = 'SELECT p.personid AS id, p.fullname AS libelle, COUNT(*) AS nb FROM users_views AS uv INNER JOIN movie_person AS mp ON uv.movieid = mp.movieid AND userid = '.$this->infos['id'].' AND mp.`type` = \'2\' INNER JOIN person AS p ON mp.personid = p.personid GROUP BY id ORDER BY nb DESC LIMIT 10';
        else
            $sql = 'SELECT g.genreid AS id, g.genrename AS libelle, COUNT(*) AS nb FROM users_views AS uv INNER JOIN movie_genre AS mg ON uv.movieid = mg.movieid AND userid = '.$this->infos['id'].' INNER JOIN genre AS g ON mg.genreid = g.genreid GROUP BY id ORDER BY nb DESC LIMIT 10';

        $result = $this->db->query($sql)or error('Impossible de récupérer les statistiques par '.$type, __FILE__, __LINE__, $this->db->error());

        $nb = array();
        while($cur = $this->db->fetch_assoc($result))
            $nb[] = $cur;

        return $nb;
    }

    public function rateFilm($movieid, $rate) {
        // Le film existe ?
        $film = new \modules\Film\Film();
        $film->exists($movieid);

        $rate = intval($rate);
        if($rate < 0 OR $rate > 5)
            $rate = 0;

        if($film->exists) {
            $datas = array(
                'userid' => $this->infos['id'],
                'movieid' => $movieid,
                'rate' => intval($rate)
                );
            
            $this->db->query(\library\Query::insertORupdate('users_rate', $datas, array('rate'), true))or error($this->db->error());
            // On supprime les lignes dont la note est 0
            $this->db->query('DELETE FROM users_rate WHERE rate = 0');
        }
    }

    public function getRate($movieid) {
        $result = $this->db->query('SELECT rate FROM users_rate WHERE userid = '.$this->infos['id'].' AND movieid = '.intval($movieid));

        if($this->db->num_rows($result))
            $this->infos['rateFilm'] = $this->db->fetch_assoc($result)['rate'];
        else
            $this->infos['rateFilm'] = 0;
    }

    public function displayRate($note) {
        $rate = '';
        $note = intval($note);

        if($note <= 0 OR $note > 5)
            return '&nbsp;';
        else {
            for($i=1;$i<=5;$i++) {
                if($i > $note)
                    $rate .= '<span class="glyphicon glyphicon-star-empty"></span>';
                else
                    $rate .= '<span class="glyphicon glyphicon-star"></span>';
            }

            return $rate;
        }
    }

    public function getTheaters() {
       $result = $this->db->query('SELECT ut.theaterid, t.theatername, t.code, t.adress, t.zipcode, t.city FROM users_theater AS ut INNER JOIN theater AS t ON ut.theaterid = t.theaterid AND ut.userid = '.$this->infos['id'].' ORDER BY zipcode, theatername')or error('Impossible de récupérer les cinémas de l\'utilisateur', __FILE__, __LINE__, $this->db->error());

       $this->infos['theaters'] = array();

       while($cur = $this->db->fetch_assoc($result))
            $this->infos['theaters'][$cur['code']] = $cur;
    }

    public function isSubscribe($ticketid) {
        $result = $this->db->query('SELECT ticketid, userid FROM ticket_subscribe WHERE userid ='.intval($this->infos['id']).' AND ticketid = '.intval($ticketid))or error('Impossible de récupérer l\'abonnement au ticket', __FILE__, __LINE__, $this->db->error());

        return ($this->db->num_rows($result) ? true : false);
    }

    public function getRights() {
        $result = $this->db->query('SELECT action, guest, member, friend FROM users_right WHERE userid = '.$this->infos['id'])or error('Impossible de récupérer les droits de l\'utilisateur '.$this->infos['id'].'.', __FILE__, __LINE__, $this->db->error());

        $right = array();
        $right['biblio'] = array('guest' => '0', 'member' => '0', 'friend' => '0');
        $right['towatchlist'] = array('guest' => '0', 'member' => '0', 'friend' => '0');
        $right['lastview'] = array('guest' => '0', 'member' => '0', 'friend' => '0');
        $right['whishlist'] = array('guest' => '0', 'member' => '0', 'friend' => '0');
        $right['stats'] = array('guest' => '0', 'member' => '0', 'friend' => '0');

        while($cur = $this->db->fetch_assoc($result)) {
            $right[$cur['action']] = $cur;
        }

        $this->infos['right'] = $right;
    }

    public function updateRight($rights, $action) {
        $this->db->query(\library\Query::Update('users_right', $rights, array('userid' => $this->infos['id'], 'action' => $action) , false))or error('Impossible de modifier les droits '.$action.' de l\'utilisateur '.$this->infos['id'].'.', __FILE__, __LINE__, $this->db->error());
    }

    public function getAvis() {
        $sql = 'SELECT a.avisid, a.movieid, a.message, a.created_at, m.titrevf FROM avis AS a INNER JOIN movie AS m ON a.movieid = m.movieid AND a.userid = '.$this->infos['id'].' ORDER BY a.created_at DESC';

        $result = $this->db->query($sql)or error('Impossible de récupérer les avis pour cet utilisateur', __FILE__, __LINE__, $this->db->error());

        $avis = array();
        while($cur = $this->db->fetch_assoc($result))
            $avis[] = $cur;

        return $avis;
    }
}