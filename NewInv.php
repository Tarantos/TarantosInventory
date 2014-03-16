<html>
<head>
	<title>Tarantos Inventory Tracking System</title>
	<link rel="stylesheet" type="text/css" href="tstyle.css" />



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
	
	<!-- MENU FOR INVENTORY COUNT -->
	<div id="menuLayout" >
		

		<ul id="menuLinks">
	<h4>Taranto's Inventory Tracking System</h4>
		<li>
			<a href="./index.php" class="button">Home</a>
		</li>
		<li>
			<a href="./printPaperCount.php" class="button">Printable Inventory List</a>
		</li>
		<li>
			<a href="./inventoryCount.php?name=locFront" class="button">Front</a>
		</li>
		<li>
			<a href="./inventoryCount.php?name=locHall" class="button">Hallway</a>
		</li>
		<li>
			<a href="./inventoryCount.php?name=locInsideWalkin" class="button">Inside Walkin</a>
		</li>
		<li>
			<a href="./inventoryCount.php?name=locKitchen" class="button">Kitchen</a>
		</li>

		<li>
			<a href="./inventoryCount.php?name=locOutsideWalkin" class="button">Outside Walkin</a>
		</li>
		<li>
			<a href="./saveWarning.php" class="button">Save and Print</a>
		</li>
		</ul>
	
	</div>


<div id="container">
<div id="invTables">
	<p>To begin a new inventory count, select the <a href="printPaperCount.php">Printable Inventory List</a>
	link. Distribute the list for counting or count the inventory yourself. Once the items in each location are counted, select one of the links above to input the inventory count for that location.
	The table below shows which items in which locations have been counted thus far.
	Make sure that once a item count is input into the table on the location page, the
	"ADD" button is pressed. Otherwise the count <emphasis>WILL NOT</emphasis> be added.
	If there are no items in a location, insert the number zero. This will indicate that
	the item was not there, instead of not counted.	Once every item is counted, select the 
	<a href="./saveWarning.php">Save and Print</a> link here, or above. 
	Once the <a href="./saveWarning.php">Save and Print</a> link is pressed, no more 
	items will be counted for this inventory count and the current counts will be removed.
	Make sure you are ready to do that.</p>
<?php

	$db = new PDO('sqlite:inventory.db');
	//Get location tables
	$locTables = $db->query("SELECT * FROM sqlite_master WHERE name LIKE 'loc%' ORDER BY name");
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

		$product = $db->query("SELECT * FROM sqlite_master WHERE name != 'items' AND name NOT LIKE 'sqlite%' AND name NOT LIKE 'loc%' AND name NOT LIKE 'inventory%' ORDER BY name");
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
			}
			
			
			if($size > 0){
				print "<div id='itemCounts'>";
							
				print "<table border='1' align='center'>";
				print "<tr>";
				print "<th width='325'>Item</th><th>Cases Count</th><th>Singles Count</th>";
				print	"</tr>";
				$color = 1;
				foreach($items as $item){
					$itemNum = $item['itemIDNum'];
					$itemName = $item['descrip'];
					$caseCount = $item['caseCount'];
					$singleCount = $item['itemCount'];
					if($color == 1){
						print "<tr bgcolor='#DDDDDD'>";
						$color = 2;
					} else {
						print "<tr bgcolor='#FFFFFF'>";
						$color = 1;
					}
					print "<td>$itemName</td>";
					print "<td>$caseCount</td>";
					print "<td>$singleCount</td>";
					print "</tr>";
				}// end foreach($items...)
				print "</table>";
				print "</div>";
			} //end if($size...)
		}//end foreach($prodTables...)

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
