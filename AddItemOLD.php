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
		<a href="./index.php">Home</a>
	</li>
	<li>
		<a href="./AddItem.php">Manage Items</a>
	</li>
	<li>
		<a href="NewInv.php">New Inventory Count</a>
	</li>
	</div>
	<!-- MENU SYSTEM -->		
<div id="container">	
<?php
	// Open inventory template DB to edit
	$db = new PDO('sqlite:inventory.db');
	
	print '<div id="newItem">';		
		print '<form action="Add.php" method="POST">';
	
		print '<label for="Item">Item: </label>';
		print '<input type="text" name="Item" value="" />';
	
		print '<label for="Tables">Table: </label>';
		print '<select name="Tables">';
		$getTables = $db->query("SELECT * FROM sqlite_master");
		$rowTables = $getTables->fetchAll();
			foreach($rowTables as $row){
				$invTable = $row['name'];
				print "<option value=\"$invTable\">$invTable</option>";
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
			$getItems = $db->query("SELECT * FROM $tname");
		
			// Display invItems 
			foreach($getItems as $item){
			print '<div id="invItems">';
			$stockNum = $item['StockNumber'];
			 	print '<li>';
				print "<form action=\"Del.php?table=$tname&StockNum=$stockNum\" method=\"POST\">";	
					print '<input type="Submit" value="Delete" />';
				print '</form>';
				print '</li>';
				print '<li>';
					print $item['Descrip'];
					$desc = $item['Descrip'];
					print '<ul>';
						print '<li>';
							$PackQty = $item['PackQty'];
							print "Pack Quantity: $PackQty ";
						print '</li>';
						print '<li>';
							$PackCost = $item['PackCost'];
							print "Pack Cost: $PackCost ";
						print '</li>';
					print '</ul>';
				print '</li>';
				print '<form action=\"Edit.php?table=$tname&item=$desc&amount=$
				
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