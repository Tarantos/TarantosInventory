<?php
	$table = $_GET['table'];
	$id = $_GET['id'];
	$descrip = $_POST['Descrip'];
	$packQty = $_POST['PackQty'];
	$packCost = $_POST['PackCost'];
	
	$db = new PDO('sqlite:inventory.db');
	$sql = "UPDATE items SET descrip=\"$descrip\", packQuantity=$packQty, packCost=$packCost WHERE itemIDNum=$id";
	$db->exec($sql);
	$descrip = str_replace(" ", "", $descrip);
	header("Location: AddItem.php#$descrip") ;
?>
