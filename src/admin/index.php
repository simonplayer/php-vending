<?php

# load the config
require ('../config.inc.php');

# the vending class
require ('../Vending.class.php');
$vending = new Vending();

# update products
if (isset($_POST["updateProducts"])){
	foreach ($vending->getProducts() as $product){
		$arr = array(
			"name" => trim($_POST["name" . $product["prd_id"]]),
			"price" => trim($_POST["price" . $product["prd_id"]]),
			"quantity" => trim($_POST["quantity" . $product["prd_id"]])
		);
		$vending->updateProduct($product["prd_id"], $arr);
	}	
}

# add product
if (isset($_POST["addProduct"])){
	$arr = array(
		"name" => trim($_POST["name"]),
		"price" => trim($_POST["price"]),
		"quantity" => trim($_POST["quantity"])
	);
	$vending->addProduct($arr);
}

# update coins
if (isset($_POST["updateCoins"])){
	foreach ($vending->getCoins() as $coin){
 		if (!isset($_POST["active" . $coin["coins_id"]])){
			$_POST["active" . $coin["coins_id"]] = 0;
		}
		$arr = array(
			"quantity" => trim($_POST["quantity" . $coin["coins_id"]]),
			"active" => trim($_POST["active" . $coin["coins_id"]])
		);
		$vending->updateCoins($coin["coins_id"], $arr);
	}	
}

# load view
if (isset($_GET["page"])){
	if 	($_GET["page"] == "products"){
		include ("views/products.php");
	}
	elseif ($_GET["page"] == "coins"){
		include ("views/coins.php");
	}
}
else{
	include ("views/products.php");
}


?>