<?php

require_once 'mvc/controller.php';
require_once 'app/model/City.php';
require_once 'app/model/Clubs.php';
require_once 'app/model/Skiers.php';
require_once 'app/model/Logs.php';
require_once 'app/model/Season.php';
require_once 'app/model/Distance.php';

class XMLController extends MVC\Controller {

    private $xml;
    public function parseFile($fileName) {
        $this->loadFile($fileName);
        $this->parseCities();
        $this->parseClubs();
        $this->parseSkiers();
        $this->parseSeason();
        $this->parseSeasons();
        $this->calculateDistance();
    }

    public function loadFile($fileName) {
        $this->xml = simplexml_load_file($fileName); 
    }

    public function parseCities() {
        $data = $this->xml->xpath('//SkierLogs/Clubs/Club');
        foreach($data as $item) {
            $row = simplexml_load_string($item->asXML());
            $city = new City();
            $city->city = $row->City;
            $city->county = $row->County;
            $city->saveUnique();
        }
    }
    public function parseClubs() {
        $data = $this->xml->xpath('//SkierLogs/Clubs/Club');
        foreach($data as $item) {
            $row = simplexml_load_string($item->asXML());
            $city = new City();
            $city->find([
                'city' => $row->City,
                'county' => $row->County
            ]);
            $attributes = $row->attributes();
            $id = $attributes->id;
            
            // Create a new club
            $club = new Clubs();
            $club->cityid = $city->id;
            $club->name = $row->Name;
            $club->clubid = $id;
            $club->saveUnique();
            
        }
    }

    public function parseSkiers() {
        $data = $this->xml->xpath('//SkierLogs/Skiers/Skier');
        foreach($data as $item) {
            $row = simplexml_load_string($item->asXML());
            $attributes = $row->attributes();

            $skier = new Skiers();
            $skier->username = $attributes->userName;
            $skier->firstName = $row->FirstName;
            $skier->lastName = $row->LastName;
            $skier->yearOfBirth = $row->YearOfBirth;
            
            $skier->saveUnique();
        }
    }

    public function calculateDistance() {
        $yearsStmt = new Season();
        $seasons = $yearsStmt->all();
        
        $skiersStmt = new Skiers();
        $skiers = $skiersStmt->all();
        $logStmt = new Logs();
        foreach($seasons as $season) {
            
            foreach($skiers as $skier) {
                $logs = $logStmt->all([
                    'season' => $season->year,
                    'skierid' => $skier->id
                ]);
                $distance = 0;
                foreach($logs as $log) {
                    $distance += $log->distance;
                }
                $total = new Distance();
                $total->skierid = $skier->id;
                $total->distance = $distance;
                $total->season = $season->year;
                $total->saveUnique();
            }

        }

        
    }

    public function parseSeason() {
        $data = $this->xml->xpath('//SkierLogs/Season');
        foreach($data as $item) {
            $seasonPath = $item->xpath('@fallYear');
            $fallYear = $seasonPath[0];
            $season = new Season();
            $season->year = $fallYear;
            $season->saveUnique();
        }

    }

    public function parseSeasons() {
        $data = $this->xml->xpath('//SkierLogs/Season/Skiers');
        foreach($data as $item) {
            $row = simplexml_load_string($item->asXML());
            $seasonPath = $item->xpath('../@fallYear');
            $fallYear = $seasonPath[0];

            

            $clubAttributes = $row->attributes();
            $clubId = "";
            if(isset($clubAttributes->clubId)) {
                $clubId = $clubAttributes->clubId;           
            }
            
            $skiers = $item->xpath('Skier');
            foreach($skiers as $skier) {
                $skierRow = simplexml_load_string($skier->asXML());
                $skierAttributes = $skierRow->attributes();

                $username = $skierAttributes->userName;
                $logs = $skier->xpath('Log/Entry');

                foreach($logs as $log) {
                    $logRow = simplexml_load_string($log->asXML());
                    $date = $logRow->Date;
                    $area = $logRow->Area;
                    $distance = $logRow->Distance;
                    
                    $person = new Skiers();
                    $person->find(['username' => $username]);
                    if($person->id) {

                        $newLog = new Logs();
                        $newLog->clubId = $clubId;
                        $newLog->skierId = $person->id;
                        $newLog->season = $fallYear;
                        $newLog->date = $date;
                        $newLog->area = $area;
                        $newLog->distance = $distance;
                        $newLog->save();
                    }
                }
            }
          
        }
    }

}