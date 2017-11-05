<?php

namespace MVC;

class SQL {

    public static function insert($data, $table) {
        $key = array_keys($data);  
        $query ="INSERT INTO $table ( ". implode(',' , $key) .") VALUES(:".     implode(",:" , $key) .")";
        return $query;
    }

    public static function find($data, $table, $columns = "*") {
        $select = "";
        foreach($data as $key => $value) {
            if($select !== "") {
                $select .= " AND $key=:$key";
                continue;
            }
            $select = "$key=:$key";
            
        }
        $query = "SELECT $columns FROM $table";
        if(count($data) > 0) {
            $query .= " WHERE $select";
        }

        return $query;
    }
}