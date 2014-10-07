<?php

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
}