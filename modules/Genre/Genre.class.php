<?php
namespace modules\Genre;

class Genre extends \library\BaseModel {
    /**
     * Genre::__construct()
     * 
     * @return void
     */
    public function __construct() {
    	parent::__construct();
        $this->table = 'genre';
        $this->key = 'genreid';
        $this->time = true;
        
        $this->schema = array(
        'genreid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID du genre'),
        'genrename' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => 'Libellé du genre'),
        'code' => array('fieldtype' => 'INT', 'required' => false, 'default' => 0, 'publicname' => 'Code Allocine')
        );
    }
}