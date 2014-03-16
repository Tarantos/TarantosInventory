<?php
	$db = new PDO('sqlite:inventory.db');
	
	$invTable = $_GET['table'];
	$invItem = $_GET['item'];
	
	$caseCount = $_POST['cases'];
	$itemCount = $_POST['singles'];
	if(empty($caseCount)){
		$caseCount = 0;
	}
	if(empty($itemCount)){
		$itemCount = 0;
	}

	$sql = "UPDATE $invTable 
				SET caseCount=$caseCount, itemCount=$itemCount
				WHERE ItemID=$invItem";
	$db->exec($sql);
	//print $sql;
	header( "Location: inventoryCount.php?name=$invTable#$invItem" ) ;
	$db = NULL;
?>
