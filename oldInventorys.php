<html>
<head>
<title> Previous Inventories </title>
</head>
<body>
<?php
	$db = new PDO("sqlite:inventory.db");
	$inventoryObj = $db->query("SELECT * FROM sqlite_master WHERE name LIKE 'inventory%'");

	$inventoryGroup = $inventoryObj->fetchAll();
	print "<form action='oldPrint.php' method='POST'>"; 
	print "<select name='inventoryToPrint'>";
	foreach($inventoryGroup as $inventory){
		$invName = $inventory['name'];
		$subName = substr($invName, 9, 8);
		$regName = ereg_replace("_", "/", $subName);
		print "<option name='table' value=".$invName.">Inventory from $regName</option>";
	}
	print "</select>";
	print "<input type='submit' value='Ok' />";
	print "</form>";
?>
</body>
</html>


