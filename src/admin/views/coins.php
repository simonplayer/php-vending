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
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/custom.css" rel="stylesheet">

    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
	<form action="index.php?page=coins" method="post">  
    <!-- Page Content -->
    <div class="container">

         <div class="row">
         
         	<h1 class="page-header">Admin</h1>
        
            <ul class="nav nav-pills navbar-right">
                <li><a href="index.php?page=products">Products</a></li>
                <li class="active"><a href="index.php?page=coins">Coins</a></li>
            </ul>
            
            <br />
             
            <h3>Update Coins</h3>       
             <table class="table">
                <thead>
                  <tr>
                    <th>Coin</th>
                    <th>Quantity</th>
                    <th>Active</th>
                  </tr>
                </thead>
                <tbody>
<?php
	foreach ($vending->getCoins() as $coin){
?>                
                  <tr>
                    <td><?php echo $vending->displayPrice($coin["coins_value"]); ?></td>
                    <td><input type="text" name="quantity<?php echo $coin["coins_id"]; ?>" class="form-control" value="<?php echo $coin["coins_quantity"]; ?>"></td>
                    <td><input name="active<?php echo $coin["coins_id"]; ?>" type="checkbox" value="1" <?php if ($coin["coins_active"] == 1){ echo 'checked'; } ?>></td>
                  </tr>
<?php
	}
?>                  
                </tbody>
              </table>    
              <button type="submit" name="updateCoins" class="btn btn-primary btn-lg pull-right">Update Coins</button>  
                
			
        </div>
        <!-- /.row -->

        <hr>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
	</form>
</body>
</html>