<?php
namespace modules\Search;

class Search extends \library\BaseModel {

    public function defaultParams() {
        $params = array();
        $params['titre'] = '';
        $params['synopsis'] = '';
        $params['acteur'] = '';
        $params['genre'] = 0;
        $params['ordre'] = 1;
        $params['own'] = 1;
        $params['biblio'] = 0;

        return $params;
    }

    private function test($post, $var) {
        if(isset($post[$var]))
            return $post[$var];
        else
            return '';
    }

    public function cleanPost($post) {
        $params = array();
        $params['titre'] = $this->test($post, 'titre');
        $params['acteur'] = $this->test($post, 'acteur');

        $params['biblio'] = isset($_POST['biblio']) ? true : false;
        $params['own'] = isset($_POST['own']) ? $_POST['own'] : '';
        $params['genre'] = intval($this->test($post, 'genre'));
        $params['ordre'] = intval($this->test($post, 'ordre'));
        
        return $params;
    }

    public function search($post, $userid) {
        $sql_join = array();
        $sql_where = array();

        // Filtre par titre
        if($post['titre'] != '') {
            $sql_where[] = '(m.titrevf LIKE \'%'.$this->db->escape($post['titre']).'%\' OR m.titrevo LIKE \'%'.$this->db->escape($post['titre']).'%\')';
        }

        // Filtre par genre
        if($post['genre'] != 0) {
            $sql_join[] = 'INNER JOIN movie_genre AS mg ON m.movieid = mg.movieid AND mg.genreid = '.intval($post['genre']);
        }

        // Filtre par acteur
        if($post['acteur'] != '') {
            $sql_join[] = 'INNER JOIN movie_person AS mp ON m.movieid = mp.movieid INNER JOIN person AS p ON mp.personid = p.personid AND p.fullname LIKE \'%'.$post['acteur'].'%\'';
        }

        // Filtre par bibliothèque
        if($post['biblio'] || $post['own'] != '') {
            $sql_temp = 'INNER JOIN users_biblio AS ub ON m.movieid = ub.movieid AND ub.userid = '.intval($userid);

            if($post['own'] != '')
                $sql_temp .= ' AND '.$post['own'].' = \'1\'';

            $sql_join[] = $sql_temp;
        }

        // Récupération des notes
        $sql_join[] = 'LEFT JOIN users_rate AS ur ON m.movieid = ur.movieid AND ur.userid = '.intval($userid);

        // Cé de tri
        $sort = array();
        $sort[1] = 'm.titrevf';
        $sort[2] = 'm.datesortie DESC';
        $sort[3] = 'ur.rate DESC, m.titrevf';

        // Création de la requête
        $sql = 'SELECT m.movieid, m.titrevf, m.titrevo, ur.rate FROM movie AS m ';
        $sql .= implode(' ', $sql_join);
        $sql .= (!empty($sql_where) ? ' WHERE '.implode(' AND ', $sql_where) : '');
        $sql .= ' GROUP BY m.movieid';
        $sql .= ' ORDER BY '.(isset($sort[$post['ordre']]) ? $sort[$post['ordre']] : 'm.titrevf');

        // Lancer la recherche
        $result = $this->db->query($sql)or error('Impossible de rechercher les films', __FILE__, __LINE__, $this->db->error());

        $films = array();

        while($cur = $this->db->fetch_assoc($result)) {
            $cur['rate'] = $this->displayRate($cur['rate']);
            $films[] = $cur;
        }

        return $films;
    }
}