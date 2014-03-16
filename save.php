<?php
	ini_set('max_execution_time', 0);
	
	$date = date('m_d_y_H_i', time());
	$db = new PDO('sqlite:inventory.db');
	$newInventory = "inventory".$date;
	
	// TODO check if the data is there for the table.
	$db->exec("CREATE TABLE $newInventory(invDateID INTEGER PRIMARY KEY, descrip VARCHAR(50), packQuantity INTEGER, packCost FLOAT, onHandCases FLOAT, onHandSingles INTEGER, onHandTotalCount FLOAT, onHandTotalCost FLOAT)");

		$getItemsObj = $db->query("SELECT * FROM items");
		$getItems = $getItemsObj->fetchAll();

		foreach($getItems as $items){
			$descrip = $items['descrip'];
			$packCost = $items['packCost'];
			$packQuantity = $items['packQuantity'];
			$itemID = $items['itemIDNum'];
			$caseCount = 0;
			$itemCount = 0;

			$locationsObj = $db->query("SELECT * FROM sqlite_master WHERE name LIKE 'loc%'");
			$locations = $locationsObj->fetchAll();

			foreach($locations as $location){
				$locName = $location['name'];

				$countsObj = $db->query("SELECT * FROM $locName WHERE $locName.ItemID = $itemID");
				$counts = $countsObj->fetchAll();

				foreach($counts as $count){
					$caseCount += $count['caseCount'];
					$itemCount += $count['itemCount'];
					$db->exec("UPDATE $locName SET caseCount = NULL, itemCount = NULL WHERE ItemID = $itemID");
				} //end foreach($counts...)
			}//end foreach($locations...)
			$totalCount = $caseCount + ($itemCount/$packQuantity);
			$onCost = $totalCount * $packCost;
			$db->exec("INSERT INTO $newInventory(descrip, packQuantity, packCost, onHandCases, onHandSingles, onHandTotalCount, onHandTotalCost) VALUES (\"$descrip\", $packQuantity, $packCost, $caseCount, $itemCount, $totalCount, $onCost)");
		}//end Items
		header("Location:newPrint.php?table=$newInventory");
?>




