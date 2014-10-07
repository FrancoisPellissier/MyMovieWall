<?php
namespace library;

abstract class BaseModel {
	public $table;
    public $key;
    public $schema;
    /**
     * Le schema doit être indiqué de la façon suivante :
     * 'fieldname' => 'fieldtype' => 'VARCHAR', 'INT', 'DATE', ...
     *             => 'required'  => true / false
     *             => 'default'   => '', NULL, 0, ...
     *             => 'publicname' =>
     */
    public $time;
    public $db;
    public $infos;
    public $exists = false;

    public function __construct() {
    	global $db;
    	$this->db = $db;
    }

	public function exists($id, $allocine = false) {
		global $pun_user;
		// On génère la liste des champs à récupérer
		$sql_fields = implode(', ', array_keys($this->schema));
		if($time)
			$sql_fields .= ', created_at, updated_at';

		// Génération de la clause WHERE
		if($allocine && isset($this->schema['code']))
			$where = 'WHERE code = '.intval($id);
		else
			$where = 'WHERE '.$this->key.' = '.intval($id);

		// On teste l'existence en récupérant les informations de la table
		$result = $this->db->query('SELECT '.$sql_fields.' FROM '.$this->table.' '.$where)or error('Impossible de tester l\'existence dans la table "'.$this->table.'" pour la valeur "'.intval($id).'"', __FILE__, __LINE__, $this->db->error());
		
		if($this->db->num_rows($result)) {
		    $cur = $this->db->fetch_assoc($result);
		    $this->exists = true;
		    $this->infos = $cur;
		    
		    // On enregistre la visite de cette "page" pour pouvoir faire des stats plus tard
		    if($allocine && isset($this->schema['code'])) {
		    	$this->db->query(Query::insert('stats_log', array('tablename' => $this->table, 'tableid' => $this->infos[$this->key], 'ip' => get_remote_address(), 'userid' => $pun_user['id']) , true));
		    }
		}
		else 
		    $this->exists = false;
	}

	/**
	 * BaseModel::checkData()
	 * 
	 * @param array $data
	 * @return array
	 */
	public function checkData($modiftype, $post, $errors) {
	    $data = array();
	    
	    /**
	     * ATTENTION : NE PREND PAS EN COMPTE LE CAS DES CASES A COCHER
	     */
	    
	    if($modiftype == 'insert')
	    {
	        foreach($this->schema AS $field => $fieldinfo)
	        {
	            if($fieldinfo['required'] && !isset($post[$field]))
	                $error[] = 'Le champ '.$fieldinfo['publicname'].' est requis';
	            
	            // On nettoie les données, et on met les valeurs par défaut quand il y a des manques
	            if($fieldinfo['fieldtype'] == 'VARCHAR')
	                $data[$field] = pun_trim(isset($post[$field]) ? $post[$field] : $fieldinfo['default']);
	            else if($fieldinfo['fieldtype'] == 'INT')
	                $data[$field] = intval(isset($post[$field]) ? $post[$field] : $fieldinfo['default']);
	            else if($fieldinfo['fieldtype'] == 'TEXT')
	                $data[$field] = pun_trim(isset($post[$field]) ? $post[$field] : $fieldinfo['default']);
	            else if($fieldinfo['fieldtype'] == 'DATE')
	                $data[$field] = pun_trim(isset($post[$field]) ? $post[$field] : $fieldinfo['default']);
	        }
	    }
	    else if($modiftype == 'update')
	    {            
	        foreach($this->schema AS $field => $fieldinfo)
	        {
	            // On ne modifie que les champs transmis
	            if(isset($post[$field]))
	            {
	                if($fieldinfo['fieldtype'] == 'VARCHAR')
	                    $data[$field] = pun_trim($post[$field]);
	                else if($fieldinfo['fieldtype'] == 'INT')
	                    $data[$field] = intval($post[$field]);
	                else if($fieldinfo['fieldtype'] == 'DATE')
	                    $data[$field] = $post[$field];
	            }
	        }     
	    }
	    return $data;
	}
	
	/**
	 * BaseModel::add()
	 * 
	 * @param array $post
	 * @return array $errot
	 */
	public function add($post) {
	    global $pun_user;
	    
	    $error = array();
	    // On vérifie les données, les remplaçant par la valeur par défaut si besoin
	    $datas = $this->checkData('insert', $post, $error);
	    
	    // On insert les données en base
	    $this->db->query(Query::insert($this->table, $datas, $this->time))or error('Impossible de créer la fiche dans la table : '.$this->table, __FILE__, __LINE__, $this->db->error());
	    $id = $this->db->insert_id();
	    
	    // On récupère la fiche. Nécessaire ? Non on redirige !
	    // $this->exists($id);
	    return $id;
	}
	
	/**
	 * BaseModel::edit()
	 * 
	 * @param array $post
	 * @param array $get
	 * @return array $error
	 */
	public function edit($post, $get) {
	    // On vérifie les données, les remplaçant par la valeur par défaut si besoin
	    $datas = $this->checkData('update', $post, $error);
	            
	    // On enregistre les modifications en base
	    $this->db->query(Query::update($this->table, $datas, array($this->key => $this->infos[$this->key]), $this->time))or error('Impossible de modifier la fiche '.$this->infos[$this->key].' dans la table : '.$this->table, __FILE__, __LINE__, $this->db->error());
	}
}