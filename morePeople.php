<?php
/**
File used to create more people to allow for estimations of query times 
Modify $max to change number of extra entries, limit to 9999
**/
include("databaseInfo.php");

$maxEntries = 9999;
$name = "test"; // name is not a key 
$birthday = "07/01/1962"; //birthday is not part of the key 

$mysqli = new mysqli($address,$username,$password,$database);
for($i=0; $i <= $maxEntries; $i++){ //max is 4 digits due to size limitation in db
  $query = "INSERT INTO people VALUES('$i','$name','$birthday')";
  $mysqli->query($query);
}
$mysqli->close();


?>