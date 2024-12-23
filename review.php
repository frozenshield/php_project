<?php include "dbcon.php" ?>

<?php session_start();

if(!isset($_SESSION['username']))
    {
        header("Location: index.php");
    }


$users_id = $_SESSION['users_id']; 

if(isset($_POST['submit']))   
{
    $product_id = $_POST['product_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $review = $_POST['review'];

    $insert = $conn->prepare("INSERT INTO review (product_id, users_id, fname, lname, email, review) VALUES (:product_id, :users_id, :fname, :lname, :email, :review)");
    $insert->execute([

        ':users_id' => $users_id,
        'product_id' => $product_id,
        ':fname' => $fname,
        ':lname' => $lname,
        ':email' => $email,
        ':review' => $review

    ]);

    header("Location: product-detail.php?product_id=" . $product_id);

} 


?>