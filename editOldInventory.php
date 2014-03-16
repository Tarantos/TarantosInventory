<html>
<head>
<title> Update Selected Inventory Count </title>
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
	$inventoryToPrint = $_REQUEST['inventoryToPrint'];
	print '<div id="invTables">';
	$subInventoryToPrint = substr($inventoryToPrint, 9, 8);
	$regInventoryToPrint = ereg_replace("_", "/", $subInventoryToPrint);
	print "Edit Inventory Count from ".$regInventoryToPrint;
	$color = 1;

	$db = new PDO("sqlite:inventory.db");
	
	//Get Categories of items
	$catObj = $db->query("SELECT * FROM sqlite_master WHERE name NOT LIKE 'items' AND name NOT LIKE 'sqlite%' AND name NOT LIKE 'loc%' AND name NOT LIKE 'inventory%' ORDER BY name");
	$catAll = $catObj->fetchAll();

	foreach($catAll as $catTable){
		$catTableName = $catTable['name'];
		print "<h4> $catTableName </h4>";
		$tableCost = 0;
		print "<table border='1px'>";
		print '<tr>';
		print '<th width="225">Item</th><th>Pack Quantity</th><th>Pack Cost</th><th>On Hand Cases</th><th>On Hand Singles</th><th>On Hand Total</th><th>On Hand Cost</th><th>Update</th>';
		print '</tr>';

		//Get the items in current category
		$catItemsObj = $db->query("SELECT * FROM $catTableName");
		$catItems = $catItemsObj->fetchAll();
		foreach($catItems as $catItem){
			$catItemID = $catItem['ItemID'];

			//Get the descrip of the current item
			$itemDescripObj = $db->query("SELECT descrip FROM items WHERE items.ItemIDNum = $catItemID");
			$itemDescrip = $itemDescripObj->fetchAll();
			foreach($itemDescrip as $itemD){
				$descrip = $itemD['descrip'];
				//Get info from $inventoryToPrint for current item based on descrip
				$printObj = $db->query("SELECT * FROM $inventoryToPrint WHERE descrip = \"$descrip\" ");
				$printInvTable = $printObj->fetchAll();

				print '<div id="invItems">';
				//Print info on the current item	
				foreach($printInvTable as $printInvRow){
				
			
					$itemNum = $printInvRow['invDateID'];
					$printInvRowDescrip = $printInvRow['descrip'];
					$printInvRowPackQuantity = $printInvRow['packQuantity'];
					$printInvRowPackCost = $printInvRow['packCost'];
					$printInvRowOnHandCases = $printInvRow['onHandCases'];
					$printInvRowOnHandSingles = sprintf("%.2F", $printInvRow['onHandSingles']);
					$printInvRowOnHandTotalCount = sprintf("%.2F", $printInvRow['onHandTotalCount']);
					$printInvRowOnHandTotalCost = sprintf("%.2F", $printInvRow['onHandTotalCost']);
					print "<a name=$itemNum />";
					print "<form name=$itemNum action=\"updateOldInventory.php?table=$inventoryToPrint&item=$itemNum&quantity=$printInvRowPackQuantity\" method=\"POST\" >";
					if($color == 1){
						print '<tr bgcolor=#FFFFFF>';
						$color = 2;
					} else {
						print '<tr bgcolor="#F0EAD6">';
						$color = 1;
					}
					print '<td>'.$printInvRowDescrip.'</td>';
					print '<td>'.$printInvRowPackQuantity.'</td>';
					print '<td><input type="text" name="packCost" value="'.$printInvRowPackCost.'" /></td>';
					print '<td><input  type="text" name="onHandCases" value="'.$printInvRowOnHandCases.'" /></td>';
					print '<td><input type="text" name="onHandSingles" value="'.$printInvRowOnHandSingles.'" /></td>';
					print '<td>'.$printInvRowOnHandTotalCount.'</td>';
					print '<td>'.$printInvRowOnHandTotalCost.'</td>';
					print "<td><input type='submit' value='Update' /></td>";
					print '</tr>';
					print "</form>";
					
				} // foreach($printInvTable...)
				print "</div>";
			} //end foreach($itemDescrip..)
		}//end foreach($catItems...)
		print "</table>";
	
	}//end foreach($catAll...)
	print "</div>"; //end table div

	print '<div id="footer">';
		print '<b>Tarantos Inventory Tracking System</b>';
	print '</div>';
?>
</div>
</body>
</html>
		
