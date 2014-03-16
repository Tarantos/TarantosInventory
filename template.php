<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Taranto's Inventory Tracking System</title>
		<link rel="stylesheet" type="text/css" href="tstyle.css" />
	</head>
	<body>
		<div id="menu">
			<ul>
				<li><a href="./AddItem">AddItem</a></li><li></li>
				<li></li><?php echo "<li>".date("m/d/y")."</li>"; ?>
			</ul>
		</div> <!-- close menu -->

	<?php
	$db2 = new PDO('sqlite:inventory.db'); //open sqlitedb, template?

	$sql = "SELECT * FROM invBud";         //set standard sql query to build table
	echo date("d/m/y");
	echo '<table border="1" cellspacing="1" cellpadding="1">';
	echo '<th>Type</th>			
		<th>Quantity in Stock</th>
		<th>Quantity per Case</th>
		<th>Price per Case</th>
		<th>Cost</th>';   		//builds table headers
	$totalCost = 0;				//variable for total cost of items in table

	//build table with data from database
	foreach($db2->query($sql) as $row) {	
		$cost = round(($row['OnHandQty']) * ($row['PackQty'] / $row['PackCost']), 2);
		print "<tr><td align='left'>".$row['Descrip']."</td>";
		print "<td align='right'>".$row['OnHandQty']."</td>";
		print "<td align='right'>".$row['PackQty']."</td>";
		print "<td align='right'>".$row['PackCost']."</td>";
		print "<td align='right'>$".$cost."</td>";
		print "</tr>";
		$totalCost += $cost;
	}
	echo '<tr><td colspan="4" align="right">Total Cost</td><td>$'.$totalCost.'</td></tr>';
	echo '</table>';
	echo '<hr />';

	echo "Total Cost: ".$totalCost;
	echo "<form name='addItem' method='post'><br />";
		echo "<select name='invRow'>";
			$getTables = $db2->query("SELECT * FROM sqlite_master");
			$rowTables = $getTables->fetchAll();
			$lameRow = 0;

			foreach($rowTables as $row){
				$item = $row['name'];
				print "<option value='$item'>$item</option>";
			}

			print_r($rowTables);

			?>

			<input type="submit" value="Select Table">
		</select>
	</form>
	</body>


</html>
