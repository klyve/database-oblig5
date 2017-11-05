<?php

namespace MVC;

require_once('sql/sql.php');
require_once('database.php');

class Model {

    private $exludes = ['table', 'exludes', 'exclude'];
    public function __construct() {}
        
    
    private function findObjects() {
        $vars = get_object_vars($this);
        $compileVars = [];
        $userExcludes = [];
        if(isset($this->exclude)) {
            $userExcludes = $this->exclude;
        }
        foreach($vars as $key => $value) {
            if(!in_array($key, $this->exludes) && !in_array($key, $userExcludes)) {
                $compileVars[$key] = $value;
            }
        }
        return $compileVars;
    }

    public function find($data, $selector = "*") {
        $query = SQL::find($data, $this->table, $selector);
        $dbi = Database::instance();
        $stmt = $dbi->prepare($query);
        $stmt->execute($data);
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            foreach($row as $key => $value) {
                $this->$key = $value;
            }
        }
    }
    public function all($data = [], $selector = "*") {
        $query = SQL::find($data, $this->table, $selector);
        $dbi = Database::instance();
        $stmt = $dbi->prepare($query);
        $stmt->execute($data);
        $ret = [];
        if($stmt->rowCount() > 0) {
            $ret = $stmt->fetchAll(\PDO::FETCH_CLASS, get_called_class());
        }
        return $ret;
    }



    public function save() {
        $compileVars = $this->findObjects();
        $query = SQL::insert($compileVars, $this->table);
        $dbi = Database::instance();
        $stmt = $dbi->prepare($query);
        $stmt->execute($compileVars);
    }

    public function saveUnique() {
        $compileVars = $this->findObjects();
        $query = SQL::find($compileVars, $this->table);
        $dbi = Database::instance();
        $stmt = $dbi->prepare($query);
        $stmt->execute($compileVars);
        if($stmt->rowCount() == 0) {
            $this->save();
        }
    }

}