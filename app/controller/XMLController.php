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
        // echo "Running php script";
        $this->parseCities(); // DONE
        $this->parseClubs(); // DONE
        $this->parseSkiers(); // DONE
        $this->parseSeason(); // DONE
        $this->parseSeasons();
        $this->calculateDistance();
    }

    public function getNode($item, $node) {
        return $item->getElementsByTagName($node)[0]->nodeValue;
    }

    public function loadFile($fileName) {
        $doc = new DOMDocument();
        $doc->load($fileName);
        $this->xml = new DOMXpath($doc);
    }

    public function parseCities() {
        $data = $this->xml->query('//SkierLogs/Clubs/Club');
        foreach($data as $item) {
            $city = new City();
            $city->city = $this->getNode($item, 'City');
            $city->county = $this->getNode($item, 'County');
            $city->saveUnique();
        }
    }
    public function parseClubs() {
        $data = $this->xml->query('//SkierLogs/Clubs/Club');
        foreach($data as $item) {
            $city = new City();
            $city->find([
                'city' => $this->getNode($item, 'City'),
                'county' => $this->getNode($item, 'County')
            ]);

            $id = $item->getAttribute('id');
            
            // Create a new club
            $club = new Clubs();
            $club->cityid = $city->id;
            $club->name = $this->getNode($item, 'Name');
            $club->clubid = $id;
            $club->saveUnique();
            
        }
    }

    public function parseSkiers() {
        $data = $this->xml->query('//SkierLogs/Skiers/Skier');
        foreach($data as $item) {
            $skier = new Skiers();
            $skier->username = $item->getAttribute('userName');
            $skier->firstName = $this->getNode($item, 'FirstName');
            $skier->lastName = $this->getNode($item, 'LastName');
            $skier->yearOfBirth = $this->getNode($item, 'YearOfBirth');
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
        $data = $this->xml->query('//SkierLogs/Season');
        foreach($data as $item) {
            $fallYear = $item->getAttribute('fallYear');
            $season = new Season();
            $season->year = $fallYear;
            $season->saveUnique();
        }

    }

    public function parseSeasons() {
        $data = $this->xml->query('//SkierLogs/Season/Skiers');
        foreach($data as $item) {
            $seasonPath = $this->xml->query('../@fallYear', $item);
            $fallYear = $seasonPath[0]->value;
            
            $clubId = "";
            if($item->hasAttribute('clubId')) {
                $clubId = $item->getAttribute('clubId');           
            }

            $skiers = $this->xml->query('Skier', $item);
            foreach($skiers as $skier) {

                $username = $skier->getAttribute('userName');
                $logs = $this->xml->query('Log/Entry', $skier);

                foreach($logs as $log) {
                    $date = $this->getNode($log, 'Date');
                    $area = $this->getNode($log, 'Area');
                    $distance = $this->getNode($log, 'Distance');
                    
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