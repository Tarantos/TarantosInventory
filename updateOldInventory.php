<?php
	$db = new PDO('sqlite:inventory.db');
	
	$invTable = $_GET['table'];
	$invItem = $_GET['item'];
	$packQuantity = $_GET['quantity'];
	
	$packCost = $_POST['packCost'];
	$onHandCases = $_POST['onHandCases'];
	$onHandSingles = $_POST['onHandSingles'];

	$onHandTotalCount = $onHandCases + ($onHandSingles / $packQuantity);
	$onHandTotalCost = $onHandTotalCount * $packCost;

	$sql = "UPDATE $invTable 
				SET packCost=$packCost, onHandCases=$onHandCases, onHandSingles=$onHandSingles, onHandTotalCount=$onHandTotalCount, onHandTotalCost=$onHandTotalCost
				WHERE invDateID=$invItem";
	$db->exec($sql);
	//print $sql;
	header( "Location: editOldInventory.php?inventoryToPrint=$invTable#$invItem" ) ;
	$db = NULL;
?>
