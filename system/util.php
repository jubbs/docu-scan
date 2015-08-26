<?php

class Config {
    private static $settings = array();

    public static function get($key) {
        return isset(self::$settings[$key]) ? self::$settings[$key] : false;
    }
    
    public static function set($key, $value) {
        self::$settings[$key] = $value;
    }
    
    public static function all() {
        return self::$settings;
    }
    
}



class Debugger {
    
    public static function dump($data) {
        echo('<pre>');
        print_r($data);
        echo('</pre>');
    }
    
    public static function dumpJson($data) {
        echo('<pre>');
        echo json_encode($data, JSON_PRETTY_PRINT);
        echo('</pre>');
    }
    
}


class MainDatabase {
    
    private static $mysqli;
    
    public static function connect() {
        $credentials = Config::get('MAIN_DATABASE');
        
        self::$mysqli = new mysqli(
            $credentials['host'],
            $credentials['username'],
            $credentials['password'],
            $credentials['name']
        );
        
        if (self::$mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . self::$mysqli->connect_error;
        }
    }
    
    public static function disconnect() {
        self::$mysqli->close();
    }
    
    public static function get() {
        return self::$mysqli;
    }
    
}


class JsonPost {
    
    private $data = array();
    
    public function __construct($authToken, $computerId, $type) {
        $this->data['authToken'] = $authToken;
        $this->data['computerId'] = $computerId;
        $this->data['type'] = $type;
        $this->data['groups'] = array();
    }
    
    public function addGroup($group) {
        $this->data['groups'][] = $group->getData();
    }
    
    public function getGroups() {
        return $this->data['groups'];
    }
    
    public function getAuthToken() {
        return $this->data['authToken'];
    }
    
    public function setAuthToken($authToken) {
        $this->data['authToken'] = $authToken;
    }
    
    public function getComputerId() {
        return $this->data['computerId'];
    }
    
    public function setComputerId($computerId) {
        $this->data['computerId'] = $computerId;
    }
    
    public function getData() {
        return $this->data;
    }
    
    public function getJson() {
        return json_encode($this->data);
    }
    
}



class JsonPostGroup {
    
    private $data = array();
    
    public function __construct($customerId, $typeId) {
        $this->data['customerId'] = $customerId;
        $this->data['typeId'] = $typeId;
        $this->data['scans'] = array();
    }
    
    public function addScan($scan) {
        $this->data['scans'][] = $scan->getData();
    }
    
    public function setQuarterId($quarterId) {
        $this->data['quarterId'] = $quarterId;
    }
    
    public function setSourceId($sourceId) {
        $this->data['sourceId'] = $sourceId;
    }
    
    public function getData() {
        return $this->data;
    }
    
}


class JsonPostScan {
    
    private $data = array();
    
    public function __construct() {
        $this->data['image'] = null;
    }
    
    public function setImage($image) {
        $this->data['image'] = $image;
    }
    
    public function setScanData($scanData) {
        $this->data['scanData'] = $scanData;
    }
    
    public function getData() {
        return $this->data;
    }
    
}