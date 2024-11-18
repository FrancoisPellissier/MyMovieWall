<?php
namespace modules\Tmdb;

class Tmdb {
    private $apiEndpoint = "https://api.themoviedb.org/3/";
    private $apiKey = "8447d72dbcf6770b6ae44891d82dabc8";
    private $imageEndpoint = "https://image.tmdb.org/t/p/";
    
    public function __construct() {
        // Récupération de l'API token et autres infos utiles pour l'API
        // Dans les fichiers de config
    }

    public function getImageUrl($url, $size = "original") {
        return $this->imageEndpoint.$size.$url;
    }

    public function getFilm($id) {
        // Appel de l'API et conversion en JSON
        $response = $this->callUrl('movie/'.$id, array('append_to_response' => urlencode('release_dates,credits')));
        $json = json_decode($response);

        if(isset($json->success) && !$json->success) {
            return false;
        }
        else {
            $data = array();

            $data['tmdbid'] = $json->id;
            $data['titrevo'] = $json->original_title;
            $data['titrevf'] = $json->title;

            $data['datesortie'] = $this->handleReleaseDate($json->release_dates);
            $data['duree'] = $json->runtime*60;
            $data['duree_texte'] = gmdate("G\hi", $json->runtime*60);

            $data['synopsis'] = $json->overview;
            $data['affiche'] = $this->getImageUrl($json->poster_path);

            // Gestion du casting / réalisateurs
            $credits = $this->handleCredits($json->credits);

            $data['acteurs'] = $credits['acteurs'];
            $data['realisateurs'] = $credits['realisateurs'];

            // Gestion des genres
            $data['genre'] = array();
            if(isset($json->genres)) {
                foreach($json->genres AS $genre) {
                    $data['genre'][$genre->id] = $genre->name;
                }
            }

            return $data;
        }
    }

    private function handleCredits($credits) {
        $data = array(
            'acteurs' => array(),
            'realisateurs' => array()
            );

        // Gestion du casting
        $acteurs = array();
        if(isset($credits->cast) && count($credits->cast) > 0) {
            $i = 0;
            foreach($credits->cast as $acteur) {
                $i++;
                if($i > 12) {
                    break;
                }
                $acteurs[] = array(
                    'tmdbid' => $acteur->id,
                    'nom' => $acteur->original_name,
                    'role' => isset($acteur->character) ? $acteur->character : "",
                    'picture' => isset($acteur->profile_path) ? $this->getImageUrl($acteur->profile_path, 'h632') : ""
                );
            }
            $data['acteurs'] = $acteurs;
        }

        // Gestion du/des réalisateurs
        $director = array();
        if(isset($credits->crew) && count($credits->crew) > 0) {
            foreach($credits->crew as $crews) {
                if($crews->job == 'Director') {
                    $director[] = array(
                        'tmdbid' => $crews->id,
                        'nom' => $crews->original_name,
                        'picture' => isset($crews->profile_path) ? $this->getImageUrl($crews->profile_path, 'h632') : ""
                    );
                }
            }
            $data['realisateurs'] = $director;
        }

        return $data;
    }

    private function handleReleaseDate($release_dates) {
        $answer = null;
        
        if(isset($release_dates->results)) {
            foreach($release_dates->results as $pays) {
                if($pays->iso_3166_1 == 'FR') {

                    // Type 2 Cinéma (limité) ou 3 Cinéma
                    foreach($pays->release_dates AS $date) {
                        if($date->type == 2 || $date->type == 3) {
                            $answer = substr($date->release_date, 0, 10);  
                            break;
                        }
                    }

                    // Sinon celle que je trouve
                    if($answer == null) {
                        $answer = substr($pays->release_dates[0]->release_date, 0, 10);
                    }
                    break;
                }
            }
        }
        return $answer;
    }

