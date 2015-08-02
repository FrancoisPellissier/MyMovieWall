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
        $this->key = 'teatherid';
        $this->time = true;
        
        $this->schema = array(
        'theaterid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID du cinéma'),
        'theatername' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => 'Nom du cinéma'),
        'code' => array('fieldtype' => 'INT', 'required' => false, 'default' => 0, 'publicname' => 'Code Allocine')
        );
    }
}