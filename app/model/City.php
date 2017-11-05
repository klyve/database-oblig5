<?php


require_once 'mvc/model.php';


class City extends MVC\Model {

    protected $table = 'city';
    
    public $id;
    public $city;
    public $county;

    // Exclude variables
    public $exclude = ['id'];
    

    public function __construct() {}

    public function print() {
        echo "City: $this->city <br />";
        echo "County: $this->county <hr />";
    }

}
