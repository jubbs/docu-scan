<?php

	require_once('system/util.php');
	require_once('system/config.php');
	
	$pageTitle = 'DocuScan';
	$pageContent = $_SERVER['REQUEST_METHOD'] == 'POST' ? "views/scan.php" : "views/main.php"; // If post from main page redirect to scan page
	include("layouts/default.php");