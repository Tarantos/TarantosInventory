<?php
	$db = new PDO('sqlite:inventory.db');
	try{
		$table = $_GET['table'];
		$item = $_GET['StockNum'];

		//Delete references from Product table
		$sql = "DELETE FROM $table WHERE ItemID LIKE $item";
		$db->exec($sql);
		//Delete item from item table
		$sql = "DELETE FROM items WHERE itemIDNum LIKE $item";
		$db->exec($sql);

	}catch(Exception $e){
			die($e);
	}
	header( 'Location: AddItem.php' );
?>
