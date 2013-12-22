<?php
/**
File used to test query times 
**/

$oldTime = microtime(true);
$xml = simplexml_load_file("company.xml");
$result = $xml->xpath("people/name");
$newTime = microtime(true);//microseconds
echo ("<br />");
echo ("XML Execution took: " . round(($newTime-$oldTime),3) . " microseconds");
echo ("<br />");
//equivelelnt sql

include("databaseInfo.php");
$oldTime = microtime(true);
$mysqli = new mysqli($address,$username,$password,$database);
$result = $mysqli->query("SELECT name from people");
$newTime = microtime(true);//microseconds
echo ("<br />");
echo ("SQL Execution took: " . round(($newTime-$oldTime),3) . " microseconds");

?>