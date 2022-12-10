<?php
// Database configuration


$dbHost     = "localhost";
$dbUsername = "satishpatel1992";
$dbPassword = "QzO2sH1~xzKC";
$dbName     = "autocare121";

// Create database connection
try{
    $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
}catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}