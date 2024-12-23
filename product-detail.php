<?php include "include/header.php" ?>
<?php include "dbcon.php" ?>
<?php session_start(); ?>

<?php if(!isset($_SESSION['username']))
        {
            header("Location: index.php");
        }
?>


  



<?php
        if(isset($_GET['product_id']))
        {
            $product_id = $_GET['product_id'];

            $data = $conn->query("SELECT * FROM product WHERE product_id = '$product_id' ");
            $data->execute();

            $product = $data->fetchAll(PDO::FETCH_OBJ);

        }

        $query = $conn->prepare("SELECT * FROM review WHERE product_id = '$product_id' "); 
        $query->execute();

        $review = $query->fetchAll(PDO::FETCH_OBJ);

?>

  

<?php
        $name = $conn->prepare("SELECT * FROM users WHERE username = :username ");
        $name->execute([
            ':username' => $_SESSION['username']
        ]);

        $data = $name->fetchAll(PDO::FETCH_OBJ);

?>

<?php
    // to show the cart value
        if (isset($_SESSION['users_id'])) {
        $user_id = $_SESSION['users_id'];
        
        $query = $conn->prepare("SELECT SUM(quantity) AS total_quantity FROM cart WHERE users_id = :users_id ");
        $query->execute([

            ':users_id' => $user_id
        ]);

        $result = $query->fetch(PDO::FETCH_OBJ);

    // Initialize to an empty array if no result is found
    if ($result === false) 
    {
        $result = [];
    }
} 
    else 
    {
    $result = []; // Set result to an empty array if user is not logged in
    }




    // to show the wishlist value
    if (isset($_SESSION['users_id'])) {
        $user_id = $_SESSION['users_id'];
        
        $querys = $conn->prepare("SELECT SUM(quantity) AS total_quantity FROM wishlist WHERE users_id = :users_id ");
        $querys->execute([

            ':users_id' => $user_id
        ]);

        $wish = $querys->fetch(PDO::FETCH_OBJ);

    // Initialize to an empty array if no result is found
    if ($result === false) 
    {
        $result = [];
    }
} 
    else 
    {
    $result = []; // Set result to an empty array if user is not logged in
    }

