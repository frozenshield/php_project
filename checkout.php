<?php require_once "dbcon.php"; ?>
<?php session_start(); ?>
<?php include "include/header.php" ?>
<?php 

if(!isset($_SESSION['username']))
    {
        header('Location: index.php');
        exit();
    }

?>

<?php
        //show products from a user who logged in
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

    // Check if the user already has billing details
        $query = $conn->prepare("SELECT COUNT(*) FROM billing WHERE users_id = :user_ids");
        $query->execute([':user_ids' => $user_id]);
        $billingExists = $query->fetchColumn() > 0;



    // to show the cart value
        if (isset($_SESSION['users_id'])) {
        $user_id = $_SESSION['users_id'];
        
        $query = $conn->prepare("SELECT SUM(quantity) AS total_quantity FROM cart WHERE users_id = :users_id ");
        $query->execute([

            ':users_id' => $user_id
        ]);

        $result = $query->fetchAll(PDO::FETCH_OBJ);

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
                <?php foreach($result as $quantity) : ?>
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
                                <span>(<?php echo $quantity->total_quantity; ?>)</span>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>  
                </div>
            </div>
        </div>
        <!-- Bottom Bar End --> 
        
        <!-- Breadcrumb Start -->
        <div class="breadcrumb-wrap">
            <div class="container-fluid">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb End -->
        
        <!-- Checkout Start -->
        <div class="checkout">
            <div class="container-fluid"> 
                <div class="row">
                    <div class="col-lg-8">
                        <div class="checkout-inner">
                            <div class="billing-address">
                                <h2>Billing Address</h2>
                                <form method="POST" action="add-to-billing.php" id="billingForm">
                                    <div class="row">
                                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" required>
                                        <div class="col-md-6">
                                            <label>First Name</label>
                                            <input class="form-control" name="fname" placeholder="First Name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Last Name</label>
                                            <input class="form-control" name="lname" placeholder="Last Name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label>E-mail</label>
                                            <input class="form-control" name="email" placeholder="E-mail" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Mobile No</label>
                                            <input class="form-control" name="phone" placeholder="Mobile No" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Address</label>
                                            <input class="form-control" name="address" placeholder="Address" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label>ZIP Code</label>
                                            <input class="form-control" name="zip" placeholder="ZIP Code" required>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="checkout-btn">
                                                <!-- Conditionally render the Save button -->
                                                <?php if (!$billingExists): ?>
                                                    <button id="saveButton" name="addbilling" type="submit">Save</button>
                                                <?php else: ?>
                                                    <p class="text-success">Billing details are already added.</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="checkout-inner">
                            <div class="checkout-summary">
                                <h1>Cart Total</h1>
                                <?php foreach($cart_item as $p) : ?>
                                <p><?php echo $p->product_name ?><span>PhP <?php echo number_format($p->price, 2) ?></span></p>
                                <?php endforeach; ?>
                                <p class="sub-total">Sub Total<span>PhP <?php echo number_format($total_amount, 2) ?></span></p>
                                <p class="ship-cost">Shipping Cost<span>PhP 0.00</span></p>
                                <h2>Grand Total<span>PhP <?php echo number_format($total_amount, 2) ?></span></h2>

                            </div>

                            <div class="checkout-payment">
                                <div class="payment-methods">
                                    <h1>Payment Methods</h1>
                                    <div class="payment-method">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="payment-1" name="payment" required>
                                            <label class="custom-control-label" for="payment-1">Paypal</label>
                                        </div>
                                        <div class="payment-content" id="payment-1-show">
                                            <p>
                                                
                                            </p>
                                        </div>
                                    </div>
                                    
                              
                                </div>
                                <div class="checkout-btn">
                                     <button id="placeOrderBtn">Place Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Checkout End -->

        <script>
            document.getElementById('placeOrderBtn').addEventListener('click', function(event) {
                // Check if the PayPal option is selected
                const paypalOption = document.getElementById('payment-1');
                if (paypalOption.checked) {
                    // Navigate to PayPal if selected
                    window.location.href = 'paypal.php?user_id=<?php echo $_SESSION['username']; ?>';
                } else {
                    // Show a warning if not selected
                    alert('Please select the PayPal payment method before placing your order.');
                }
            });
        </script>                            

        <script>
            // Add an event listener to hide the Save button after form submission
            const form = document.getElementById('billingForm');
            const saveButton = document.getElementById('saveButton');

            form.addEventListener('submit', function() 
            {
                saveButton.style.display = 'none'; // Hide the button
            });
        </script>
      
        
       <?php include "include/footer.php" ?>
