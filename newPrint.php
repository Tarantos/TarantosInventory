<html>
<head>
<title> Print Selected Inventory Count </title>
<link rel="stylesheet" type="text/css" href="print.css" />
</head>
<body>
<?php
$inventoryToPrint = $_GET['table'];

	print $inventoryToPrint;

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
		print '<th width="225">Item</th><th>Pack Quantity</th><th>Pack Cost</th><th>On Hand Cases</th><th>On Hand Singles</th><th>On Hand Total</th><th>OnHand Cost</th>';
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
				
				//Print info on the current item	
				foreach($printInvTable as $printInvRow){
					$printInvRowDescrip = $printInvRow['descrip'];
					$printInvRowPackQuantity = $printInvRow['packQuantity'];
					$printInvRowPackCost = $printInvRow['packCost'];
					$printInvRowOnHandCases = $printInvRow['onHandCases'];
					$printInvRowOnHandSingles = sprintf("%.2F",$printInvRow['onHandSingles']);
					$printInvRowOnHandTotalCount = sprintf("%.2F",$printInvRow['onHandTotalCount']);
					$printInvRowOnHandTotalCost = sprintf("%.2F",$printInvRow['onHandTotalCost']);
					$tableCost += $printInvRowOnHandTotalCost;
					print '<tr>';
					print '<td>'.$printInvRowDescrip.'</td>';
					print '<td>'.$printInvRowPackQuantity.'</td>';
					print '<td>'.$printInvRowPackCost.'</td>';
					print '<td>'.$printInvRowOnHandCases.'</td>';
					print '<td>'.$printInvRowOnHandSingles.'</td>';
					print '<td>'.$printInvRowOnHandTotalCount.'</td>';
					print '<td>'.$printInvRowOnHandTotalCost.'</td>';
				} // foreach($printInvTable...)
			} //end foreach($itemDescrip..)
		}//end foreach($catItems...)
		print "</table>";
		print "Total cost of $catTableName is \$$tableCost";
		print "<div style='page-break-after:always'></div>";
		if($catTableName == 'Boxes'){
			$BoxesTotalCost = $tableCost;
		}else if($catTableName == 'Bread'){
			$BreadTotalCost = $tableCost;
		}else if($catTableName == 'Budweiser'){
			$BudTotalCost = $tableCost;
		}else if($catTableName == 'Cheese'){
			$CheeseTotalCost = $tableCost;
		}else if($catTableName == 'Cleaning'){
			$CleaningTotalCost = $tableCost;
		}else if($catTableName == 'CocaCola'){
			$CokeTotalCost = $tableCost;
		}else if($catTableName == 'Containers'){
			$ContainersTotalCost = $tableCost;
		}else if($catTableName == 'Dessert'){
			$DessertTotalCost = $tableCost;
		}else if($catTableName == 'Dressing'){
			$DressingTotalCost = $tableCost;
		}else if($catTableName == 'Dry'){
			$DryTotalCost = $tableCost;
		}else if($catTableName == 'Fry'){
			$FryTotalCost = $tableCost;
		}else if($catTableName == 'Lettuce'){
			$LettuceTotalCost = $tableCost;
		}else if($catTableName == 'Meat'){
			$MeatTotalCost = $tableCost;
		}else if($catTableName == 'Paper'){
			$PaperTotalCost = $tableCost;
		}else if($catTableName == 'Pastas'){
			$PastasTotalCost = $tableCost;
		}else if($catTableName == 'Sauce'){
			$SauceTotalCost = $tableCost;
		}else if($catTableName == 'Soup'){
			$SoupTotalCost = $tableCost;
		}else if($catTableName == 'Vegetable'){
			$VegetableTotalCost = $tableCost;
		}
	}//end foreach($catAll...)
	print "<h4>Summary</h4>";
	print "<table border='1px'>";
	print '<tr>';
	print '<td>Boxes Cost</td>';
	print "<td>$BoxesTotalCost</td>";
	print '</tr>';
	print '<tr>';
	print '<td>Bread Cost</td>';
	print "<td>$BreadTotalCost</td>";
	print '</tr>';	
	print '<tr>';
	print '<td>Budweiser Cost</td>';
	print "<td>$BudTotalCost</td>";
	print '</tr>';	
	print '<tr>';
	print '<td>Cheese Cost</td>';
	print "<td>$CheeseTotalCost</td>";
	print '</tr>';	
	print '<tr>';
	print '<td>Cleaning Cost</td>';
	print "<td>$CleaningTotalCost</td>";
	print '</tr>';	print '<tr>';
	print '<td>Coca Cola Cost</td>';
	print "<td>$CokeTotalCost</td>";
	print '</tr>';	print '<tr>';
	print '<td>Containers Cost</td>';
	print "<td>$ContainersTotalCost</td>";
	print '</tr>';	print '<tr>';
	print '<td>Desserts Cost</td>';
	print "<td>$DessertTotalCost</td>";
	print '</tr>';	print '<tr>';
	print '<td>Dressings Cost</td>';
	print "<td>$DressingTotalCost</td>";
	print '</tr>';
		print '<tr>';
	print '<td> Dry Goods Cost</td>';
	print "<td>$DryTotalCost</td>";
	print '</tr>';
		print '<tr>';
	print '<td>Fry Goods Cost</td>';
	print "<td>$FryTotalCost</td>";
	print '</tr>';
		print '<tr>';
	print '<td>Lettuce Cost</td>';
	print "<td>$LettuceTotalCost</td>";
	print '</tr>';
		print '<tr>';
	print '<td>Meats Cost</td>';
	print "<td>$MeatTotalCost</td>";
	print '</tr>';	print '<tr>';
	print '<td>Paper Goods Cost</td>';
	print "<td>$PaperTotalCost</td>";
	print '</tr>';	print '<tr>';
	print '<td>Pastas Cost</td>';
	print "<td>$PastasTotalCost</td>";
	print '</tr>';
		print '<tr>';
	print '<td>Sauces Cost</td>';
	print "<td>$SauceTotalCost</td>";
	print '</tr>';	print '<tr>';
	print '<td>Soups Cost</td>';
	print "<td>$SoupTotalCost</td>";
	print '</tr>';	print '<tr>';
	print '<td>Vegetables Cost</td>';
	print "<td>$VegetableTotalCost</td>";
	print '</tr>';
	print "<tr><td>Total Cost</td><td>";
	print $BoxesTotalCost + 
		$BreadTotalCost +
		$BudTotalCost +
		$CheeseTotalCost + 
		$CleaningTotalCost +
		$CokeTotalCost +
		$ContainersTotalCost + 
		$DessertTotalCost +
		$DressingTotalCost + 
		$DryTotalCost +
		$FryTotalCost +
		$LettuceTotalCost +
		$MeatTotalCost +
		$PaperTotalCost + 
		$PastasTotalCost +
		$SauceTotalCost +
		$SoupTotalCost + 
		$VegetableTotalCost;
	print "</td></tr>";

	print '</table>';
?>
</body>
</html>
		
