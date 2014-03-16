<html>

<head>
	<title>Tarantos Inventory Tracking System -- WARNING!!!</title>
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
		</ul>
	
	</div>


<div id="container">
<div id="invTables">
	<h4>WARNING!!</h4>
	<p>You are about to complete the SAVE process. If you continue and are not finished you will have to restart inputting values. Please make certain that you are ready for this. The Continue button is at the bottom of the page, please review the inventory count (no blank counts) before pressing this button. </p>
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
	print '<p>Click the Continue button if everything is in order.</p>';
	print '<a href="./save.php" class="button">Continue</a>';
	print '</div>';
	
	print '<div id="footer">';
		print '<b>Tarantos Inventory Tracking System</b>';
	print '</div>';
	
	print '</div>'; // end Container
	print '</body>';
	print '</html>';
?>
	
</body>
</html>
