<?php

	require_once('system/util.php');
	require_once('system/import-part-v1.php');
	require_once('system/config.php');
	
	$alphabet = Config::get('ALPHABET');
	$pageTitle = 'DocuScan '.$alphabet[Config::get('COMPUTER_ID')];
	$pageContent = $_SERVER['REQUEST_METHOD'] == 'POST' ? "views/scan.php" : "views/main.php"; // If post from main page redirect to scan page
	include("layouts/default.php");