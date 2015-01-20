<?php
namespace library;

class Query { 
    /**
     * Query::insert()
     * 
     * @param String $table
     * @param Array $datas
     * @param Boolean $time
     * @return SQL_INSERT
     */
    public static function insert($table, $datas, $time = NULL, $ignore = false) {
        global $db;
        
        // Query FIELDS
        $sql_fields = array();
        $sql_values = array();
        
        foreach($datas AS $field => $value) {
            $sql_fields[] = $field;
            $sql_values[] = ($value == 'NULL' && strlen($value) == 4 ? 'NULL' : '\''.$db->escape($value).'\'');
        }
        
        if($time)
        {
            $sql_fields[] = 'created_at';
            $sql_fields[] = 'updated_at';
            $sql_values[] = 'NOW()';
            $sql_values[] = 'NOW()';
        }
        
        return 'INSERT'.($ignore ? ' IGNORE' : '').' INTO '.$table.' ('.implode(', ', $sql_fields).') VALUES ('.implode(', ', $sql_values).')';
    }
    
    /**
     * Query::update()
     * 
     * @param String $table
     * @param Array $datas
     * @param Array $where
     * @param Boolean $time
     * @return SQL_UPDATE
     */
    public static function update($table, $datas, $where, $time = NULL) {
        global $db;
        
        // Query SET
        $sql_set = array();
        foreach($datas AS $field => $value)
            $sql_set[] = $field.' = '.($value == 'NULL' && strlen($value) == 4 ? 'NULL' : '\''.$db->escape($value).'\'');
        
        if($time)
            $sql_set[] = 'updated_at = NOW()';

        // Clause WHERE
        $sql_where = array();
        foreach($where AS $field => $value)
            $sql_where[] = $field.' = \''.$db->escape($value).'\'';
        
        return 'UPDATE '.$table.' SET '.implode(', ', $sql_set).' WHERE '.implode(' AND ', $sql_where);
    }

    /**
     * Query::insertORupdate()
     * 
     * @param String $table
     * @param Array $datas
     * @param Array $set
     * @param Boolean $time
     * @return SQL_UPDATE
     */
    public static function insertORupdate($table, $datas, $set, $time = NULL) {
        global $db;
        
        // Query FIELDS
        $sql_fields = array();
        $sql_values = array();
        $sql_set = array();
        
        foreach($datas AS $field => $value) {
            $sql_fields[] = $field;
            $sql_values[] = ($value == 'NULL' && strlen($value) == 4 ? 'NULL' : '\''.$db->escape($value).'\'');
            
            if(in_array($field, $set))
                $sql_set[] = $field.' = '.($value == 'NULL' && strlen($value) == 4 ? 'NULL' : '\''.$db->escape($value).'\'');
        }
        
        if($time)
        {
            $sql_fields[] = 'created_at';
            $sql_fields[] = 'updated_at';
            $sql_values[] = 'NOW()';
            $sql_values[] = 'NOW()';
            $sql_set[] = 'updated_at = NOW()';
        }
        
        return 'INSERT INTO '.$table.' ('.implode(', ', $sql_fields).') VALUES ('.implode(', ', $sql_values).') ON DUPLICATE KEY UPDATE '.implode(', ', $sql_set);
    }
}
?>