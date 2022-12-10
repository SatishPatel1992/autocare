<?php
// Include URL Shortener library file
require_once 'backend/assets/Shortener.php';

// Initialize Shortener class and pass PDO object
$shortener = new Shortener();

// Retrieve short code from URL
$shortCode = $_GET["c"];

try{
    // Get URL by short code
    $url = $shortener->shortCodeToUrl($shortCode);
    
    // Redirect to the original URL
    header("Location: ".$url);
    exit;
}catch(Exception $e){
    // Display error
    echo $e->getMessage();
}
?>