?>



    <body>
  
        
        <!-- Nav Bar Start -->
        <div class="nav">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-md bg-dark navbar-dark">
                    <a href="index.php" class="navbar-brand">MENU</a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto">
                            <a href="index.php" class="nav-item nav-link active">Home</a>
                            <a href="cart.php" class="nav-item nav-link">Cart</a>
                            <a href="checkout.php" class="nav-item nav-link">Checkout</a>
                            <a href="my-account.php" class="nav-item nav-link">My Account</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">More Pages</a>
                                <div class="dropdown-menu">
                                    <a href="wishlist.php" class="dropdown-item">Wishlist</a>
                                    <a href="contact.php" class="dropdown-item">Contact Us</a>
                                </div>
                            </div>
                        </div>

                        <?php if (!isset($_SESSION['username'])): ?>
                            <div class="navbar-nav ml-auto">
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" id="guestDropdown" data-toggle="dropdown">USER</a>
                                    <div class="dropdown-menu" aria-labelledby="guestDropdown">
                                        <a href="login.php" class="dropdown-item">Login / Register</a>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="navbar-nav ml-auto">
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" id="userDropdown" data-toggle="dropdown">
                                        <?php echo $_SESSION['username']; ?>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="userDropdown">
                                        <a href="logout.php" class="dropdown-item">Logout</a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>  
                            </div>
                        </div>

                    </div>
                </nav>
            </div>
        </div>
        <!-- Nav Bar End -->      
        
        <!-- Bottom Bar Start -->
        <div class="bottom-bar">
            <div class="container-fluid">
                <div class="row align-items-center">

                    <div class="col-md-3">
                        <div class="logo">
                            <a href="index.php">
                                <img src="img/logo.png" alt="Logo">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="search">
                            <input type="text" placeholder="Search">
                            <button><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                   
                    <div class="col-md-3">
                        <div class="user">
                            <a href="wishlist.php" class="btn wishlist">
                                <i class="fa fa-heart"></i>
                                <span>(<?php echo isset($wish->total_quantity) ? $wish->total_quantity : 0; ?>)</span>
                            </a>
                            <a href="cart.php" class="btn cart">
                                <i class="fa fa-shopping-cart"></i>
                                <span>(<?php echo isset($result->total_quantity) ? $result->total_quantity : 0; ?>)</span>
                            </a>
                        </div>
                    </div>
                    
              
                </div>
            </div>
        </div>
        <!-- Bottom Bar End --> 
        
        <!-- Breadcrumb Start -->
        <div class="breadcrumb-wrap">
            <div class="container-fluid">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Product Detail</li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb End -->
        
        <!-- Product Detail Start -->
        <div class="product-detail">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="product-detail-top">
                            <div class="row align-items-center">


                            
                            <?php foreach($data as $user) : ?>
                            <?php foreach($product as $row) : ?> 
                             
                                <div class="col-md-5">
                                    <div class="product-slider-single normal-slider">
                                        <img src="img/<?php echo $row->image ?>" alt="Product Image">
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="product-content">
                                        <div class="title"><h2><?php echo $row->product_name ?></h2></div>
                                        <div class="ratting">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                        </div>
                                        <div class="price">
                                            <h4>Price:</h4>
                                            <p>PhP<?php echo $row->price ?> </p>
                                        </div>

                                        <!-- adding to cart or wishlist -->

                                                    <form method="POST" action="add-to-cart.php" id="cart-form" >
                                                    <!-- Hidden inputs to store product and user information -->
                                                    <input type="hidden" name="fname" value="<?php echo $user->fname ?>" class="form-control">
                                                    <input type="hidden" name="product_id" value="<?php echo $row->product_id ?>" class="form-control">

                                                    <!-- Quantity Section -->
                                                    <div class="quantity">
                                                        <h4>Quantity:</h4>
                                                        <div class="qty">
                                                            <button type="button" class="btn-minus" ><i class="fa fa-minus"></i></button>
                                                            <input type="text" name="quantity" id="quantity" value="1" readonly>
                                                            <button type="button" class="btn-plus" ><i class="fa fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="action">
                                                        <button type="submit" class="btn" name="submit"><i class="fa fa-shopping-cart"></i>Add to Cart</button>
                                                        <button type="submit" class="btn" name="wish"><i class="fa fa-heart"></i>Add to Wishlist</button>              
                                                    </div>
                                                    </form>
                                       
                                    </div>

                                </div>
                            </div>
                        </div>
                        
                        <div class="row product-detail-bottom">
                            <div class="col-lg-12">
                                <ul class="nav nav-pills nav-justified">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="pill" href="#description">Description</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="pill" href="#specification">Specification</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="pill" href="#reviews">Reviews (1)</a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div id="description" class="container tab-pane active">
                                        <h4>Product description</h4>
                                        <p>
                                            <?php echo $row->product_description ?>
                                        </p>
                                    </div>
                                    <div id="specification" class="container tab-pane fade">
                                        <h4>Product specification</h4>
                                        
                                    </div>
                                    <div id="reviews" class="container tab-pane fade">
                                    <?php foreach($review as $reviews) : ?>  
                                        <div class="reviews-submitted">
                                            <div class="reviewer"><?php echo $reviews->fname, $reviews->lname ?>- <span><?php echo $reviews->date_created ?></span></div>
                                            <div class="ratting">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                            </div>
                                            <p>
                                                <?php echo $reviews->review ?>
                                            </p>
                                        </div>
                                    <?php endforeach; ?>
                                        <div class="reviews-submit">
                                            <h4>Give your Review:</h4>
                                            <div class="ratting">
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                            </div>

                                            <form method="POST" action="review.php">
                                            <div class="row form">
                                                <div class="col-sm-6">
                                                    <input type="text" name="fname" placeholder="Name" value="<?php echo $user->fname   ?> " readonly>
                                                    <input type="hidden" name="lname" placeholder="Name" value="<?php echo $user->lname  ?> " readonly>
                                                    <input type="hidden" name="product_id" value="<?php echo $row->product_id ?>" class="form-control">
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="email" name="email" placeholder="Email" value="<?php echo $user->email  ?>" readonly>
                                                </div>
                                                <div class="col-sm-12">
                                                    <textarea placeholder="Review" name="review"></textarea>
                                                </div>
                                                <div class="col-sm-12">
                                                    <button type="submit" name="submit">Submit</button>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>

                                    
                                    <?php endforeach; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>

                      
                    
                    <!-- Side Bar Start -->
                    <div class="col-lg-4 sidebar">
                        <div class="sidebar-widget category">
                        <form method="GET" action="product-list.php">
                            <h2 class="title">Category</h2>
                            <nav class="navbar bg-light">
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="product-list.php?category=Romance"><i class="fa fa-book"></i>Romance</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="product-list.php?category=Action"><i class="fa fa-book"></i>Action Adventure</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="product-list.php?category=History"><i class="fa fa-book"></i>Biographies & History</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="product-list.php?category=Mystery"><i class="fa fa-book"></i>Mystery & Thriller</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="product-list.php?category=Fantasy"><i class="fa fa-book"></i>Fantasy</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="product-list.php?category=Horror"><i class="fa fa-book"></i>Horror</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="product-list.php?category=educational"><i class="fa fa-book"></i>Education</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="product-list.php?category=ScienceFiction"><i class="fa fa-book"></i>Science Fiction</a>
                                    </li>
                                    </form>
                                </ul>
                            </nav>
                        </div>
                        
                     
                
                        
                        
                    </div>
                    <!-- Side Bar End -->
                </div>
            </div>
        </div>
      
<?php include "include/footer.php" ?>
