<?php include "include/header.php" ?>
<?php include "dbcon.php" ?>
<?php session_start(); 

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
?>


<?php 


    $rows = $conn->query("SELECT * FROM product WHERE status = 1 ORDER BY RAND()");
    $rows->execute();

    $allrows = $rows->fetchAll(PDO::FETCH_OBJ);

     // to show the reviews
    $user_id = $_SESSION['users_id'];

    $query = $conn->prepare("SELECT users.users_id, users.profile_pic, review.fname, review.lname, review.email, review.review FROM review
    JOIN users ON review.users_id = users.users_id WHERE review.stars = 5 ORDER BY RAND() "); 
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
        
        <!-- Main Slider Start -->
        <div class="header">
            <div class="container-fluid">
                <div class="row">

        
                    <div class="col-md-3">
                        <nav class="navbar bg-light">
                            <ul class="navbar-nav">
                            <form method="GET" action="product-list.php">
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php"><i class="fa fa-home"></i>Home</a>
                                </li>
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
        

                    <div class="col-md-6">
                        <div class="header-slider normal-slider">
                            <div class="header-slider-item">
                                <img src="img/slider-01.jpg" alt="Slider Image" />
                            </div>
                            <div class="header-slider-item">
                                <img src="img/slider-02.jpg" alt="Slider Image" />
                            </div>
                            <div class="header-slider-item">
                                <img src="img/slider-03.jpg" alt="Slider Image" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="header-img">
                            <div class="img-item">
                                <img src="img/category-01.jpg" />
                                <a class="img-text" href="product-list.php?category=History">
                                    <p>Biographies & History</p>
                                </a>
                            </div>
                            <div class="img-item">
                                <img src="img/category-02.jpg" />
                                <a class="img-text" href="product-list.php?category=ScienceFiction">
                                    <p>Science Fiction</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Slider End -->      
        
       
        <!-- Feature Start-->
        <div class="feature">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-6 feature-col">
                        <div class="feature-content">
                            <i class="fab fa-cc-mastercard"></i>
                            <h2>Secure Payment</h2>
                           
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 feature-col">
                        <div class="feature-content">
                            <i class="fa fa-truck"></i>
                            <h2>Worldwide Delivery</h2>
                            
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 feature-col">
                        <div class="feature-content">
                            <i class="fa fa-sync-alt"></i>
                            <h2>90 Days Return</h2>
                           
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 feature-col">
                        <div class="feature-content">
                            <i class="fa fa-comments"></i>
                            <h2>24/7 Support</h2>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Feature End-->      
        
        <!-- Category Start-->
        <div class="category">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="category-item ch-400">
                            <img src="img/category01.png" />
                            <a class="category-name" href="product-list.php?category=Romance">
                                <p>ROMANCE</p>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="category-item ch-250">
                            <img src="img/category02.png" />
                            <a class="category-name" href="product-list.php?category=Action">
                                <p>ACTION & ADVENTURE</p>
                            </a>
                        </div>
                        <div class="category-item ch-150">
                            <img src="img/category03.png" />
                            <a class="category-name" href="product-list.php?category=Mystery">
                                <p>MYSTERY & THRILLER</p>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="category-item ch-150">
                            <img src="img/category04.png" />
                            <a class="category-name" href="product-list.php?category=Fantasy">
                                <p>FANTASY</p>
                            </a>
                        </div>
                        <div class="category-item ch-250">
                            <img src="img/category06.png" />
                            <a class="category-name" href="product-list.php?category=educational">
                                <p>EDUCATION</p>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="category-item ch-400">
                            <img src="img/category05.png" />
                            <a class="category-name" href="product-list.php?category=Horror">
                                <p>HORROR</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Category End-->       
        
        <!-- Call to Action Start -->
        <div class="call-to-action">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1></h1>
                    </div>
                    <div class="col-md-6">
                       
                    </div>
                </div>
            </div>
        </div>
        <!-- Call to Action End -->       
        
        <!-- Featured Product Start -->
        <div class="featured-product product">
            <div class="container-fluid">
                <div class="section-header">
                    <h1>Featured Product</h1>
                </div>
                <div class="row align-items-center product-slider product-slider-4">
                    
                    
                  
               
                    <?php foreach($allrows as $product) : ?>
                        
                    <div class="col-lg-3">
                        <div class="product-item">
                            <div class="product-title">
                                <a href="product-detail.php?product_id=<?php echo $product->product_id; ?>"><?php echo $product->product_name; ?></a>
                                <div class="ratting">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                            </div>
                            <div class="product-image">
                                <a href="product-detail.php?product_id=<?php echo $product->product_id; ?>">
                                    <img src="img/<?php echo $product->image; ?>" alt="Product Image">
                                </a>
                                
                            </div>
                            <form method="POST" id="form-data" action="add-to-cart.php">
                            <div class="product-price">
                                <h3><span>PhP</span><?php echo $product->price; ?></h3>
                                <input type="hidden" name="quantity" value="1" class="form-control">
                                <input type="hidden" name="fname" value="<?php echo $_SESSION['fnamee'] ?>" class="form-control">
                                <input type="hidden" name="product_id" value="<?php echo $product->product_id ?>" class="form-control">
                                <button class="btn" name="submit"><i class="fa fa-shopping-cart"></i>Add to cart</button>
                            </div>
                            </form>
                        </div>
                    </div>
                    
              
                    <?php endforeach; ?>
                    
                    
                   
                </div>
            </div>
        </div>
        <!-- Featured Product End -->       
        
        <!-- Newsletter Start -->
        <div class="newsletter">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <h1>Subscribe Our Newsletter</h1>
                    </div>
                    <div class="col-md-6">
                        <div class="form">
                            <input type="email" value="Your email here">
                            <button>Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Newsletter End -->        
        
        <!-- Recent Product Start -->
        <div class="recent-product product">
            <div class="container-fluid">
                <div class="section-header">
                    <h1>Recent Product</h1>
                </div>
                <div class="row align-items-center product-slider product-slider-4">
                    
                    <?php foreach($allrows as $product) : ?>
              
                    
                    <div class="col-lg-3">
                        <div class="product-item">
                            <div class="product-title">
                                <a href="product-detail.php?product_id=<?php echo $product->product_id; ?>"><?php echo $product->product_name; ?></a>
                                <div class="ratting">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                            </div>
                            <div class="product-image">
                                <a href="product-detail.php?product_id=<?php echo $product->product_id; ?>">
                                    <img src="img/<?php echo $product->image; ?>" alt="Product Image">
                                </a>
                                
                            </div>
                            <form method="POST" action="add-to-cart.php">
                            <div class="product-price">
                                <h3><span>PhP</span><?php echo $product->price; ?></h3>
                                <input type="hidden" name="quantity" value="1" class="form-control">
                                <input type="hidden" name="fname" value="<?php echo $user->fname ?>" class="form-control">
                                <input type="hidden" name="product_id" value="<?php echo $product->product_id ?>" class="form-control">
                                <button class="btn" name="submit"><i class="fa fa-shopping-cart"></i>Add to cart</button>
                            </div>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
             
                </div>
            </div>
        </div>
        <!-- Recent Product End -->
        
        <!-- Review Start -->
        <div class="review">
            <div class="container-fluid">
                <div class="row align-items-center review-slider normal-slider">
                    <?php foreach($review as $reviews) : ?>
                    <div class="col-md-6">
                        <div class="review-slider-item">
                            <div class="review-img">
                                <img src="img/<?php echo $reviews->profile_pic ?>" alt="Image">
                            </div>
                            <div class="review-text">
                                <h2><?php echo $reviews->fname . " " . $reviews->lname ?></h2>
                                <h3><?php echo $reviews->email ?></h3>
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
                        </div>
                    </div>
                    
                    
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!-- Review End -->        
        
        
   
        
<?php include "include/footer.php" ?>