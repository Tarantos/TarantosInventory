<?php
try{
	$tableName = $_POST['tableName'];
	
	$db = new PDO('sqlite:inventory.db');
	//$sql = "CREATE TABLE lhj(locIDNum INTEGER PRIMARY KEY, ItemID INTEGER REFERENCES items(itemIDNum), caseCount FLOAT, itemCount INTEGER)"
	
	foreach($_POST as $key=>$value){
		if($key != 'tableName') {
			$ikey = (int)$key;
			
			$sql = "INSERT INTO locFront (ItemID) VALUES ($ikey)";
			$db->exec($sql);
		}
	}
} catch(Exception $e){

	print $e;
}
	
	
	
	
	
	
	header( "Location: build.php" ) ;
?>