<?php
namespace modules\Theater;

class Theater extends \library\BaseModel {
    /**
     * Theater::__construct()
     * 
     * @return void
     */
    public function __construct() {
    	parent::__construct();
        $this->table = 'theater';
        $this->key = 'theaterid';
        $this->time = true;
        
        $this->schema = array(
        'theaterid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID du cinéma'),
        'theatername' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => 'Nom du cinéma'),
        'code' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => 0, 'publicname' => 'Code Allocine'),
        'adress' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => 0, 'publicname' => 'Adresse'),
        'zipcode' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => 0, 'publicname' => 'Code postal'),
        'city' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => 0, 'publicname' => 'Ville'),
        );
    }

    public function addToUser($userid) {
        if($this->exists)
            $this->db->query('INSERT IGNORE INTO users_theater (userid, theaterid) VALUES('.intval($userid).', '.$this->infos['theaterid'].')')or error('Impossible de lier le cinéma avec l\'utilisateur', __FILE__, __LINE__, $this->db->error());
    }

    public function delFromUser($userid) {
        $this->db->query('DELETE FROM users_theater WHERE userid = '.intval($userid).' AND theaterid = '.$this->infos['theaterid'])or error('Impossible de supprimer le lien entre le cinéma avec l\'utilisateur', __FILE__, __LINE__, $this->db->error());   
    }
}