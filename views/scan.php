<?php

include('main.php');

function alert($alert) {
    if (isset($alert->message)) {
        ?><div class="alert alert-<?php echo($alert->type); ?>" role="alert"><?php echo($alert->message); ?></div><?php
    }
}

$customerId = $_POST['customerId'];
$typeId = $_POST['typeId'];

$duplex = isset($_POST['duplex']) && $_POST['duplex'] ? 'both' : 'front';
$group = isset($_POST['group']);

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
    
    ?><div class="alert alert-info alert-hideme" role="alert">Uploading <?= count($scans); ?> scan(s)...</div><?php
    ?><div class="alert alert-spinner alert-hideme" style="text-align: center;" role="alert"><img src="assets/img/spinner.gif" width="26" height="26" /></div><?php
    ob_flush();
	flush();
    
    $imageErrors = false;
    
    // Server details
    $url = Config::get('DOCU_SERVER_URL');
    $auth = Config::get('DOCU_SERVER_AUTH');
    // ------------------------------
    
    // Create header information for all posts
    $groupIdentifier = str_replace('.', '-', uniqid(null, true));
    $header = new PartHeader(Config::get('DOCU_SERVER_AUTH_TOKEN'), Config::get('COMPUTER_ID'), $groupIdentifier);
    // ------------------------------
    
    
    // Sort and add to the group
    ksort($scans);
    
    foreach ($scans as $filename) {
        
        // Encode image file as base 64 string
        $path = "scans/$filename";
        $data = file_get_contents($path);
        $base64 = strtr(base64_encode($data), '+/=', '-_,');
        // ------------------------------
        
        // Create a scan and post
        $imagePost = new ImagePost($header, $base64);
        $response = postJson($url, $auth, $imagePost->getJson());
        alert($response);
        // ------------------------------

        if ($response->type != 'success') {
            $errors = true;
            break;
        }
        
        // Remove file
        unlink($path);
        // ------------------------------
        
    }
    // ------------------------------
    
    if (!$imageErrors) {
    
        // Send scan data
        $dataPost = new DataPost($header, 'png', $customerId, $typeId, $group);
        
        if ($typeId == -2 && isset($_POST['quarterId'])) { // Cover Sheet
            $dataPost->setQuarter($_POST['quarterId']);
        } else if ($typeId >= 0) { // Customer Document
            $dataPost->setSource($_POST['sourceId']);
        }
        
        $response = postJson($url, $auth, $dataPost->getJson());
        alert($response);
        // ------------------------------

    }

    $inlineScript .= '<script type="text/javascript">var hideSpinner = true;</script>';
    
}

?>