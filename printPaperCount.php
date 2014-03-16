<html>
<head>
	<title>Tarantos Inventory Tracking System</title>
	<link rel="stylesheet" type="text/css" href="print.css" />
</head>
	
<body>
	

<div id="container">
<div id="invTables">
<?php

	$db = new PDO('sqlite:inventory.db');
	//Get location tables
	$locTables = $db->query("SELECT name FROM sqlite_master WHERE name LIKE 'loc%' ORDER BY name");
	$locations = $locTables->fetchAll();
	

	foreach($locations as $loc){
		$locName = $loc['name'];
		if($locName == 'locFront'){
			$lName = 'Front Inventory';
		}	
		if($locName == 'locHall'){
			$lName = 'Hall Inventory';
		}
		if($locName == 'locKitchen'){
			$lName = 'Kitchen Inventory';
		}
		if($locName == 'locInsideWalkin'){
			$lName = 'Inside Walkin Inventory';
		}
		if($locName == 'locOutsideWalkin'){
			$lName = 'Outside Walkin Inventory';
		}
		print "<h3>$lName</h3>";

		$product = $db->query("SELECT name FROM sqlite_master WHERE name != 'items' AND name NOT LIKE 'loc%' AND name NOT LIKE 'inventory%' AND name NOT LIKE 'sqlite%' ORDER BY name");
		$prodTables = $product->fetchAll();
		
		foreach($prodTables as $prod){
			$prodTableName = $prod['name'];

			//Get items in location sorted by product
			$itemObj = $db->query("SELECT * FROM items 
						JOIN $prodTableName ON $prodTableName.ItemID = items.itemIDNum
						JOIN $locName ON $locName.ItemID = items.itemIDNum ORDER BY descrip");
			$items = $itemObj->fetchAll();
			
			$size = sizeof($items);
			if($size > 0){
				print "<h4>$prodTableName</h4>";
			
			print "<div id='itemCounts'>";			
			print "<table border='1'>";
				print "<tr>";
				print "<th width='325'>Item</th><th>Cases Count</th><th>Singles Count</th>";
				print	"</tr>";
			foreach($items as $item){
				$itemNum = $item['itemIDNum'];
				$itemName = $item['descrip'];
				$caseCount = $item['caseCount'];
				$singleCount = $item['itemCount'];
				$packQuantity = $item['packQuantity'];
				
				print "<tr>";
				print "<td>$itemName</td>";
				print "<td>&nbsp</td>";
				print "<td>";
				if($packQuantity == 1){
					print "N/A";
				} else {
					print "&nbsp";
				}
				print "</td>";
				print "</tr>";
			}// end foreach($items...)
			print "</table>";
			print "</div>";
			}// end if(size...)
			
		}//end foreach($prodTables...)
		print "<div style='page-break-after:always'></div>";
	}//end foreach($locations...)


	$db = NULL;
	print '</div>';
	print '<div id="footer">';
		print '<b>Tarantos Inventory Tracking System</b>';
	print '</div>';
	
	print '</div>'; // end Container
	print '</body>';
	print '</html>';
?>
