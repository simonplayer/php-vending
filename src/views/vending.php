<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Vending Machine</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/custom.css" rel="stylesheet">

    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    <!-- Page Content -->
    <div class="container">

                <h1 class="page-header">Simon's Snacks!</h1>

        <div class="row">

            <!-- Column -->
            <div class="col-md-7">

                <?php if(isset($buyMsg)){?><div class="alert alert-success" role="alert"><?php echo $buyMsg; ?></div><?php } ?>

                <!--Products Row -->
                <div class="row">


<?php foreach ($vending->getProducts() as $product) { ?>
            <div class="col-lg-4 col-sm-6 text-center">
                <img class="img-circle img-responsive img-center" src="http://placehold.it/150&text=<?php echo $product["prd_name"]; ?>" alt="">
                <h3><?php echo $vending->displayPrice($product["prd_price"]); ?></h3>
<?php
    if ($product["prd_quantity"] ==0){ # the product can not be bought
?>
                <p><a href="./?buy=<?php echo $product["prd_id"]; ?>" class="btn btn-large btn-primary text-center disabled">Sold Out</a></p>
<?php
    }
	elseif($vending->CheckChangeAvailable($product["prd_price"]) == false){ # change can not be given so don't sell the product
?>
                <p><a href="./?buy=<?php echo $product["prd_id"]; ?>" class="btn btn-large btn-primary text-center disabled">Correct Change Only</a></p>
<?php
	}
    else { # ok to sell the product
?>
                <p><a href="./?buy=<?php echo $product["prd_id"]; ?>" class="btn btn-large btn-primary text-center <?php if ($vending->getCredit() < $product["prd_price"]) { echo 'disabled'; } ?>">Buy Now</a></p>
<?php
    }
?>
            </div>
<?php } ?>

		</div>

            </div>

            <!-- Sidebar -->
            <div class="col-md-5">

                <!-- credit and msg -->
                <div class="well">
                   <?php if(isset($msg)){?><div class="alert alert-danger" role="alert"><?php echo $msg; ?></div><?php } ?>
                    <h3>Credit: <?php echo $vending->displayPrice($vending->getCredit()); ?></h3>

                    <!-- insert coins -->
                    <form class="form" action="" method="get" id="addCoins">
                    <div class="well">
                        <h4>Insert Coins</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">Coin quantity</span>
                                    <input type="text" name="coinsQuantity" class="form-control" value="1" aria-describedby="basic-addon1">
                                </div>
                            </div>
                            <!-- /.col-lg-6 -->
                            <div class="col-lg-6">
<?php
    foreach ($vending->getCoins() as $coin){
    if ($coin["coins_active"]==1){
?>
                                <button type="submit" name="coin" value="<?php echo $coin["coins_value"]; ?>" class="btn btn-primary"><?php echo $vending->displayPrice($coin["coins_value"]); ?></button>
<?php
    }
    else{
?>
                                <button type="submit" class="btn btn-default disabled"><?php echo $vending->displayPrice($coin["coins_value"]); ?></button>
<?php }} ?>


                            </div>
                            <!-- /.col-lg-6 -->
                        </div>
                        <!-- /.row -->
                    </div>
                    </form>
                    <!-- /.row -->
                    <p><a href="./?ejectCoins=1" class="btn btn-large btn-danger text-center <?php if ($vending->getCredit() <= 0) { echo 'disabled'; } ?>">Eject Coins</a></p>
                </div>

            </div>

        </div>
        <!-- /.row -->

        <hr>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>
</html>