<?php
namespace modules\Allocine;

class Allocine {
    
    public function __construct() {
        require_once 'api-allocine-helper.php';
    }

    private function getVar($var) {
        return (isset($var) ? $var : '');
    }

    public function clean($keyword) {
        $normalizeChars = array(
            'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
            'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
            'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
            'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
            'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
            'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
            'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
            'ă'=>'a', 'î'=>'i', 'â'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Î'=>'I', 'Â'=>'A', 'Ș'=>'S', 'Ț'=>'T',
        );

        return strtr($keyword, $normalizeChars);
    }

    public function search($keywords) {
        $allohelper = new AlloHelper();

        $datas = array();
        $keywords = $this->clean($keywords);

        if(!empty($keywords)) {
            try {  
                $donnees = $allohelper->search($keywords, 1, 20, false, array('movie'));
                
                if (count( $donnees['movie'] ) >= 1) {
                    foreach ($donnees['movie'] as $film) {
                        $data = array();
                        $data['titre'] = $film['title'];
                        $data['code'] = $film['code'];
                        $data['affiche'] = str_replace('http://images.allocine.fr', 'http://images.allocine.fr/r_160_240/b_1_d6d6d6', $film['poster']);
                        $data['realisateur'] = $film['castingShort']['directors'];
                        $data['acteur'] = $film['castingShort']['actors'];

                        $datas[] = $data;
                    }
                }
            }
            // En cas d'erreur.
            catch ( ErrorException $e ) {

            }
        }
        return $datas;   
    }

    public function getFilm($id) {
        
        $allohelper = new AlloHelper();
    
        try {
            $return = $allohelper->movie(intval($id), 'medium');

            if($return == 'Erreur 5: No result')
                echo "<p>Impossible de trouver la fiche film</p>";
            else {
                $film = $return;

                $data = array();
                
                $data['code'] = $this->getVar($film['code']);
                $data['titrevo'] = $this->getVar($film['originalTitle']);
                $data['titrevf'] = $this->getVar($film['title']);

                $data['datesortie'] = $this->getVar($film['release']['releaseDate']);
                $data['duree'] = $this->getVar($film['runtime']);
                $data['duree_texte'] = gmdate("G\hi", intval($data['duree']));

                $data['synopsis'] = $this->getVar($film['synopsis']);
                $data['realisateur'] = $this->getVar($film['castingShort']['directors']);
                $data['acteur'] = $this->getVar($film['castingShort']['actors']);

                $data['affiche'] = $this->getVar($film['poster']->url());

                // Genres
                foreach($film['genre'] AS $genre)
                    $data['genre'][$genre['code']] = $this->getVar($genre['$']);

                // Casting
                if(!empty($film['castMember'])) {
                    foreach($film['castMember'] AS $cast) {
                        // Acteur
                        if($cast['activity']['code'] == '8001') {
                            $data['acteurs'][] = array(
                                'code'  => $this->getVar($cast['person']['code']),
                                'nom'   => $this->getVar($cast['person']['name']),
                                'picture'   => $this->getVar($cast['picture']['href']),
                                'role'  => $this->getVar($cast['role'])
                                );
                        }
                        // Réalisateur
                        else if($cast['activity']['code'] == '8002') {
                            $data['realisateurs'][] = array(
                                'code'  => $this->getVar($cast['person']['code']),
                                'picture'   => $this->getVar($cast['picture']['href']),
                                'nom'   => $this->getVar($cast['person']['name'])
                                );
                        }
                    }
                }
                return $data;
            }
        }
        catch ( ErrorException $e ) {

        }
    }

    public function getFilmTrailer($id) {
        
        $allohelper = new AlloHelper();
    
        try {
            $return = $allohelper->movie(intval($id), 'large');

            if($return == 'Erreur 5: No result')
                echo "<p>Impossible de trouver la fiche film</p>";
            else {
                $film = $return;
                $data = array();
                
                // Genres
                foreach($film['media'] AS $media) {
                    if($media['class'] == 'video' && ($media['type']['code'] == '31003' || $media['type']['code'] == '31016'))
                    {
                        $trailer = array();
                        $trailer['code'] = $this->getVar($media['code']);
                        $trailer['titre'] = $this->getVar($media['title']);
                        $trailer['img'] = $this->getVar($media['thumbnail']['href']);
                        $trailer['video'] = $this->getVar($media['trailerEmbed']);
                        
                        $data[] = $trailer;
                    }
                }
                return $data;
            }
        }
        catch ( ErrorException $e ) {

        }
    }

    public function searchTheater($keyword) {
        $allohelper = new AlloHelper();

        $datas = array();

        try {
            // Envoi de la requête avec les paramètres, et enregistrement des résultats dans $donnees.
            $donnees = $allohelper->search($keyword, 1, 20, false, array('theater'));
            
            // Des résultats ?
            if (count( $donnees['theater'] ) >= 1) {
                // Pour chaque résultat de film.
                foreach ($donnees['theater'] as $theater) {
                    $data = array();
                    $data['theatername'] = $theater['name'];
                    $data['code'] = $theater['code'];
                    $data['adress'] = $theater['address'];
                    $data['zipcode'] = $theater['postalCode'];
                    $data['city'] = $theater['city'];

                    $datas[] = $data;
                }
            }
        }
        // En cas d'erreur.
        catch ( ErrorException $e ) {
            
        }
        return $datas;  
    }

    public function getTheater($code) {
        $allohelper = new AlloHelper();
    
        try {
            $code = array($code);
            $return = $allohelper->showtimesByTheaters($code);

            if(isset($return['feed']['theaterShowtimes'][0]['place']['theater'])) {
                $theater = $return['feed']['theaterShowtimes'][0]['place']['theater'];
                $data = array();
                $data['theatername'] = $theater['name'];
                $data['code'] = $theater['code'];
                $data['adress'] = $theater['address'];
                $data['zipcode'] = $theater['postalCode'];
                $data['city'] = $theater['city'];
                return $data;
                
            }
            else
                return false;
        }
        catch ( ErrorException $e ) {

        }
    }

    public function getSeances($code, $theaters) {
        $allohelper = new AlloHelper();
    
        try {
            $datas = array();
            $return = $allohelper->showtimesByTheaters($theaters, null, $code);
            
            // Est-ce que l'on a un résultat ?
            if(isset($return['feed']['theaterShowtimes'])) {
                // Si oui, on boucle sur les cinémas
                foreach($return['feed']['theaterShowtimes'] AS $place) {                
                    // Il y a des séances ?
                    if(isset($place['movieShowtimes'])) {
                        // On parcourt les version (VO/VF/...)
                        foreach($place['movieShowtimes'] AS $version) {
                            // On parcourt les dates
                            foreach($version['scr'] AS $scr) {
                                // On parcourt les horaires
                                foreach($scr['t'] AS $t) {
                                    $datas[$place['place']['theater']['name']][$version['version']['$'].' - '.$version['screenFormat']['$']][$scr['d']][$t['$']] = $t['$'];
                                }
                            }
                        }
                    }
                }
                ksort($datas);
                return $datas;
            } 
            else
                return false;
        }
        catch ( ErrorException $e ) {

        }
    }
};