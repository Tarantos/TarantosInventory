<html>
<head>
	<title>Tarantos Inventory Tracking System</title>
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
		<a href="./build.php" class="button">Add Items to a Location</a>
	</li>
	<li>
		<a href="./removeFromLocation.php" class="button"> Remove From Location</a>
	</li>
	<li>
		<a href="NewInv.php" class="button">New Inventory Count</a>
	</li>
	</div>
	<!-- MENU SYSTEM -->		


<div id="container">	
<?php
	// Open inventory template DB to edit
	$db = new PDO('sqlite:inventory.db');
	
	print '<div id="newItem">';		
		print '<form action="Add.php" method="POST">';
	
		print '<label for="Item" selected="selected">Item: </label>';
		print '<input type="text" name="Item" value="" />';
	
		print '<label for="Tables">Category: </label>';
		print '<select name="Tables">';
		$getTables = $db->query("SELECT * FROM sqlite_master WHERE name != 'items' AND name NOT LIKE 'sqlite%' AND name NOT LIKE 'loc%' AND name NOT LIKE 'inventory%' ORDER BY name ASC");
		$rowTables = $getTables->fetchAll();
			foreach($rowTables as $row){
				$invTable = $row['name'];
				print "<option ";
				if(isset($_GET['table'])){
					if($invTable == $_GET['table']){print 'selected="selected" ';}}

				print "value=\"$invTable\">$invTable</option>";
			}
		print '</select>';
	
		print '<label for="PackQty">Package Quantity: </label>';
		print '<input type="text" name="PackQty" value="" />';
		
		print '<label for="Price">Price: </label>';
		print '<input type="text" name="Price" value="" />';
	
		print '<input type="submit" value="Add Item" />';
		print '</form>';
	print '</div>';

	// Display current items in the template DB
	print '<div id="invTables">';
		print '<b>Current Items in Inventory</b>';
	
		// Display invTables then invItems below it
		foreach($rowTables as $row){
			$tname = $row['name'];
			print "<h4>$tname</h4>";
			print "<ul id='tNames' title=\"$tname\">";
			$getItems = $db->query("SELECT * FROM items JOIN $tname ON items.itemIDNum=$tname.ItemID ORDER BY Descrip ASC");
			
			// Display invItems 
			foreach($getItems as $item){
			print '<div id="invItems">';
			$stockNum = $item['itemIDNum'];
			$descrip = $item['descrip'];
			$PackQty = $item['packQuantity'];
			$PackCost = $item['packCost'];
				print "<form action=\"Del.php?table=$tname&StockNum=$stockNum\" method=\"POST\">";	
					print '<input type="Submit" value="Delete" />';
			print '</form>';
			$refDesc = str_replace(" ", "", $descrip);
			print "<a name=\"$refDesc\">";
				print "<form action=\"Edit.php?table=$tname&id=$stockNum\" method=\"POST\">";
					print "<input type=\"text\" name=\"Descrip\" value=\"$descrip\" size=\"30\" />";
					print 'Units/Case';
					print "<input type=\"text\" name=\"PackQty\" value=\"$PackQty\" size=\"3\" style=\"text-align:right\" />";
					print '$';
					print "<input type=\"text\" name=\"PackCost\" value=\"$PackCost\" size=\"6\" style=\"text-align:right\" />";
					print '<input type="submit" value="Update" />';
				print "</form>";
				print "</a>";	
			print '</div>';	
			} // End foreach($getItems...)
		
			print '</ul>';
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
