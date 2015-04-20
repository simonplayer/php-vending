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
	<form action="index.php" method="post">  
    <!-- Page Content -->
    <div class="container">

         <div class="row">
         
         	<h1 class="page-header">Admin</h1>
        
            <ul class="nav nav-pills navbar-right">
                <li class="active"><a href="index.php?page=products">Products</a></li>
                <li><a href="index.php?page=coins">Coins</a></li>
            </ul>
            
            <br />
             
            <h3>Update Products</h3>       
             <table class="table">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                  </tr>
                </thead>
                <tbody>
<?php
	foreach ($vending->getProducts() as $product){
?>                
                  <tr>
                    <td><input type="text" name="name<?php echo $product["prd_id"]; ?>" class="form-control" value="<?php echo $product["prd_name"]; ?>"></td>
                    <td><input type="text" name="price<?php echo $product["prd_id"]; ?>" class="form-control" value="<?php echo $product["prd_price"]; ?>"></td>
                    <td><input type="text" name="quantity<?php echo $product["prd_id"]; ?>" class="form-control" value="<?php echo $product["prd_quantity"]; ?>"></td>
                  </tr>
<?php
	}
?>                  
                </tbody>
              </table>    
              <button type="submit" name="updateProducts" class="btn btn-primary btn-lg pull-right">Update Products</button>  
              
              <br />
              
            <h3>Add Product</h3>       
             <table class="table">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                  </tr>
                </thead>              
                  <tr>
                    <td><input type="text" name="name" class="form-control" value=""></td>
                    <td><input type="text" name="price" class="form-control" value=""></td>
                    <td><input type="text" name="quantity" class="form-control" value="0"></td>
                  </tr>                 
                </tbody>
              </table>    
              <button type="submit" name="addProduct" class="btn btn-primary btn-lg pull-right">Add Product</button>                   
			
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