<html>
<head>
	<title>Tarantos Inventory Tracking System - Remove Item From Location</title>
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
		<a href="./AddItem.php" class="button">Manage Items</a>
	</li>
	<li>
		<a href="NewInv.php" class="button">New Inventory Count</a>
	</li>
	</div>
	<!-- MENU SYSTEM -->		


<div id="container">	
<?php
// Get table if POST is available
	$removeItemFromTable = 'locFront';
	if(sizeof($_POST) > 0){
		$removeItemFromTable = $_POST['locTables'];
}
	// Open inventory template DB to edit
	$db = new PDO('sqlite:inventory.db');

	//Get product tables to pull organized items 
	$getTables = $db->query("SELECT * FROM sqlite_master WHERE name != 'items' AND name NOT LIKE 'sqlite%' AND name NOT LIKE 'loc%' AND name NOT LIKE 'inventory%' ORDER BY name ASC");
	$rowTables = $getTables->fetchAll();

	//Get location tables for removing items.
	$getLoc = $db->query("SELECT * FROM sqlite_master WHERE name LIKE 'loc%' ORDER BY name");
	$locations = $getLoc->fetchAll();


	// Display current items in the template DB
	print '<div id="invTables">';
		print '<b>Select Location to Remove Items From</b>';
		
		// Display a drop down list of locations
		print '<form action="removeFromLocation.php" method="POST">';
		print '<select name="locTables">';
		foreach($locations as $loc){
			$locName = $loc['name'];
			$hName = $locName;
			if($locName == 'locFront'){
				$hName = 'Front';
			}else if($locName == 'locHall'){
				$hName = 'Hall';
			}else if($locName == 'locInsideWalkin' ){
				$hName = 'Inside Walkin';
			}else if($locName == 'locOutsideWalkin' ){
				$hName = 'Outside Walkin';
			}else if($locName == 'locKitchen' ){
				$hName = 'Kitchen';
			}
			if($locName == $removeItemFromTable){
				print "<option value=\"$locName\" selected=\"selected\">$hName</option>";
			} else {
				print "<option value=\"$locName\">$hName</option>";
			}
		} // end foreach($locations...)
		print '</select>';
		print '<input type="submit" value="Select" />';
		print '</form>';

  print '<form action="removeItemFromLocation.php" method="POST">';
               
                // Display invTables then invItems below it
		foreach($rowTables as $row){
			$tname = $row['name'];

			$getItemCount = 0;
			$getItems = $db->query("SELECT * FROM items JOIN $tname ON items.itemIDNum=$tname.ItemID JOIN $removeItemFromTable ON $removeItemFromTable.ItemID=items.itemIDNum ORDER BY Descrip ASC");
                        
			// Display invItems 
			foreach($getItems as $item){
                          $stockNum = $item['itemIDNum'];
			  $descrip = $item['descrip'];
			  print "<label><input type='checkbox' name=\"{$removeItemFromTable}[]\" value=\"$stockNum\" location=\"$removeItemFromTable\" >$descrip</input></label>";
			} // End foreach($getItems...)
		
		} // End foreach($rowTables...)
		print "<input type='Submit' value='Remove' \>";
	print "</form>";
	print '</div>'; // END invItems
	$db = NULL;
	
	print '<div id="footer">';
		print '<b>Tarantos Inventory Tracking System</b>';
	print '</div>';
	
	print '</div>'; // end Container
	print '</body>';
?>
</html>
