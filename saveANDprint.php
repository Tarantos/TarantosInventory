<html>
<head>
	<title>Taranto's Inventory Tracking System - Save and Print</title>
	<link rel="stylesheet" type="text/css" href="./tstyle.css" />
	
	<script type="text/javascript"> 

	function stopRKey(evt) { 
 		var evt = (evt) ? evt : ((event) ? event : null); 
  		var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
  		if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
	} 

	document.onkeypress = stopRKey; 

	</script>
		
</head>

<body>

<!-- MENU SYSTEM -->
	<div id="menuLayout">
	<h4>Taranto's Inventory Tracking System</h4>
	<ul id="menuLinks">
	<li>
		<a href="./index.php">Home</a>
	</li>
	<li>
		<a href="./AddItem.php">Manage Items</a>
	</li>
	<li>
		<a href="NewInv.php">New Inventory Count</a>
	</li>
	<li>
		<a href="help.php">Main Help</a>
	</li>
	</ul> <!-- end menuLinks -->
	</div>
	<!-- MENU SYSTEM -->
	
	<!-- MENU FOR INVENTORY COUNT -->
	<div id="invCountMenu" >
		<ul id="menuLinks">
		<li>
			<a href="./printPaperCount.php">Printable Inventory List</a>
		</li>
		<li>
			<a href="./inventoryCount.php?name=locFront">Front</a>
		</li>
		<li>
			<a href="./inventoryCount.php?name=locHall">Hallway</a>
		</li>
		<li>
			<a href="./inventoryCount.php?name=locKitchen">Kitchen</a>
		</li>
		<li>
			<a href="./inventoryCount.php?name=locInsideWalkin">Inside Walkin</a>
		</li>
		<li>
			<a href="./inventoryCount.php?name=locOutsideWalkin">Outside Walkin</a>
		</li>
		<li>
			<a href="./saveANDprint.php">Save and Print</a>
		</li>
		<li>
			<a href="./helpCount.php">Help</a>
		</li>
		</ul>
	
	</div>
	<?php
	function sqlite3_table_exists($table_name){
        	$sdb = new PDO('sqlite:inventory.db');

        	### check for mrusers table
       		$result = $sdb->query("select name from sqlite_master where type='table' and name=\"$table_name\"");
        	$a_result = $result->fetchArray(SQLITE3_ASSOC);
       		if(is_array($a_result) && count($a_result) > 0){
                	### the table exists
               		$db->close();
                	return TRUE;
        	}else{
                	### the table does not exist
               		$db->close();
                	return FALSE;
        	}
	}
	 
	$date = date("m_d_y_H_i", time());
	$subDate = substr($date, 0, 5);
	$regDate = ereg_replace("_", "/", $subDate);
	print "Inventory Summary for".$regDate;
	print time();
	$db = new PDO('sqlite:inventory.db');
	//$tableTest = sqlite3_table_exists("inventory$date");
	//print $tableTest;
	$db->exec("CREATE TABLE inventory$date(invDateID INTEGER PRIMARY KEY, descrip VARCHAR(50), packQuantity INTEGER, packCost FLOAT, onHandCases FLOAT, onHandSingles INTEGER, onHandTotalCount FLOAT, onHandTotalCost FLOAT)");
		$productsObj = $db->query("SELECT * FROM sqlite_master WHERE name NOT LIKE 'loc%' AND name NOT LIKE 'items' AND name NOT LIKE 'inventory%'");	
		$productTables = $productsObj->fetchAll();
		$totalCost = 0;
		foreach($productTables as $product){
			$productName = $product['name'];
			print '<h4>';
			print $productName;
			print '</h4>';
			$totalProdCost = 0;
			print '<table border="1px">';
			print '<tr>';
			print '<th width="225">Item</th><th>Pack Quantity</th><th>Pack Cost</th><th>On Hand Cases</th><th>On Hand Singles</th><th>On Hand Total</th><th>OnHand Cost</th>';
			print '</tr>';
			$getItemsObj = $db->query("SELECT * FROM items JOIN $productName ON items.itemIDNum=$productName.ItemID ORDER BY descrip ASC");
			$getItems = $getItemsObj->fetchAll();
			
			foreach($getItems as $items){
				$descrip = $items['descrip'];
				$packCost = $items['packCost'];
				$packQuantity = $items['packQuantity'];
				
				print '<tr>';
					print "<td>$descrip</td>";
					print "<td align=\"right\">$packQuantity</td>";
					print "<td align=\"right\">$packCost</td>";
				$caseCount = 0;
				$itemCount = 0;
				$locationsObj = $db->query("SELECT * FROM sqlite_master WHERE name LIKE 'loc%'");
				$locations = $locationsObj->fetchAll();
				foreach($locations as $location){
					$itemID = $items['itemIDNum'];
					$locName = $location['name'];
					$countsObj = $db->query("SELECT * FROM $locName WHERE $locName.ItemID = $itemID"); 
					$counts = $countsObj->fetchAll();
					foreach($counts as $count){
						$caseCount += $count['caseCount'];
						$itemCount += $count['itemCount'];
						$db->exec("UPDATE $locName SET caseCount = NULL, itemCount = NULL WHERE ItemID = $itemID ");
					} //end Counts
				}// end Locations
				print "<td align='right'>$caseCount</td>";
				print "<td>$itemCount</td>";
				$totalCount = $caseCount + ($itemCount/$packQuantity);
				print "<td>";
				print sprintf("%.2f",$totalCount);
				print "</td>";
				$onCost = $totalCount * $packCost;
				$totalProdCost += $onCost;
				print "<td>";
				print sprintf("%.2f", $onCost);
				print "</td>";
				print "</tr>";
				$db->exec("INSERT INTO inventory$date(descrip, packQuantity, packCost, onHandCases, onHandSingles, onHandTotalCount, onHandTotalCost) VALUES (\"$descrip\", $packQuantity, $packCost, $caseCount, $itemCount, $totalCount, $onCost)");
			}//end Items
			print "</table>";
			print "<table>";
			print "<tr>";
			print "<td>Total Cost of $productName</td>";
			print "<td>";
			print sprintf("%.2f", $totalProdCost);
			print "</td>";
			print "</tr>";
			print "</table>";
			$totalCost += $totalProdCost;
		}//end products
		print sprintf("%.2f",$totalCost);
?></body>
