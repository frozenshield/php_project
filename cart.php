<?php session_start(); ?>
<?php include "include/header.php" ?>
<?php require_once "dbcon.php"; ?>

<?php if(!isset($_SESSION['username']))
        {
            header("Location: index.php");
        }
?>

<?php

        $user_id = $_SESSION['users_id'];

        $query = $conn->prepare("SELECT product.product_id, product.product_name, product.image, product.price, cart.quantity, (product.price * cart.quantity) AS total_price 
                                 FROM cart JOIN product ON cart.product_id = product.product_id
                                 WHERE cart.users_id = :users_id ");
        $query->execute([

            ':users_id' => $user_id
        
        ]);

        $cart_item = $query->fetchAll(PDO::FETCH_OBJ);

        $total_amount = 0;
        $shipping = 199;
        $grandtotal = 0;
        foreach ($cart_item as $item) {
            $total_amount = $total_amount + $item->total_price;

        $grandtotal = $total_amount + $shipping;


        }
?>  

<?php

        $query = $conn->prepare("SELECT SUM(quantity) AS total_quantity FROM cart WHERE users_id = :users_id ");
        $query->execute([

            ':users_id' => $user_id
        ]);

        $result = $query->fetch(PDO::FETCH_OBJ);

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

                    <?php foreach($result as $quantity) : ?>            

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

                <?php endforeach; ?>
            </div>
        </div>
        <!-- Bottom Bar End -->
        
        <!-- Breadcrumb Start -->
        <div class="breadcrumb-wrap">
            <div class="container-fluid">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Cart</li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb End -->
        
        <!-- Cart Start -->
        <div class="cart-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="cart-page-inner">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th>Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody class="align-middle">
                                        <?php foreach($cart_item as $detail) : ?>
                                        <tr> 
                                            <td>
                                                <div class="img">
                                                    <a href="#"><img src="img/<?php echo $detail->image ?>" alt="Image"></a>
                                                    <p><?php echo $detail->product_name ?></p>
                                                </div>
                                            </td>
                                            <td>₱ <?php echo $detail->price ?></td>
                                            <td>
                                                 <?php echo $detail->quantity ?>
                                            </td>
                                            <td>₱ <?php echo $detail->total_price ?></td>
                                            <form method="POST" action="backend.php?del_id=<?php echo $detail->product_id ?>">
                                                <td><button><i class="fa fa-trash"></i></button></td>
                                            </form>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="cart-page-inner">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="coupon">
                                        <input type="text" placeholder="Coupon Code">
                                        <button>Apply Code</button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="cart-summary">
                                        <div class="cart-content">
                                            <h1>Cart Summary</h1>
                                            <p>Sub Total<span>₱<?php echo number_format($total_amount, 2); ?></span></p>
                                            <p>Shipping Cost<span>Free</span></p>
                                            <h2>Grand Total<span>₱<?php echo number_format($total_amount, 2); ?></span></h2>
                                        </div>
                                        <form method="POST" action="checkout.php">
                                        <div class="cart-btn">
                                            <button>Checkout</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Cart End -->
        


<?php include "include/footer.php" ?>