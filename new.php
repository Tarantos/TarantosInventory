<html>
<body>
<?php
	$db2 = new PDO('sqlite:inventory.db');

	$sql = "SELECT * FROM invBud";
	echo '<table border="1" cellspacing="1" cellpadding="1">';
	echo '<th>Type</th>
		<th>Quantity in Stock</th>
		<th>Quantity per Case</th>
		<th>Price per Case</th>
		<th>Cost</th>';
	$totalCost = 0;

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
?>
</body>
</html>

