<?php
namespace modules\Person;

class Person extends \library\BaseModel {
    /**
     * Person::__construct()
     * 
     * @return void
     */
    public function __construct() {
    	parent::__construct();
        $this->table = 'person';
        $this->key = 'personid';
        $this->time = true;
        $this->picture = 'picture';
        
        $this->schema = array(
        'personid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID de la personne'),
        'fullname' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => 'Nom complet de la personne'),
        'code' => array('fieldtype' => 'INT', 'required' => false, 'default' => 0, 'publicname' => 'Code Allocine')
        );
    }
}