<?php
namespace library;

class Datetime {
    public static function formatDateTime($datetime, $dateformat, $timeformat) {
        /* 
        Un rappel des formats accessibles
        Type    Complet     Sans zéro
        Année   Y           y
        Mois    m           n
        Jour    d           j           N (1 lundi / 7 dimanche)
        Heure   H           G
        Minute  i
        Seconde s
        */

        $datetime = new \DateTime($datetime);
        $date = self::formatDate($datetime, $dateformat);
        $time = $datetime->format($timeformat);
        return $date.($date != '' && $time != '' ? ' ' : '').$time;
    }

    public static function formatDate(\Datetime $date, $format) {
        $list_mois = array(1 => 'janvier', 2 => 'février', 3 => 'mars', 4 => 'avril', 5 => 'mai', 6 => 'juin', 7 => 'juillet', 8 => 'août', 9 => 'septembre', 10 => 'octobre', 11 => 'novembre', 12 => 'décembre');
        $list_jours = array(1 => 'lundi', 2 => 'mardi', 3 => 'mercredi', 4 => 'jeudi', 5 => 'vendredi', 6 => 'samedi', 7 => 'dimanche');

        // On cleane certains mots clé
        $format = str_replace('jour', '\j\o\u\r', $format);
        $format = str_replace('mois', '\m\o\i\s', $format);

        // On formatte la date
        $return = $date->format($format);

        // On remplace les mots clés
        $return = str_replace('jour', $list_jours[$date->format('N')], $return);
        $return = str_replace('mois', $list_mois[$date->format('n')], $return);

        // On renvoit la valeur
        return $return;
    }

    public static function duration($duree) {
        $duree = $duree / 1000;
        $minute = floor($duree / 60);
        $duree -= $minute * 60;
        $duree = ceil($duree);

        return $minute.'m '.$duree.'s';
    }

    /**
     * Date::UStoFr()
     * 
     * Passer une date du format américain au format français
     * @param mixed $date
     * @return
     */
    public static function UStoFr($date) {
        $date_s = explode('-', $date);
        
        if(count($date_s) == 3)
            return ($date_s[2].'/'.$date_s[1].'/'.$date_s[0]);
        else
            return '';
    }

    /**
     * Date::FrtoUs()
     * 
     * Passer une date du format français au format américain
     * @param mixed $date
     * @return
     */
    public static function FrtoUs($date) {
        $date_s = explode('/', $date);
        
        if(count($date_s) == 3)
            return ($date_s[2].'-'.substr('00'.$date_s[1] , -2).'-'.substr('00'.$date_s[0] , -2));
        else
            return '';
    }
    
    /**
     * Date::fromUnixtime()
     * 
     * @param int $time
     * @param int $format
     * @return string
     */
    public static function fromUnixtime($time, $format) {
        
        if($format == 1)
            return date('d/m/Y H:i:s', $time);
    }
}
