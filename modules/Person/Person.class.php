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
        
        $this->schema = array(
        'personid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID du film'),
        'fullname' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => 'Titre français'),
        'code' => array('fieldtype' => 'INT', 'required' => false, 'default' => 0, 'publicname' => 'Code Allocine')
        );
    }
}