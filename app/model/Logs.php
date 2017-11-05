<?php


require_once 'mvc/model.php';


class Logs extends MVC\Model {

    protected $table = 'logs';
    public $exclude = ['id'];

    public $id;
    public $season;
    public $clubId;
    public $skierId;
    public $date;
    public $area;
    public $distance;
    

    public function __construct() {}

}
