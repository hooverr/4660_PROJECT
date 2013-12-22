<?php
/**
File used to create the XML Schema Document
**/
include("databaseInfo.php");

$xmlData = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n"; // required for xml schema
$xmlData .= "<xs:schema xmlns:xs=\"http://www.w3.org/2001/XMLSchema\">\n"; //required for xml schema
$oldTime = microtime(true);
$mysqli = new mysqli($address,$username,$password,$database);

//Recover the names of all tables.
$result = $mysqli->query("SHOW TABLES");
$xmlData .= "<xs:element name =\"".$database."\">\n";
$xmlData .= " <xs:complexType>\n";
$xmlData .= "   <xs:sequence>\n";
while($object = $result->fetch_array()){
  $tableList[] = $object[0];
}
$result->close();

//for each table, parse out the columns and create xml elements
foreach($tableList as $table){
  $query = "SELECT * from " . $table;
  if($result = $mysqli->query($query)){
    $object = $result->fetch_object();//get object to parse fields
    $xmlData .= "     <xs:element name=\"".$table."\" maxOccurs=\"unbounded\">\n";
    $xmlData .= "       <xs:complexType>\n";
    $xmlData .= "         <xs:sequence>\n";
    $fields = $result->fetch_fields();
    unset($tableKeyList);
    $tableKeyList[] = $table; //store table name as first element 
    foreach($fields as $field){
      $fieldName = $field->name;
      $fieldType = $field->type;
      $fieldType = "string"; //253 is value for varchar but generalizing all non int types as strings
      if($fieldType == "3"){ //3 is value for integer which is often used.
        $fieldType = "integer";
      
      }
      /**More conversions must be done to generate appropriate conversion for different databases that use other types for now generalized as strings**/
      $xmlData .="          <xs:element name=\"" . $fieldName ."\" type=\"xs:".$fieldType."\"/>\n";
      
      //add primary keys to list 
      if($field->flags & 2){
        $tableKeyList[] = $fieldName; //add field name if it is a key
      }
    }
    
    $keyList[] = $tableKeyList;
    
    $xmlData .= "         </xs:sequence>\n";
    $xmlData .= "       </xs:complexType>\n";
    $xmlData .= "     </xs:element>\n";
    
    //check flags to check if field is a key

  $result->close();
  }
  
}
$xmlData .= "   </xs:sequence>\n";
$xmlData .= " </xs:complexType>\n";

//Create primary keys
foreach($keyList as $key){
$xmlData .= "<xs:key name=\"".$key[0]."Key\">\n";
$xmlData .= " <xs:selector xpath=\"".$key[0]."\"/>\n";
for($i = 1; $i < count($key); $i++){ 
  $xmlData .= " <xs:field xpath=\"".$key[$i]."\"/>\n";
}
$xmlData .= "</xs:key>\n";
}

//foreign key referencing
/**WILL ONLY WORK IF BOTH TABLES HAVE SAME NAME FOR THE KEY **/
//find tables that have references.
$mysqli = new mysqli($address,$username,$password,"information_schema");
$result = $mysqli->query("SELECT FOR_NAME, REF_NAME FROM INNODB_SYS_FOREIGN");
while($object = $result->fetch_array()){
  $for = explode("/", $object[0]);
  $ref = explode("/", $object[1]);
  if($for[0] == $database && $ref[0] == $database){
    $foreignKeyList[] = array($for[1],$ref[1]);
  }
}
$mysqli->close();
//since key references aren't refered to, safe to use integer as means to make names unique
$i = 0;
foreach($foreignKeyList as $foreignKey){
  //find key name for from
  $xmlData .= "<xs:keyref name=\"KeyRef".$i."\" refer=\"".$foreignKey[1]."Key\">\n";
  $xmlData .= " <xs:selector xpath=\"".$foreignKey[0]."\"/>\n";
  //find key name for that table.
  foreach($keyList as $key){
  if($key[0] == $foreignKey[1]){
    $xmlData .= " <xs:field xpath=\"".$key[1]."\"/>\n";
  }
  }
  $xmlData .= "</xs:keyref>\n";
  $i++;
}
$xmlData .= "</xs:element>\n";
$xmlData .= "</xs:schema>\n";
file_put_contents("$database.xsd", $xmlData);
$newTime = microtime(true);//microseconds
echo ("Creation of XML schema took: " . round(($newTime-$oldTime),3) . " microseconds");