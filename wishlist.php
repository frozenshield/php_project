<?php session_start(); ?>
<?php include "include/header.php" ?>
<?php include "dbcon.php" ?>

<?php if(!isset($_SESSION['username']))
        {
            header("Location: index.php");
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

<?php
     $user_id = $_SESSION['users_id'];
    $query = $conn->prepare("SELECT product.product_id, product.product_name, product.image, wishlist.quantity, (product.price * wishlist.quantity) AS total_price
                             FROM wishlist JOIN product ON wishlist.product_id = product.product_id WHERE wishlist.users_id = :users_id " );
             $query->execute([

                ':users_id' => $user_id              
             ]);
    $data = $query->fetchAll(PDO::FETCH_OBJ);

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
                    <li class="breadcrumb-item active">Wishlist</li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb End -->
        
        <!-- Wishlist Start -->
        <div class="wishlist-page">
            <div class="container-fluid">
                <div class="wishlist-page-inner">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Add to Cart</th>
                                            <th>Remove</th>
                                        </tr>
                                      
                                    </thead>
                               
                                    <tbody class="align-middle">
                                         <?php foreach($data as $wishlist) : ?>
                                        <tr>
                                            <td>
                                                <div class="img">
                                                    <a href="#"><img src="img/<?php echo $wishlist->image ?>" alt="Image"></a>
                                                    <p><?php echo $wishlist->product_name ?></p>
                                                </div>
                                            </td>
                                            <td><?php echo $wishlist->total_price ?></td>
                                            <td><?php echo $wishlist->quantity ?></td>
                                            <form method="POST" id="form-data" action="add-to-cart.php">
                                            <input type="hidden" name="quantity" value="<?php echo $wishlist->quantity ?>" class="form-control">
                                            <input type="hidden" name="fname" value="<?php echo $_SESSION['fnamee'] ?>" class="form-control">
                                            <input type="hidden" name="product_id" value="<?php echo $wishlist->product_id ?>" class="form-control">   
                                            <td><button name="wishtocart" class="btn-cart">Add to Cart</button></td>
                                            </form>
                                       
                                        <form method="POST" action="backend.php?delete_id=<?php echo $wishlist->product_id ?>">
                                            <td><button><i class="fa fa-trash"></i></button></td>
                                        </form>
                                         
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Wishlist End -->
        
        <!-- Footer Start -->
        <div class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h2>Get in Touch</h2>
                            <div class="contact-info">
                                <p><i class="fa fa-map-marker"></i>123 E Store, Los Angeles, USA</p>
                                <p><i class="fa fa-envelope"></i>email@example.com</p>
                                <p><i class="fa fa-phone"></i>+123-456-7890</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h2>Follow Us</h2>
                            <div class="contact-info">
                                <div class="social">
                                    <a href=""><i class="fab fa-twitter"></i></a>
                                    <a href=""><i class="fab fa-facebook-f"></i></a>
                                    <a href=""><i class="fab fa-linkedin-in"></i></a>
                                    <a href=""><i class="fab fa-instagram"></i></a>
                                    <a href=""><i class="fab fa-youtube"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h2>Company Info</h2>
                            <ul>
                                <li><a href="#">About Us</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="#">Terms & Condition</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h2>Purchase Info</h2>
                            <ul>
                                <li><a href="#">Pyament Policy</a></li>
                                <li><a href="#">Shipping Policy</a></li>
                                <li><a href="#">Return Policy</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="row payment align-items-center">
                    <div class="col-md-6">
                        <div class="payment-method">
                            <h2>We Accept:</h2>
                            <img src="img/payment-method.png" alt="Payment Method" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="payment-security">
                            <h2>Secured By:</h2>
                            <img src="img/godaddy.svg" alt="Payment Security" />
                            <img src="img/norton.svg" alt="Payment Security" />
                            <img src="img/ssl.svg" alt="Payment Security" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->
        
        <!-- Footer Bottom Start -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 copyright">
                        <p>Copyright &copy; <a href="#">Your Site Name</a>. All Rights Reserved</p>
                    </div>

                    <div class="col-md-6 template-by">
						<!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->					
                        <p>Designed By <a href="https://htmlcodex.com">HTML Codex</a></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Bottom End -->      
        
        <!-- Back to Top -->
        <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
        
        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/slick/slick.min.js"></script>
        
        <!-- Template Javascript -->
        <script src="js/main.js"></script>
    </body>
</html>
