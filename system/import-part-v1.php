<?php

function postJson($url, $auth, $json) {
    $ch = curl_init($url.'documents/import_part');
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $auth);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($json))                                                                       
    );
    
    $response = curl_exec($ch); 
    
    if($response === false) {
    	return json_decode(json_encode(array('type' => 'danger', 'message' => 'Curl Error: '.curl_error($ch).'. Error Code: '.curl_errno($ch).'.')));
    } else {
    	$json = json_decode($response);
    	if (isset($json)) {
    		return $json;
    	} else {
    		return json_decode(json_encode(array('type' => 'danger', 'message' => 'Failed to parse JSON. Is the docu-server URL correct?')));
    	}
    }
}

class PartHeader {
    
    private $data = array();
    
    public function __construct($authToken, $computerId, $uniqueId) {
        $this->data['authToken'] = $authToken;
        $this->data['version'] = 1;
        $this->data['computerId'] = $computerId;
        $this->data['uniqueId'] = $uniqueId;
    }
    
    public function getData() {
        return $this->data;
    }
    
}

class ImagePost {
    
    private $data = array();
    
    public function __construct($header, $image) {
        foreach ($header->getData() as $key => $value) {
            $this->data[$key] = $value;
        }
        
        $this->data['image'] = $image;
    }
    
    public function getData() {
        return $this->data;
    }
    
    public function getJson() {
        return json_encode($this->data);
    }
    
}


class DataPost {
    
    private $data = array();
    
    public function __construct($header, $type, $customer, $docType, $group = true) {
        foreach ($header->getData() as $key => $value) {
            $this->data[$key] = $value;
        }
        
        $this->data['group'] = $group;
        $this->data['type'] = $type;
        $this->data['customerId'] = $customer;
        $this->data['docTypeId'] = $docType;
    }
    
    public function setQuarter($quarter) {
        $this->data['quarterId'] = $quarter;
    }
    
    public function setSource($source) {
        $this->data['sourceId'] = $source;
    }
    
    public function getData() {
        return $this->data;
    }
    
    public function getJson() {
        return json_encode($this->data);
    }
    
}