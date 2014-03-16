<?php
	$table = $_POST['locTables'];

	$db = new PDO('sqlite:inventory.db');

	foreach($_POST as $key => $values){
		$db->exec("INSERT INTO $table (ItemID) VALUES ($key)");
	}

	$db = NULL;
	header( "Location: build.php" ) ;
?>
