<?php


require_once 'mvc/model.php';


class Distance extends MVC\Model {

    protected $table = 'totalDistance';
    protected $exclude = ['id'];

    public $id;
    public $skierid;
    public $season;
    public $distance;
    

    public function __construct() {}

}
