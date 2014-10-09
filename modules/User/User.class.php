<?php
namespace modules\User;

class User extends \library\BaseModel {
    /**
     * Person::__construct()
     * 
     * @return void
     */
    public function __construct() {
        global $pun_user;
    	parent::__construct();
        $this->table = 'users';
        $this->key = 'id';
        $this->time = false;

        $this->infos = $pun_user;
        
        $this->schema = array(
        'id' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID du user')
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
            
            $this->db->query(\library\Query::insertORupdate('users_biblio', $datas, array($type), true));
        }

    }

    public function addView($movieid, $type, $date = null) {
        // Le film existe ?
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
            
            $this->db->query(\library\Query::insert('users_views', $datas, true));

        }
    }
}