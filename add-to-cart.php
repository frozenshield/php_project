<?php require_once "dbcon.php"; ?>
<?php session_start(); ?>

<?php 

if(!isset($_SESSION['username']))
    {
        header('Location: login.php');
        exit();
    }


// get the product id from the URL

//FOR ADD TO CART
if(isset($_POST['submit']))
{
            $product_id = $_POST['product_id'];
            $fname = $_POST['fname'];
            $user_id = $_SESSION['users_id'];
            $quants = $_POST['quantity'];



            $check = $conn->prepare("SELECT * FROM cart WHERE users_id = :users_id AND product_id = :product_id AND fname = :fname");
            $check->execute([

            ':users_id' => $user_id,
            ':product_id' => $product_id,
            ':fname' => $fname

            ]);

            $cartitem = $check->fetch();



            if($cartitem)
                {
                    // If the product is already in the cart, update the quantity
                    $check = $conn->prepare("UPDATE cart SET quantity = quantity + '$quants' WHERE users_id = :users_id AND product_id = :product_id  AND fname = :fname ");
                    $check->execute([

                        ':users_id' => $user_id,
                        ':product_id' => $product_id,
                        ':fname' => $fname

                    ]);
                }
            else
                {
                    // If the product is not in the cart, insert a new entry
                    $additem = $conn->prepare("INSERT INTO cart (users_id, product_id, quantity, fname) VALUES (:users_id, :product_id, :quantity, :fname) ");
                    $additem->execute([

                        ':users_id' => $user_id,
                        ':product_id' => $product_id,
                        ':fname' => $fname,
                        ':quantity' => $quants
                    

                    ]);
                }

                // Redirect to the cart page
                header("Location: cart.php");
                exit();
}



//FOR WISHLIST
if(isset($_POST['wish']))
{
            $product_id = $_POST['product_id'];
            $fname = $_POST['fname'];
            $user_id = $_SESSION['users_id'];
            $quants = $_POST['quantity'];



            $check = $conn->prepare("SELECT * FROM wishlist WHERE users_id = :users_id AND product_id = :product_id AND fname = :fname");
            $check->execute([

            ':users_id' => $user_id,
            ':product_id' => $product_id,
            ':fname' => $fname

            ]);

            $cartitem = $check->fetch();



            if($cartitem)
                {
                    // If the product is already in the cart, update the quantity
                    $check = $conn->prepare("UPDATE wishlist SET quantity = quantity + '$quants' WHERE users_id = :users_id AND product_id = :product_id  AND fname = :fname ");
                    $check->execute([

                        ':users_id' => $user_id,
                        ':product_id' => $product_id,
                        ':fname' => $fname

                    ]);
                }
            else
                {
                    // If the product is not in the cart, insert a new entry
                    $additem = $conn->prepare("INSERT INTO wishlist (users_id, product_id, quantity, fname) VALUES (:users_id, :product_id, :quantity, :fname) ");
                    $additem->execute([

                        ':users_id' => $user_id,
                        ':product_id' => $product_id,
                        ':fname' => $fname,
                        ':quantity' => $quants
                    

                    ]);
                }

                // Redirect to the cart page
                header("Location: wishlist.php");
                exit();
}





//FOR ADD TO CART FROM WISHLIST
if(isset($_POST['wishtocart']))
{
            $product_id = $_POST['product_id'];
            $fname = $_POST['fname'];
            $user_id = $_SESSION['users_id'];
            $quants = $_POST['quantity'];



            $check = $conn->prepare("SELECT * FROM cart WHERE users_id = :users_id AND product_id = :product_id AND fname = :fname");
            $check->execute([

            ':users_id' => $user_id,
            ':product_id' => $product_id,
            ':fname' => $fname

            ]);

            $cartitem = $check->fetch();



            if($cartitem)
                {
                    // If the product is already in the cart, update the quantity
                    $check = $conn->prepare("UPDATE cart SET quantity = quantity + '$quants' WHERE users_id = :users_id AND product_id = :product_id  AND fname = :fname ");
                    $check->execute([

                        ':users_id' => $user_id,
                        ':product_id' => $product_id,
                        ':fname' => $fname

                    ]);

                    $del = $conn->prepare("DELETE FROM wishlist WHERE users_id = :users_id AND product_id = :product_id  AND fname = :fname ");
                    $del->execute([

                        ':users_id' => $user_id,
                        ':product_id' => $product_id,
                        ':fname' => $fname

                    ]);
                
                }
            else
                {
                    // If the product is not in the cart, insert a new entry
                    $additem = $conn->prepare("INSERT INTO cart (users_id, product_id, quantity, fname) VALUES (:users_id, :product_id, :quantity, :fname) ");
                    $additem->execute([

                        ':users_id' => $user_id,
                        ':product_id' => $product_id,
                        ':fname' => $fname,
                        ':quantity' => $quants
                    

                    ]);

                    $del = $conn->prepare("DELETE FROM wishlist WHERE users_id = :users_id AND product_id = :product_id  AND fname = :fname ");
                    $del->execute([

                        ':users_id' => $user_id,
                        ':product_id' => $product_id,
                        ':fname' => $fname

                    ]);


                }

                // Redirect to the cart page
                header("Location: cart.php");
                exit();
}

?>