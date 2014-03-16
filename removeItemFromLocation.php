<?php
$db = new PDO('sqlite:inventory.db');

	
	$table = key($_POST);
	
	for($c = 0; $c < count($_POST[$table]); $c++){
	  $item = $_POST[$table][$c];
          $sql = "DELETE FROM $table WHERE ItemID = $item";
	  $db->exec($sql);
	  
	 }

	 header("Location: removeFromLocation.php");
?>	
