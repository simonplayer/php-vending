<?php

class Vending{

    /**
     * the pdo db object
     */
    private $dbConn = null;

    /**
     * the credit of coins entered into the vending machine
     */
    private $credit = 0;

    /**
     * array containing coins and their quantity entered into the vending machine
     */
    private $creditCoins = array();

    /**
     * our constructer
     */
    public function __construct(){
        /**
         * set the db object
         */
        $this->dbConn = new PDO( 'mysql:host=' . VENDING_MYSQL_HOST . ';dbname='. VENDING_MYSQL_DB .';charset=utf8', VENDING_MYSQL_USERNAME, VENDING_MYSQL_PASSWORD );

        /**
         * set the credit from the session data
         */
        if (isset($_SESSION["credit"])){
            $this->credit = $_SESSION["credit"];
        }

        /**
         * set the coins from the session data
         */
        if (isset($_SESSION["creditCoins"])){
            $this->creditCoins = $_SESSION["creditCoins"];
        }
        else{
            $_SESSION["creditCoins"] = array();
        }
    }

    /**
     * get array of all products from db
     */
    public function getProducts(){
        $sql = "SELECT * from products";
        $st = $this->dbConn-> prepare($sql);
        $st -> execute();
        return $st -> fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * get product from db
     * @param $product
     * @return array
     */
    public function getProduct($product){
        $sql = "SELECT * from products WHERE prd_id = :product";
        $st = $this->dbConn-> prepare($sql);
        $st -> bindValue(":product", $product, PDO::PARAM_INT);
        $st -> execute();
        return $st -> fetch();
    }
	
    /**
     * update product db record
     * @param $product
	 * @param $arr
     */
    public function updateProduct($product, $arr){
        $sql = "
			UPDATE products 
			SET prd_name = :name,
				prd_price = :price,
				prd_quantity = :quantity
			WHERE prd_id = :product
		";
        $st = $this->dbConn-> prepare($sql);
        $st -> bindValue(":name", $arr["name"], PDO::PARAM_STR);
		$st -> bindValue(":price", $arr["price"], PDO::PARAM_INT);
		$st -> bindValue(":quantity", $arr["quantity"], PDO::PARAM_INT);
		$st -> bindValue(":product", $product, PDO::PARAM_INT);
        $st -> execute();
    }	
	
    /**
     * add a new product db record
	 * @param $arr
     */
    public function addProduct($arr){
		if ($arr["name"] == "") { return false; }
		$sql="
			INSERT INTO products 
				( 
					prd_name,
					prd_price,
					prd_quantity				
				)
				VALUES	
				( 
					:name,
					:price,
					:quantity
									 
				 )
		";	
        $st = $this->dbConn-> prepare($sql);
        $st -> bindValue(":name", $arr["name"], PDO::PARAM_STR);
		$st -> bindValue(":price", $arr["price"], PDO::PARAM_INT);
		$st -> bindValue(":quantity", $arr["quantity"], PDO::PARAM_INT);
        $st -> execute();
    }		

    /**
     * get coins from db
     * @return array
     */
    public function getCoins(){
        $sql = "SELECT * from coins";
        $st = $this->dbConn-> prepare($sql);
        $st -> execute();
        return $st -> fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * get the credit of coins entered prior to sale
     * @return int
     */
    public function getCredit(){
        return $this->credit;
    }

    /**
     * set the credit of coins entered prior to sale
     * @param $coin
     * @param $quantity
     */
    public function setCredit($coin, $quantity){
        $this->credit = $this->credit + $coin * $quantity;
        $_SESSION["credit"] = $this->credit;
    }

    /**
     * set the coins entered and their quantity prior to sale
     * @param $coin
     * @param $quantity
     */
    public function setCreditCoins($coin, $quantity){
        if (isset($_SESSION["creditCoins"][$coin])){
            $_SESSION["creditCoins"][$coin] = $_SESSION["creditCoins"][$coin] + (int)$quantity;
			$this->creditCoins = $_SESSION["creditCoins"];
        }
        else{
            $_SESSION["creditCoins"][$coin] = (int)$quantity;
			$this->creditCoins = $_SESSION["creditCoins"];
        }
    }
	
    /**
     * get the coins entered and their quantity prior to sale
     * @param array
     */
    public function getCreditCoins(){
		return $this->creditCoins;
    }	

    /**
     * update the product stock level in the db
     * @param $product
     */
    public function setProductStock($product){
        $newStockQuantity = $product["prd_quantity"] - 1;
        $st = $this->dbConn-> prepare("UPDATE  products SET prd_quantity = :newStockQuantity WHERE prd_id = :product");
        $st -> bindValue(":newStockQuantity", $newStockQuantity, PDO::PARAM_INT);
        $st -> bindValue(":product", $product["prd_id"], PDO::PARAM_INT);
        $st -> execute();
    }

    /**
     * record the sale in the db
     * @param $product
     */
    public function insertProductSales($product){
        $sql="INSERT INTO sales (
			sales_product,
			sales_price,
			sales_datetime
		 )
		 VALUES	(
			:sales_product,
			:sales_price,
			:sales_datetime

		 )";
        $st = $this->dbConn-> prepare($sql);
        $st -> bindValue(":sales_product", $product["prd_id"], PDO::PARAM_INT);
        $st -> bindValue(":sales_price", $product["prd_price"], PDO::PARAM_INT);
        $st -> bindValue(":sales_datetime", date( 'Y-m-d H:i:s', time() ), PDO::PARAM_STR);
        $st -> execute();
    }

    /**
     * take the entered coins and deposit them, updating the coins db
     */
    public function depositCoins(){
        foreach ($this->creditCoins as $coin => $quantity){
            # get the new cashed coin quantity
            $newCashedCoinQuantity = $this->getCashedCoinQuantity($coin) + $quantity;
            # update cashed coin quantity
            $this->setCashedCoinQuantity($coin, $newCashedCoinQuantity);
        }
    }

    /**
     * get the quantity of a coin stored in the machine from the db
     * @param $coin
     * @return array
     */
    public function getCashedCoinQuantity($coin){
        $st = $this->dbConn-> prepare("SELECT coins_quantity from coins WHERE coins_value = :coin");
        $st -> bindValue(":coin", $coin, PDO::PARAM_INT);
        $st -> execute();
        $arr =  $st -> fetch();
        return $arr["coins_quantity"];
    }

    /**
     * get available coins from the db
     * @return array
     */
    public function getCashedCoins(){
        $st = $this->dbConn-> prepare("SELECT * from coins WHERE coins_quantity !=0 ORDER BY coins_value DESC");
        $st -> execute();
        $arr =  $st -> fetchAll(PDO::FETCH_ASSOC);
        return $arr;
    }
	
    /**
     * get available coins from both cashed coins and enetered coins
     * @return array
     */
    public function getAvailableCoins(){
		$AvailableCoins = array();
		// get cashed coins
		foreach ($this->getCashedCoins() as $coin){
			if (isset($AvailableCoins[$coin["coins_value"]])){
				$AvailableCoins[$coin["coins_value"]] = (int)$AvailableCoins[$coin["coins_value"]] + (int)$coin["coins_quantity"];
			}
			else{
				$AvailableCoins[$coin["coins_value"]] = (int)$coin["coins_quantity"];
			}
		}
		// get entered coins prio to sale
		foreach ($this->getcreditCoins() as $coins_value => $coin_quantity){
			if (isset($AvailableCoins[$coins_value])){
				$AvailableCoins[$coins_value] = (int)$AvailableCoins[$coins_value] + (int)$coin_quantity;
			}
			else{
				$AvailableCoins[$coins_value] = (int)$coin_quantity;
			}
		}
		return $AvailableCoins;
    }	
	
    /**
     * check to see if change is availabe for given product / entered credit
	 * @param $amount
     * @return true|false
     */
    public function CheckChangeAvailable($amount){
		$amount  = $amount * 100;
		$AvailableCoins = $this->getAvailableCoins();
		krsort($AvailableCoins);

		foreach ($AvailableCoins as $coin_value => $coin_quantity){
			$coin_value = $coin_value * 100;
 			while ($coin_value <= $amount){
	 
                        # adjust quantity
                        $coin_quantity = $coin_quantity -1;

                        # adjust the amount
                        $amount = $amount - $coin_value;

                        # break if zero quantity
                        if ($coin_quantity <= 0){
                            break;
                        }													
			}			
		}
		if ($amount == 0){
			return true;
		}
		else{
			return false;
		}
    }
	
    /**
     * Build array of change required
	 * @param $amount
     * @return array
     */
    public function getChange($amount){
		$changeCoinsArr = array();
		$amount  = $amount * 100;
		$amount = (string)$amount;
		$cashedCoins =  $this->getCashedCoins(); # get available coins
		
		foreach ($cashedCoins as $cashedCoin){
			$coin = (string)$cashedCoin["coins_value"];
			$cashedCoin["coins_value"] = $cashedCoin["coins_value"] * 100;
			while ($cashedCoin["coins_value"] <= $amount){

				# add to changeCoinsArr array
				if (isset($changeCoinsArr[$coin])){
					$changeCoinsArr[$coin] = $changeCoinsArr[$coin] + 1;
				}
				else{
					$changeCoinsArr[$coin] = 1;
				}

				# adjust the cashed quantity
				$cashedCoin["coins_quantity"] = $cashedCoin["coins_quantity"] -1;

				# adjust the change required amount
				$amount = $amount - $cashedCoin["coins_value"];

				# break if zero quantity
				if ($cashedCoin["coins_quantity"] <= 0){
					break;
				}
			}
		}		

		return $changeCoinsArr;
	}			
	
    /**
     * update the coins quantity in the db
     * @param $coin
     * @param $quantity
     */
    public function setCashedCoinQuantity($coin, $quantity){
        $st = $this->dbConn-> prepare("UPDATE coins SET coins_quantity = :quantity WHERE coins_value = :coin");
        $st -> bindValue(":quantity", $quantity, PDO::PARAM_INT);
        $st -> bindValue(":coin", $coin, PDO::PARAM_INT);
        $st -> execute();
    }
	
    /**
     * update product db record
     * @param $product
	 * @param $arr
     */
    public function updateCoins($coin, $arr){
        $sql = "
			UPDATE coins
			SET coins_quantity = :quantity,
				coins_active = :active
			WHERE coins_id = :coin
		";
        $st = $this->dbConn-> prepare($sql);
		$st -> bindValue(":quantity", $arr["quantity"], PDO::PARAM_INT);
		$st -> bindValue(":active", $arr["active"], PDO::PARAM_INT);
		$st -> bindValue(":coin", $coin, PDO::PARAM_INT);
        $st -> execute();
    }		

    /**
     * eject the coins - clear credit
     */
    public function clearCredit(){
        $this->credit = 0;
        $_SESSION["credit"] = 0;
        $this->creditCoins = array();
        $_SESSION["creditCoins"] = array();
    }

    /**
     * display formatted product price
     * @param $price
     * @return string
     */
    public function displayPrice($price){
        if ($price < 1){
            return 100 * $price . 'p';
        }
        else {
            return VENDING_CURRENCY_CODE . number_format($price,2);
        }
    }

}