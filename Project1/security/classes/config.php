<?php

/*
  include_once($_SERVER['DOCUMENT_ROOT'] . '/Server/dbname.php');
  $m = new dbname();
  $dbname = $m->getdbname(); */
//print_r($dbname);
////////////////////
// Important ! These must be filled in correctly.
// Database details are required to use this script.
$curpageURL = $_SERVER["SERVER_NAME"];
$a = array();
$a = explode('.', $curpageURL);
$host = "127.0.0.1"; // If you don't know what your host is, it's safe to leave it localhost
$dbName = $a[0]; //"pslcustomertest"; // Database name
if ($a[0] == 'test') {
    $dbName = 'king';
} else {
    $dbName = $a[0];
}
//$dbName = 'basedb';
$dbUser = "developer"; // Username
$dbPass = "Emlr29861152%%"; // Password

