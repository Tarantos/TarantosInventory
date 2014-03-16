<?php
	$db = new PDO('sqlite:inventory.db');
		try{
			$newTable = $_POST['Tables'];
			$newItem = $_POST['Item'];
			$newPack = $_POST['PackQty'];
			$newPrice = $_POST['Price'];

			// insert new item into items table
			$sql = "INSERT INTO items (descrip, packQuantity, packCost) VALUES (\"$newItem\", $newPack, $newPrice)";
			$db->exec($sql);

			// reference item in Product table
			$sql = "INSERT INTO $newTable (ItemID) SELECT itemIDNum FROM items WHERE descrip LIKE \"$newItem\"";
			$db->exec($sql);
		}catch(Exception $e){
			echo 'die($e)';
		}
		header( "Location: AddItem.php?table=$newTable" ) ;
?>
