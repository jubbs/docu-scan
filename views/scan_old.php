<?php

include('main.php');

$computerId = Config::get('COMPUTER_ID');
$customerId = $_POST['customerId'];
$typeId = $_POST['typeId'];

$duplex = isset($_POST['duplex']) && $_POST['duplex'] ? 'both' : 'front';

// Execute scanning script
?><div class="alert alert-info alert-hideme" role="alert">Scanning...</div><?php
ob_flush();
flush();

$scan_result = exec("system/scan.sh $duplex 2>&1");
// ------------------------------

if(trim($scan_result) == "rm: cannot remove '*.pnm': No such file or directory"){
    $scan_failed = true;
} else {
    $scan_failed = false;
}

// Find scans in output folder and sort
$scans = array();
$dir = new DirectoryIterator('scans');
foreach ($dir as $fileinfo) {
	if (!$fileinfo->isDot() && $fileinfo->getFilename() != "empty") {
   		$scans[$fileinfo->getMTime().'-'.$fileinfo->getBasename('.png')] = $fileinfo->getFilename();
	}
}
// ------------------------------


if (empty($scans)||$scan_failed) {
    ?><div class="alert alert-warning" role="alert"><strong>Warning!</strong> No scans found.</div><?php
} else {
    
    // Create post with a single group
    $post = new JsonPost(Config::get('DOCU_SERVER_AUTH_TOKEN'), $computerId, 'png');
    $group = new JsonPostGroup($customerId, $typeId);
    // ------------------------------
    
    
    // Extra fields for other types of scans
    if ($typeId == -2 && isset($_POST['quarterId'])) { // Cover Sheet
        $group->setQuarterId($_POST['quarterId']);
    } else if ($typeId >= 0) { // Customer Document
        $group->setSourceId($_POST['sourceId']);
    }
    // ------------------------------
    
    
    // Sort and add to the group
    ksort($scans);
    
    foreach ($scans as $filename) {
        
        // Encode image file as base 64 string
        $path = "scans/$filename";
        $data = file_get_contents($path);
        $base64 = strtr(base64_encode($data), '+/=', '-_,');
        // ------------------------------
        
        // Create a scan and add to the group
        $scan = new JsonPostScan();
        $scan->setImage($base64);
        $group->addScan($scan);
        // ------------------------------
        
        // Remove file
        unlink($path);
        // ------------------------------
        
    }
    // ------------------------------
    
    
    // Add the group to the post
    $post->addGroup($group);
    // ------------------------------
    
    ?><div class="alert alert-info alert-hideme" role="alert">Uploading <?= count($scans); ?> scan(s)...</div><?php
    ?><div class="alert alert-spinner alert-hideme" style="text-align: center;" role="alert"><img src="assets/img/spinner.gif" width="26" height="26" /></div><?php
    ob_flush();
	flush();
    
    $url = Config::get('DOCU_SERVER_URL').'documents/import';
	$data_string = $post->getJson();
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, Config::get('DOCU_SERVER_AUTH'));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	    'Content-Type: application/json',                                                                                
	    'Content-Length: ' . strlen($data_string))                                                                       
	);
	
	$response = curl_exec($ch); 

	if($response === false) {
    	?><div class="alert alert-danger" role="alert">Curl Error: <?php echo(curl_error($ch)); ?></div><?php
    	?><div class="alert alert-danger" role="alert">Error Code: <?php echo(curl_errno($ch)); ?></div><?php
	} else {
    	$json = json_decode($response);
    	if (isset($json)) {
    		?><div class="alert alert-<?php echo($json->type); ?>" role="alert"><?php echo($json->message); ?></div><?php
    	} else {
    		?><div class="alert alert-danger" role="alert">Failed to parse JSON. Is the docu-server URL correct? <?php echo $response; ?></div><?php
    	}
	}

    $inlineScript .= '<script type="text/javascript">var hideSpinner = true;</script>';
    
}

?>