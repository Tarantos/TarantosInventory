<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Taranto's Inventory Tracking System</title>
		<link rel="stylesheet" type="text/css" href="tstyle.css" />
	</head>
	<body>
		<!-- MENU SYSTEM -->
	<div id="menuLayout">
	<h4>Taranto's Inventory Tracking System</h4>
	<ul id="menuLinks">
	<li>
		<a href="./index.php" class="button">Home</a>
	</li>

	<li>
		<a href="./NewInv.php" class="button">New Inventory Count</a>
	</li>
	<li>
		<a href="./oldInventorys.php" class="button">Previous Inventories</a>
	</li>
	<li>
		<a href="./EditInventoryChoice.php" class="button">Edit Old Inventories</a>
	</li>
	<li>
		<a href="./AddItem.php" class="button">Setup</a>
	</li>
	</div>
	<!-- MENU SYSTEM -->
<div id="container">	
<?php
	// Open inventory template DB to edit
	$db = new PDO('sqlite:inventory.db');
	$hallObj = $db->query("SELECT * FROM locHall");
	$hallItems = $hallObj->fetchAll();

	$frontObj = $db->query("SELECT * FROM locFront");
	$frontItems = $frontObj->fetchAll();

	$kitchenObj = $db->query("SELECT * FROM locKitchen");
	$kitchenItems = $kitchenObj->fetchAll();

	$insideWalkinObj = $db->query("SELECT * FROM locInsideWalkin");
	$insideWalkinItems = $insideWalkinObj->fetchAll();

	$outsideWalkinObj = $db->query("SELECT * FROM locOutsideWalkin");
	$outsideWalkinItems = $outsideWalkinObj->fetchAll();
	$color = 1;

	
	
	// Display current items in the template DB
	print '<div id="invTables">';
		print '<b>Current Items in Inventory</b>';
		$getTables = $db->query("SELECT * FROM sqlite_master WHERE name != 'items' AND name NOT LIKE 'sqlite%' AND name NOT LIKE 'loc%' AND name NOT LIKE 'inventory%' ORDER BY name ASC");
		$rowTables = $getTables->fetchAll();
		// Display invTables then invItems below it
		foreach($rowTables as $row){
			$tname = $row['name'];
			print "<h4>$tname</h4>";
			$getItems = $db->query("SELECT * FROM items JOIN $tname ON items.itemIDNum=$tname.ItemID ORDER BY descrip ASC");
		
			// Display invItems 
			print '<table border="1" color="blue" align="center">';
			print '<tr>';
			print '<th width="225">Item</th><th>Pack Quantity</th><th>Pack Cost</th><th width="200">Locations</th>';
			print '</tr>';
			$color = 1;
			foreach($getItems as $item){
			print '<div id="invItems">';
			$stockNum = $item['itemIDNum'];
			$itemCost = sprintf("%.2F", $item['packCost']);
			
			if($color == 1){
				print '<tr bgcolor=#DDDDDD>';
				$color = 2;
			} else {
				print '<tr bgcolor="#FFFFFF">';
				$color = 1;
			}
				print '<td align="left">';
					print $item['descrip'];
				print '</td>';
				print '<td align="right">';
					$PackQty = $item['packQuantity'];
					print "$PackQty";
				print '</td>';
				print '<td align="right">';
					print $itemCost;
				print '</td>';
				print '<td>';
				foreach($hallItems as $hItems){
					$hNum = $hItems['ItemID'];
					if($hNum == $stockNum){
						print " Hall, ";
					}
				}
				foreach($frontItems as $fItems){
					$fNum = $fItems['ItemID'];
					if($fNum == $stockNum){
						print " Front, ";
					}
				}
				foreach($kitchenItems as $kItems){
					$kNum = $kItems['ItemID'];
					if($kNum == $stockNum){
						print " Kitchen, ";
					}
				}
				foreach($insideWalkinItems as $iItems){
					$iNum = $iItems['ItemID'];
					if($iNum == $stockNum){
						print " InsideWalkin, ";
					}
				}
				foreach($outsideWalkinItems as $oItems){
					$oNum = $oItems['ItemID'];
					if($oNum == $stockNum){
						print " OutsideWalkin, ";
					}
				}
				print '</td>';
			print '</tr>';			
			print '</div>';	
			} // End foreach($getItems...)
			print '</table>';
			
		} // End foreach($rowTables...)
	print '</div>'; // END invItems
	$db = NULL;
	
	print '<div id="footer">';
		print '<b>Tarantos Inventory Tracking System</b>';
	print '</div>';
	
	print '</div>'; // end Container
	print '</body>';
	print '</html>';
?>
	
	</body>


</html>
