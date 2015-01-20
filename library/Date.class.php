<?php
namespace library;

class Date {
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

    public static function formatDate($date, $format) {
        $list_mois = array(1 => 'janvier', 2 => 'février', 3 => 'mars', 4 => 'avril', 5 => 'mai', 6 => 'juin', 7 => 'juillet', 8 => 'août', 9 => 'septembre', 10 => 'octobre', 11 => 'novembre', 12 => 'décembre');
        $list_jours = array(1 => 'lundi', 2 => 'mardi', 3 => 'mercredi', 4 => 'jeudi', 5 => 'vendredi', 6 => 'samedi', 7 => 'dimanche');

        $dates = explode('-', $date);
        $jour = intval($dates[2]);
        $mois = intval($dates[1]);
        $annee = intval($dates[0]);

        if($format == 'J mois annee')
            return $jour.' '.$list_mois[$mois].' '.$annee;
        else if($format == 'mois annee')
            return $list_mois[$mois].' '.$annee;
        if($format == 'JJ/MM/AAAA')
            return $jour.'/'.$mois.'/'.$annee;
        else
            return $date;
    }
}
