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
    public $order;
    public $time;
    public $picture;
    public $pictureurl;
    public $db;
    public $infos;
    public $exists = false;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function all() {
        // On génère la liste des champs à récupérer
        $sql_fields = implode(', ', array_keys($this->schema));
        if($this->time)
            $sql_fields .= ', created_at, updated_at';

        $result = $this->db->query('SELECT '.$sql_fields.' FROM '.$this->table.($this->order != '' ? ' ORDER BY '.$this->order : ''))or error('Impossible tous les éléments de la table "'.$this->table.'"', __FILE__, __LINE__, $this->db->error());
        
        $all = array();
        if($this->db->num_rows($result)) {
            while($cur = $this->db->fetch_assoc($result)) {
                $all[$cur[$this->key]] = $cur;
            }
        }
        return $all;
    }

    public function exists($id, $tmdb = false, $email = false) {
        global $pun_user;
        // On génère la liste des champs à récupérer
        $sql_fields = implode(', ', array_keys($this->schema));
        if($this->time)
            $sql_fields .= ', created_at, updated_at';

        // Génération de la clause WHERE
        if($tmdb && isset($this->schema['tmdbid']))
            $where = 'WHERE tmdbid = \''.$this->db->escape($id).'\'';
        else if($email && isset($this->schema['email']))
            $where = 'WHERE email = \''.$this->db->escape($id).'\'';
        else
            $where = 'WHERE '.$this->key.' = '.intval($id);

        // On teste l'existence en récupérant les informations de la table
        $result = $this->db->query('SELECT '.$sql_fields.' FROM '.$this->table.' '.$where)or error('Impossible de tester l\'existence dans la table "'.$this->table.'" pour la valeur "'.intval($id).'"', __FILE__, __LINE__, $this->db->error());
        
        if($this->db->num_rows($result)) {
            $cur = $this->db->fetch_assoc($result);
            $this->exists = true;
            
            // On génère le chemin pour l'image
            if($this->picture != '' && !$tmdb)
                $cur['folder'] = 'img/'.$this->table.'/'.intval($cur[$this->key] / 100).'/';

            $this->infos = $cur;
        }
        else 
           $this->exists = false;
    }

    public function hydrate($datas) {

        // On parcourt le schema pour insérer les données correspondant dans les data
        foreach($this->schema AS $field => $infos) {
            if(isset($datas[$field]))
                $this->infos[$field] = $datas[$field];
            }

        // On récupère l'url de l'image si elle existe
        if(isset($datas[$this->picture])) {
            $this->pictureurl =  $datas[$this->picture];
        }
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
        
        if($modiftype == 'insert') {
            foreach($this->schema AS $field => $fieldinfo) {
                if($fieldinfo['required'] && !isset($post[$field]))
                    $error[] = 'Le champ '.$fieldinfo['publicname'].' est requis';
                
                // On passe la clé
                if($field != $this->key) {
                    // On nettoie les données, et on met les valeurs par défaut quand il y a des manques
                    if($fieldinfo['fieldtype'] == 'VARCHAR')
                        $data[$field] = pun_trim(isset($post[$field]) ? $post[$field] : $fieldinfo['default']);
                    else if($fieldinfo['fieldtype'] == 'INT')
                        $data[$field] = intval(isset($post[$field]) ? $post[$field] : $fieldinfo['default']);
                    else if($fieldinfo['fieldtype'] == 'TEXT')
                        $data[$field] = pun_trim(isset($post[$field]) ? $post[$field] : $fieldinfo['default']);
                    else if($fieldinfo['fieldtype'] == 'DATE' || $fieldinfo['fieldtype'] == 'DATETIME') {
                        if(isset($post[$field]) && $post[$field] != '' && $post[$field] != null && strlen($post[$field]) == 10)
                            $data[$field] = $post[$field];
                        else
                            unset($this->schema[$field]);
                    }
                }
            }
        }
        else if($modiftype == 'update') {            
            foreach($this->schema AS $field => $fieldinfo) {
                // On ne modifie que les champs transmis
                if(isset($post[$field])) {
                    if($fieldinfo['fieldtype'] == 'VARCHAR')
                        $data[$field] = pun_trim($post[$field]);
                    else if($fieldinfo['fieldtype'] == 'INT')
                        $data[$field] = intval($post[$field]);
                    else if($fieldinfo['fieldtype'] == 'DATE') {
                        if($post[$field] == '' || $post[$field] == null || strlen($post[$field]) != 10)
                            unset($this->schema[$field]);
                        else
                            $data[$field] = $post[$field];
                    }
                    else if($fieldinfo['fieldtype'] == 'DATETIME') {
                        if($post[$field] == '' || $post[$field] == null)
                            unset($this->schema[$field]);
                        else
                            $data[$field] = $post[$field];
                    }
                    else if($fieldinfo['fieldtype'] == 'TEXT')
                        $data[$field] = pun_trim(isset($post[$field]) ? $post[$field] : $fieldinfo['default']);
                }
            }     
        }
        return $data;
    }
    
    /**
     * BaseModel::add()
     * 
     * @return array $errot
     */
    public function add() {
        global $pun_user;
        
        $error = array();
        // On vérifie les données, les remplaçant par la valeur par défaut si besoin
        $datas = $this->checkData('insert', $this->infos, $error);
        
        // On insère les données en base
        $this->db->query(Query::insert($this->table, $datas, $this->time))or error('Impossible de créer la fiche dans la table : '.$this->table, __FILE__, __LINE__, $this->db->error());
        $id =  $this->db->insert_id();

        // On regarde s'il y a une image a rapatrier
        if($this->pictureurl != '')
            $this->getPoster($id);
        
        // On renvoit l'ID généré
        return $id;
    }
    
    public function getPoster($id) {
        $image = new \library\Image();
        $image->download($this->pictureurl, $this->table, $id);
    }

    /**
     * BaseModel::edit()
     * 
     * @param array $post
     * @param array $get
     * @return array $error
     */
    public function edit($post, $get = array()) {
        // On vérifie les données, les remplaçant par la valeur par défaut si besoin
        $error = array();
        $datas = $this->checkData('update', $post, $error);
        
        // On enregistre les modifications en base
        // dump(Query::update($this->table, $datas, array($this->key => $this->infos[$this->key]), $this->time));
        $this->db->query(Query::update($this->table, $datas, array($this->key => $this->infos[$this->key]), $this->time))or error('Impossible de modifier la fiche '.$this->infos[$this->key].' dans la table : '.$this->table, __FILE__, __LINE__, $this->db->error());
    }

    public function getLasts() {
        $result = $this->db->query('SELECT * FROM '.$this->table.' ORDER BY created_at DESC');

        $last = array();
        while($cur = $this->db->fetch_assoc($result)) {
            $last[] = $cur;
        }
        return $last;
    }

    public function assocPerson($movieid, $datas) {
        $type = array(1 => 'acteurs', 2 => 'realisateurs');

        // On supprime tous les liens
        $this->db->query('DELETE FROM movie_person WHERE movieid = '.intval($movieid))or error('Impossible de supprimer les liens entre le film et les genre', __FILE__, __LINE__, $this->db->error());

        // On parcourt les types
        foreach($type AS $typeid => $typename) {
            // On parcourt les personnes
            foreach($datas[$typename] AS $data) {
                // On regarde si elles existent
                $person = new \modules\Person\Person();
                $person->exists($data['tmdbid'], true);

                // On les créé si elles n'existent pas
                if(!$person->exists) {
                    $person->hydrate(array(
                        'fullname' => $data['nom'],
                        'picture' => $data['picture'],
                        'tmdbid' => $data['tmdbid'])
                        );
                    $personid = $person->add();
                }
                else {
                    $personid = $person->infos['personid'];
                    // On met tout de même l'image à jour
                    if($data['picture'] != '') {
                        $person->pictureurl = $data['picture'];
                        $person->getPoster($personid);
                    }
                }

                // On insère le lien film / person / type
                $data_insert = array(
                    'movieid ' => $movieid,
                    'personid' => $personid,
                    'type' => $typeid,
                    'role' => ($typeid == 1 ? $data['role'] : '')
                    );

                $this->db->query(Query::insert('movie_person', $data_insert, false))or error('Impossible de créer les liens entre le film et les personnes', __FILE__, __LINE__, $this->db->error());
            }
        }
    }

    public function assocGenre($movieid, $datas) {
        // On supprime les liens Films / Genre existant pour cette fiche
        $this->db->query('DELETE FROM movie_genre WHERE movieid = '.intval($movieid))or error('Impossible de supprimer les liens entre le film et les genre', __FILE__, __LINE__, $this->db->error());

        if(!empty($datas)) {
            // On parcourt les genres
            foreach($datas AS $tmdbid => $name) {
                // On regarde s'il existe
                $genre = new \modules\Genre\Genre();
                $genre->exists($tmdbid, true);

                // S'il n'existe pas on le créé
                if(!$genre->exists) {
                    $genre->hydrate(array('genrename' => $name, 'tmdbid' => $tmdbid));
                    $genreid = $genre->add();
                }
                else
                    $genreid = $genre->infos['genreid'];

                // On insère le lien film / genre
                $data_insert = array(
                    'movieid ' => $movieid,
                    'genreid' => $genreid
                    );
                
                $this->db->query(Query::insert('movie_genre', $data_insert, false))or error('Impossible de créer les liens entre le film et les genres', __FILE__, __LINE__, $this->db->error());
            }
        }
    }

    public function assocTrailer($movieid, $datas) {
        if(!empty($datas)) {
            // On parcourt les trailers
            foreach($datas AS $data) {
                $data['movieid'] = $movieid;

                // On regarde s'il existe
                $trailer = new \modules\Trailer\Trailer();
                $trailer->exists($data['code'], true);

                // S'il n'existe pas on le créé
                if(!$trailer->exists) {
                    
                    $trailer->hydrate($data);
                    $trailerid = $trailer->add();
                }
                else {
                    $trailer->edit($data);
                }
            }
        }
    }

    public function displayRate($note) {
        $rate = '';
        $note = intval($note);

        if($note <= 0 OR $note > 5)
            return '&nbsp;';
        else {
            for($i=1;$i<=5;$i++) {
                if($i > $note)
                    $rate .= '<span class="glyphicon glyphicon-star-empty"></span>';
                else
                    $rate .= '<span class="glyphicon glyphicon-star"></span>';
            }

            return $rate;
        }
    }
}