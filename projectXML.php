<?php
/**
File used to create the XML document
**/
include("databaseInfo.php");

$xmlData = "<?xml version=\"1.0\"?>\n";
$xmlData = "<".$database." xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"$database.xsd\">";

$oldTime = microtime(true);
$mysqli = new mysqli($address,$username,$password,$database);

//get the table names
$result = $mysqli->query("SHOW TABLES");
while($object = $result->fetch_array()){
  $tableList[] = $object[0];
}
$result->close();

//for each table parse out column name and the value
foreach($tableList as $table){
  $query = "SELECT * from " . $table;
  if($result = $mysqli->query($query)){
    while($object = $result->fetch_object()){
      $xmlData .= " <".$table.">\n";
      $fields = $result->fetch_fields();
      foreach($fields as $field){
        $fieldName = $field->name;
        $xmlData .= "   <" . $fieldName .">";
        $xmlData .= $object->$fieldName . "";
        $xmlData .= "</" . $fieldName .">\n";
      }
      $xmlData .= " </".$table.">\n";
    }

  $result->close();

  }
  
}
$xmlData .= "</".$database.">";
$mysqli->close();
file_put_contents("$database.xml", $xmlData);
$newTime = microtime(true);
echo ("Conversion to XML document took: " . round(($newTime-$oldTime),3) . " microseconds");
?>