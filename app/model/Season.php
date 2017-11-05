<?php


require_once 'mvc/model.php';


class Season extends MVC\Model {

    protected $table = 'season';
    
    public $year;
    

    public function __construct() {}

}