    private function getCredits($id) {
        // Appel de l'API et conversion en JSON
        $response = $this->callUrl('movie/'.$id.'/credits');
        $json = json_decode($response);

        $data = array(
            'acteurs' => array(),
            'realisateurs' => array()
            );

        // Gestion du casting
        $acteurs = array();
        if(isset($json->cast) && count($json->cast) > 0) {
            $i = 0;
            foreach($json->cast as $acteur) {
                $i++;
                if($i > 12) {
                    break;
                }
                $acteurs[] = array(
                    'tmdbid' => $acteur->id,
                    'nom' => $acteur->original_name,
                    'role' => isset($acteur->character) ? $acteur->character : "",
                    'picture' => isset($acteur->profile_path) ? $this->getImageUrl($acteur->profile_path, 'h632') : ""
                );
            }
        $data['acteurs'] = $acteurs;
        }

        // Gestion du/des réalisateurs
        $director = array();
        if(isset($json->crew) && count($json->crew) > 0) {
            foreach($json->crew as $crews) {
                if($crews->job == 'Director') {
                    $director[] = array(
                        'tmdbid' => $crews->id,
                        'nom' => $crews->original_name,
                        'picture' => isset($crews->profile_path) ? $this->getImageUrl($crews->profile_path, 'h632') : ""
                    );
                }
            }
            $data['realisateurs'] = $director;
        }

        return $data;
    }

    public function searchFilm($keyword) {
        $datas = array();

        if(!empty($keyword)) {
            // Appel de l'API et conversion en JSON
            $response = $this->callUrl('search/movie', array('query'=> urlencode($keyword) ));
            $json = json_decode($response);

            // Si on a bien result et qu'il y a des résultats
            if(isset( $json->results ) && count($json->results) > 0) {
                foreach( $json->results as $film) {
                    $data = array();

                    $data['titre'] = $film->title;
                    $data['tmdbid'] = $film->id;
                    $data['affiche'] = $this->getImageUrl($film->poster_path, "w342");
                    $data['datesortie'] = (isset($film->release_date) ? $film->release_date : null);
                    $data['pitch'] = (isset($film->overview) ? $film->overview : null);

                    $datas[] = $data;
                }
            }
        }
        return $datas;   

    }

    public function getMoreFilmsFrom($tmdbid, $filmsId) {
        $datas = array('casting' => array(), 'director' => array());

        if(!empty($tmdbid)) {
            // Appel de l'API et conversion en JSON
            $response = $this->callUrl('person/'.intval($tmdbid).'/movie_credits', array() );
            $json = json_decode($response);

            // Acteur
            if( isset( $json->cast ) && count($json->cast) > 0) {
                foreach( $json->cast as $film) {
                    if(isset($filmsId[$film->id])) {
                        continue;
                    }
                    $data = array();

                    $data['titre'] = $film->title;
                    $data['tmdbid'] = $film->id;
                    $data['affiche'] = $this->getImageUrl($film->poster_path, "w342");
                    $data['datesortie'] = (isset($film->release_date) ? $film->release_date : null);
                    $data['pitch'] = (isset($film->overview) ? $film->overview : null);

                    $datas['casting'][] = $data;
                }
            }

            // Réalisateur
            if(isset( $json->crew ) && count($json->crew) > 0) {
                foreach( $json->crew as $film) {
                    // On ne veut que les films "réalisés par"
                    if( $film->job != 'Director') {
                        continue;
                    }
                    if(isset($filmsId[$film->id])) {
                        continue;
                    }
                    $data = array();

                    $data['titre'] = $film->title;
                    $data['tmdbid'] = $film->id;
                    $data['affiche'] = $this->getImageUrl($film->poster_path, "w342");
                    $data['datesortie'] = (isset($film->release_date) ? $film->release_date : null);
                    $data['pitch'] = (isset($film->overview) ? $film->overview : null);

                    $datas['director'][] = $data;
                }
            }
        }

        $datas['casting'] = $this->orderArrayBy($datas['casting'], 'titre');
        $datas['director'] = $this->orderArrayBy($datas['director'], 'titre');
        return $datas;  
    }

    private function callUrl($uri, $addParam = array()) {
        // Gestion des paramètres
        $configParam = array(
            'api_key' => $this->apiKey,
            'language' => 'fr-FR');

        $paramsArray = array_merge( $addParam, $configParam );
        $paramsStr = "";
        foreach($paramsArray as $key=>$value) {
            $paramsStr .= ($paramsStr == "" ? "" : "&").$key."=".$value;
        }
        // Génération de l'url
        $url =  $this->apiEndpoint.$uri."?".$paramsStr;
        
        $response = "";
        try {
            $response = file_get_contents($url);
        }
        catch ( ErrorException $e ) {

        }
        return $response;
    }

    private function orderArrayBy($array, $by ) {
        // Génération du tableau à trier
        $temp = array();
        foreach($array as $key => $value) {
            $temp[$key] = $value[$by];
        }

        asort($temp);

        // Création du nouveau tableau
        $return = array();
        foreach($temp as $key => $value) {
            $return[] = $array[$key];
        }
        return $return;
    }
}