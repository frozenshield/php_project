<?php session_start(); ?>
<?php include "dbcon.php" ?>
<?php include "include/header.php" ?>

<?php if(!isset($_SESSION['username']))
        {
            header("Location: index.php");
        }
?>

<?php
        $name = $conn->prepare("SELECT * FROM users WHERE username = :username ");
        $name->execute([
            ':username' => $_SESSION['username']
        ]);

        $data = $name->fetchAll(PDO::FETCH_OBJ);

?>


<?php
    // Get the category from the URL
    $category = isset($_GET['category']) ? $_GET['category'] : null;

    if($category)
        {
            $query = $conn->prepare("SELECT * FROM product WHERE category = :category");
            $query->execute([

              ':category' => $category      

            ]);

            $products = $query->fetchAll(PDO::FETCH_OBJ);
        }
    else
        {
            echo "No category specified";
        }


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
                    <a href="#" class="navbar-brand">MENU</a>
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
                            <a href="index.html">
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
                    <li class="breadcrumb-item active">Product List</li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb End -->
        
        <!-- Product List Start -->
   
        
        <div class="product-view">
            <div class="container-fluid">
                <div class="row">
                    
                    <div class="col-lg-8">
                        <div class="row">
                        
                            <div class="col-md-12">
                                <div class="product-view-top">
                                    <div class="row">
                                    
                                        <div class="col-md-4">
                                            <div class="product-search">
                                                <input type="email" value="Search">
                                                <button><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="product-short">
                                                <div class="dropdown">
                                                    <div class="dropdown-toggle" data-toggle="dropdown">Product short by</div>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="#" class="dropdown-item">Newest</a>
                                                        <a href="#" class="dropdown-item">Popular</a>
                                                        <a href="#" class="dropdown-item">Most sale</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      
                                        <div class="col-md-4">
                                            <div class="product-price-range">
                                                <div class="dropdown">
                                                    <div class="dropdown-toggle" data-toggle="dropdown">Product price range</div>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="#" class="dropdown-item">$0 to $50</a>
                                                        <a href="#" class="dropdown-item">$51 to $100</a>
                                                        <a href="#" class="dropdown-item">$101 to $150</a>
                                                        <a href="#" class="dropdown-item">$151 to $200</a>
                                                        <a href="#" class="dropdown-item">$201 to $250</a>
                                                        <a href="#" class="dropdown-item">$251 to $300</a>
                                                        <a href="#" class="dropdown-item">$301 to $350</a>
                                                        <a href="#" class="dropdown-item">$351 to $400</a>
                                                        <a href="#" class="dropdown-item">$401 to $450</a>
                                                        <a href="#" class="dropdown-item">$451 to $500</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php foreach($products as $product) : ?>
                            <?php foreach($data as $user) : ?>

                            <div class="col-md-4">
                                <div class="product-item">
                                    <div class="product-title">
                                        
                                        <a href="product-detail.php?product_id=<?php echo $product->product_id ?>"><?php echo $product->product_name; ?></a>
                                        <div class="ratting">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                        </div>
                                    </div>
                                    <div class="product-image">
                                        <a href="product-detail.php?product_id=<?php echo $product->product_id ?>">
                                            <img src="img/<?php echo $product->image ?>" alt="Product Image">
                                        </a>
                                       
                                    </div>
                                    <form method="POST" id="form-data" action="add-to-cart.php">
                                    <div class="product-price">
                                        <h3><span>PhP</span><?php echo $product->price; ?></h3>
                                        <input type="hidden" name="fname" value="<?php echo $user->fname ?>" class="form-control">
                                        <input type="hidden" name="product_id" value="<?php echo $product->product_id ?>" class="form-control">
                                        <input type="hidden" name="quantity" value="1" class="form-control">
                                        <button class="btn" name="submit"><i class="fa fa-shopping-cart"></i>Add to cart</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endforeach; ?>
                            
                           
                    
                         
                            
                    
                       
        
                           
                        </div>
              
                        <!-- Pagination Start -->
                        <div class="col-md-12">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <!-- Pagination Start -->
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
                        
                        <div class="sidebar-widget widget-slider">
                            <div class="sidebar-slider normal-slider">
                            <?php foreach($products as $product) : ?>
                                <div class="product-item">
                                    <div class="product-title">
                                        <a href="#"><?php echo $product->product_name ?></a>
                                        <div class="ratting">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                        </div>
                                    </div>
                                    <div class="product-image">
                                        <a href="product-detail.html">
                                            <img src="img/<?php echo $product->image ?>" alt="Product Image">
                                        </a>
                                       
                                    </div>
                                    <div class="product-price">
                                        <h3><span>PhP</span>100</h3>
                                        <a class="btn" href=""><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            </div>
                        </div>
                     
                    
                        
                       
                    </div>
                    <!-- Side Bar End -->
                </div>
            </div>
        </div>
        <!-- Product List End -->  
        
<?php include "include/footer.php" ?>