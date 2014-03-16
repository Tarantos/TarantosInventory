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


<div id="container">
<div id="invTables">
	<p>To begin a new inventory count, select the <a href="printPaperCount.php">Printable Inventory Count</a>
	link. Once the items in each location are counted, select one of the links above to input the inventory count for that location.
	The table below shows which items in which locations have current items counted.
	Make sure that once a item count is input into the table on the location page, the
	"ADD" button is pressed. Otherwise the count <emphasis>WILL NOT</emphasis> be added.
	If there are no items in a location, insert the number zero. This will indicate that
	the item was not there, instead of not counted.	Once every item is counted, select the 
	<a href="./saveANDprint.php">Save and Print</a> link here, or above. 
	Once the <a href="./saveANDprint.php">Save and Print</a> link is pressed, no more 
	items will be counted for this inventory count and the current counts will be removed.
	Make sure you are ready to do that.</p>
<?php

	$db = new PDO('sqlite:inventorys.db');
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

		$product = $db->query("SELECT * FROM sqlite_master WHERE name != 'items' AND name NOT LIKE 'loc%' ORDER BY name");
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
			
			print "<div id='itemCounts'>";			
			print "<table border='1'>";
			if($size > 0){
				print "<tr>";
				print "<th width='325'>Item</th><th>Cases Count</th><th>Singles Count</th>";
				print	"</tr>";
			}
			foreach($items as $item){
				$itemNum = $item['itemIDNum'];
				$itemName = $item['descrip'];
				$caseCount = $item['caseCount'];
				$singleCount = $item['itemCount'];
				
				print "<tr>";
				print "<td>$itemName</td>";
				print "<td>$caseCount</td>";
				print "<td>$singleCount</td>";
				print "</tr>";
		}// end foreach($items...)
			print "</table>";
			print "</div>";
			
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
