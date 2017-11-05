<?php


require_once 'mvc/model.php';


class Skiers extends MVC\Model {

    protected $table = 'skiers';
    public $exclude = ['id'];

    public $id;
    public $firstName;
    public $lastName;
    public $yearOfBirth;
    public $username;
    

    public function __construct() {}

}
