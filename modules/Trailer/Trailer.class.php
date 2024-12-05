<?php
namespace modules\Trailer;

class Trailer extends \library\BaseModel {
    /**
     * Trailer::__construct()
     * 
     * @return void
     */
    public function __construct() {
    	parent::__construct();
        $this->table = 'trailer';
        $this->key = 'trailerid';
        $this->time = true;
        
        $this->schema = array(
        'trailerid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID du trailer'),
        'titre' => array('fieldtype' => 'VARCHAR', 'required' => false, 'default' => '', 'publicname' => 'Titre du trailer'),
        'movieid' => array('fieldtype' => 'INT', 'required' => false, 'default' => '', 'publicname' => 'ID du film associe'),
        'img' => array('fieldtype' => 'TEXT', 'required' => false, 'default' => '', 'publicname' => 'Vignette image'),
        'video' => array('fieldtype' => 'TEXT', 'required' => false, 'default' => '', 'publicname' => 'Code HTML de la vidéo'),
        'code' => array('fieldtype' => 'INT', 'required' => false, 'default' => 0, 'publicname' => 'Code Allocine')
        );
    }

    public function getTrailersMovie($movieid) {
        $sql_fields = implode(', ', array_keys($this->schema));
        if($this->time)
            $sql_fields .= ', created_at, updated_at';

        $result = $this->db->query('SELECT '.$sql_fields.' FROM '.$this->table.' WHERE movieid = '.intval($movieid).' ORDER BY code DESC')or error('Impossible de récupérer les trailers de ce film', __FILE__, __LINE__, $this->db->error());
        
        $all = array();
        if($this->db->num_rows($result)) {
            while($cur = $this->db->fetch_assoc($result)) {
                $all[$cur[$this->key]] = $cur;
            }
        }
        return $all;
    }
}