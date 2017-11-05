<?php


require_once 'mvc/model.php';


class Clubs extends MVC\Model {

    protected $table = 'clubs';
    
    public $clubid;
    public $name;
    public $cityid;
    

    public function __construct() {}

}
