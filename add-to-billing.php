<?php require_once "dbcon.php"; ?>


<?php 

if(isset($_POST['addbilling']))
    {
       $user_id = $_POST['user_id'];
       $fname = $_POST['fname'];
       $lname = $_POST['lname'];
       $email = $_POST['email'];
       $phone = $_POST['phone'];
       $address = $_POST['address'];
       $zip = $_POST['zip'];

       $insert = $conn->prepare("INSERT INTO billing (users_id,fname,lname,email,phone,address,zip) VALUES (:users_id,:fname,:lname,:email,:phone,:address,:zip)");
       $insert->execute([
        ':users_id' => $user_id,
        ':fname' => $fname,
        ':lname' => $lname,
        ':email' => $email,
        ':phone' => $phone,
        ':address' => $address,
        ':zip' => $zip
       ]);

       header("Location: checkout.php");
    }

?>