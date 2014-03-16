
<html>
<head>
<?php
$locName = $_GET['name'];
		if($locName == 'locFront'){
			$lName = 'Front Inventory';
			$previousName = 'locOutsideWalkin';
			$nextName = 'locHall';
		}	
		if($locName == 'locHall'){
			$lName = 'Hall Inventory';
			$previousName = 'locFront';
			$nextName = 'locInsideWalkin';
		}
		if($locName == 'locInsideWalkin'){
			$lName = 'Inside Walkin Inventory';
			$previousName = 'locHall';
			$nextName = 'locKitchen';
		}
		if($locName == 'locKitchen'){
			$lName = 'Kitchen Inventory';
			$previousName= 'locInsideWalkin';
			$nextName = 'locOutsideWalkin';

		}
		if($locName == 'locOutsideWalkin'){
			$lName = 'Outside Walkin Inventory';
			$previousName = 'locKitchen';
			$nextName = 'locFront';
		}
	print "<title>Tarantos Inventory Tracking System - $lName</title>";
?>
	<link rel="stylesheet" type="text/css" href="tstyle.css" />



	<script type="text/javascript"> 
	xmlhttp = new XMLHttpRequest();
	function stopRKey(evt) { 
 		var evt = (evt) ? evt : ((event) ? event : null); 
  		var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
  		if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
	} 
	document.onkeypress = stopRKey; 

	function insertItems(itemLocation, itemNumber) {
		xmlhttp.open("POST", "count.php?table="+itemLocation+"&item="+itemNumber, true);
	}
	
	function toUnicode(elmnt,content){
    		if (content.length==elmnt.maxLength){
			next=elmnt.tabIndex
			if (next<document.forms[0].elements.length){
				document.forms[0].elements[next].focus()
			}
		}
	}
	
	</script>

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
		<a href="./saveWarning.php" class="button">Save and Print</a>
	</li>
	</div>
	<!-- MENU SYSTEM -->


<div id="invTables">
<?php
	
	$db = new PDO('sqlite:inventory.db');

	//Get location tables
		print "<h3><a href=\"./inventoryCount.php?name=$previousName\" class='button'>Previous Location</a>$lName<a href=\"./inventoryCount.php?name=$nextName\" class='button'>Next Location</a></h3>";
		

		$product = $db->query("SELECT * FROM sqlite_master WHERE name != 'items' AND name NOT LIKE 'loc%' AND name NOT LIKE 'inventory%' AND name NOT LIKE 'sqlite%' ORDER BY name");
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
				print "<div id='itemInput'>";
				print "<ul style='display:block'>";
				$x = 0;
				$y = 1;

				foreach($items as $item){
					$itemNum = $item['itemIDNum'];
					$itemName = $item['descrip'];
					$packQuantity = $item['packQuantity'];
					$case = $item['caseCount'];
					$single = $item['itemCount'];
					$x += 1;
					$y += 1;

					if($packQuantity > 1){
						print "<li>$itemName: $packQuantity Items per Case:</li>";
					} else {
						print "<li>$itemName: </li>";
					}

					print "<a name=$itemNum>";
					print "<form name=$itemNum action=\"count.php?table=$locName&item=$itemNum\" method=\"POST\" >";
					print "<li><div id='itemInputFields'><ul style='display:inline'>";
										
					print "<li>Case Count";
					print "<input tabindex=$x type='text' name='cases' value=\"$case\" size='3' onkeyup='toUnicode(this,this.value)'/>";
					print "</li>";
					if($packQuantity > 1){
						print "<li>Item Count";
						print "<input tabindex=$y type='text' name='singles' value=\"$single\" size='3' onkeyup='toUnicode(this,this.value)'  />";
						print "</li>";
					} else {
						print "<li>Item Count: N/A";
						print "<input type='hidden' name='singles' value='0' size='3' readonly />";
						print "</li>";
					}
			       		print "<li><input type='submit' value='Add count' /></li>";		
					print "</ul>";
					print '</div>';
					
					print '</form>';
					print '</a>';
				}//end foreach($items...)
				print "</ul>";
				print "</div>";
			}//end if(size...)
		}//end foreach($prodTables...)
	print "<a href=\"./inventoryCount.php?name=$previousName\" class='button'>Previous Location</a><a href=\"./inventoryCount.php?name=$nextName\" class='button'>Next Location</a>";

	$db = NULL;
	
	print '<div id="footer">';
		print '<b>Tarantos Inventory Tracking System</b>';
	print '</div>';
	
	print '</div>'; // end invTables
	print '</body>';
	print '</html>';
?>
