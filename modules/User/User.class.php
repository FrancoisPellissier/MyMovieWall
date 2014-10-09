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

    public function addBiblio() {

    }
}