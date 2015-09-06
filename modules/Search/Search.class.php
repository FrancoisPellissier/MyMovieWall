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
        if($post['biblio']) {
            $sql_join[] = 'INNER JOIN users_biblio AS ub ON m.movieid = ub.movieid AND ub.userid = '.intval($userid);
        }

        // Lancer la recherche
        $result = $this->db->query('SELECT m.movieid, m.titrevf, m.titrevo FROM movie AS m '.implode(' ', $sql_join).(!empty($sql_where) ? ' WHERE '.implode(' AND ', $sql_where) : '').' GROUP BY m.movieid ORDER BY m.titrevf')or error('Impossible de rechercher les films', __FILE__, __LINE__, $this->db->error());

        $films = array();

        while($cur = $this->db->fetch_assoc($result))
            $films[] = $cur;

        return $films;
    }
}