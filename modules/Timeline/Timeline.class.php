<?php
namespace modules\Timeline;

class Timeline extends \library\BaseModel {
    public function __construct() {
        parent::__construct();
        $this->table = 'timeline';
        $this->key = 'timelineid';
        $this->time = true;

        $this->schema = array(
        'timelineid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID unique'),
        'action' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => 'Action'),
        'userid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID du créateur'),
        'movieid' => array('fieldtype' => 'INT', 'required' => false, 'default' => NULL, 'publicname' => 'ID du type de ticket'),
        'specificid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '0', 'publicname' => 'ID spécifique')
        );
    }

    public function track($userid, $action, $movieid, $specificid = 0) {

        $datas = array(
            'action' => $action,
            'userid' => $userid,
            'movieid' => intval($movieid),
            'specificid'=> intval($specificid)
            );

        $this->hydrate($datas);
        $new_id = $this->add();
    }
}
