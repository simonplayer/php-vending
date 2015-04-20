<?php

# load the config
require ('config.inc.php');

# the vending class
require ('Vending.class.php');
$vending = new Vending();

# coins entered
if (isset($_GET["coin"])){# check quantity
    if (isset($_GET["coinsQuantity"])){
        if ($_GET["coinsQuantity"]<1){ # no coin quantity set - or a negative amount set!
            $msg = "Please enter a coin quantity";
        }
        else{
            # lets add the credit
            $vending->setCreditCoins($_GET["coin"], $_GET["coinsQuantity"]); # this stores all the entered coins into an array
            $vending->setCredit($_GET["coin"], $_GET["coinsQuantity"]); # update the credit
        }
    }
}

# eject coins
if (isset($_GET["ejectCoins"])){
    $vending->clearCredit();
    $msg = "Coins ejected";
}

# buy product
if (isset($_GET["buy"])){
    $product = $vending->getProduct($_GET["buy"]);
    if ($product["prd_price"] > $vending->getCredit()){
        # not enough credit
        $msg = "You do not have enough credit to buy this product";
    }
    elseif($product["prd_quantity"] == 0){
        # product sold out
        $msg = "This product has sold out";
    }
    else{
        # good to go - lets sell the product!

            # update product stock
            $vending->setProductStock($product);

            # add to sales
            $vending->insertProductSales($product);

            # deposit coins
            $vending->depositCoins();

            # give change
            if ($vending->getCredit() > $product["prd_price"]){
                $changeRequired = $vending->getCredit() - $product["prd_price"];
				
                $changeCoinsArr = $vending->getChange($changeRequired);
                
                #adjust cashed coin quantity
                foreach ($changeCoinsArr as $coin=> $quantity){
                    # get the new cashed coin quantity
                    $newCashedCoinQuantity = $vending->getCashedCoinQuantity($coin) - $quantity;
                    # update cashed coin quantity
                    $vending->setCashedCoinQuantity($coin, $newCashedCoinQuantity);
                }

                if ($changeCoinsArr){
                    $msg = "Here is your change: <br />";
                    foreach ($changeCoinsArr as $key=> $value){
                        $msg .= ' ' . $value . ' x ' . $vending->displayPrice($key) . '<br />';
                    }
                }
            }

            # clear credit
            $vending->clearCredit();

            # display a message on sale
            $buyMsg = "Enjoy your snack!";
    }
}

# load the view
include ('views/vending.php');

