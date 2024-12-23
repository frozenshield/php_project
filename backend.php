<?php require_once "dbcon.php"; ?>
<?php session_start(); ?>

<!-- Add users -->


<?php 

if(isset($_POST['register']))
    {
                print_r($_POST);
            
                $fname = $_POST['fname'];
                $lname = $_POST['lname'];
                $email = $_POST['email'];
                $contact = $_POST['contact'];
                $username = $_POST['username'];
                $pass = $_POST['pass'];
                $repass = $_POST['repass'];

                if(empty($fname) || empty($lname) || empty($email) ||
                empty($contact) || empty($username) || empty($pass) ||  empty($repass))
                {
                    echo "<script>alert('some input are empty');
                     window.location.href='login.php';
                     </script>";
                }
                else if($pass != $repass )
                {
                    echo "<script>alert('Password not matched');
                     window.location.href='login.php';
                     </script>";
                }
                else
                {
                    $insert = $conn->prepare("INSERT INTO users (fname,lname,email,phone,username,mypass) VALUES (:fname,:lname,:email,:phone,:username,:mypass)");
                    $insert->execute(
                    [
                        ':fname' => $fname,
                        ':lname' => $lname,
                        ':email' => $email,
                        ':phone' => $contact,
                        ':username' => $username,
                        ':mypass' => password_hash($pass, PASSWORD_DEFAULT)
                    ]);

                    echo "<script>alert('Registered!');
                    window.location.href='index.php';
                    </script>";

                }
            
       

    }



?>


<!-- login -->

<?php 

if(isset($_POST['login']))
    {
        $username = $_POST['username'];
        $pass = $_POST['password'];

        if(empty($username) || empty($pass))
        {
            echo "<script>('some input are empty');
            window.location.href='login.php';
            </script>";
        }
        else
        {
            $login = $conn->query("SELECT * FROM users WHERE username = '$username' ");
            $login->execute();
            $data = $login->fetch(PDO::FETCH_ASSOC);

            if($login->rowCount() > 0)
            {
                $_SESSION['username'] = $data['username'];
                $_SESSION['users_id'] = $data['users_id'];
                $_SESSION['fnamee'] = $data['fname'];

                if(password_verify($pass, $data['mypass']))
                {
                    echo "<script>alert('Login Successfully');
                    window.location.href='index.php';
                    </script>";
                }
                else
                {
                    echo "<script>alert('Invalid Password');
                    window.location.href='login.php';
                    </script>";
                }
            }
            else
            {
                echo "<script>alert('Invalid Username/password');
                window.location.href='login.php';
                </script>";
            }
        }
    }

?>

    <!-- delete from cart -->

<?php
    $userid = $_SESSION['users_id'];
    if(isset($_GET['del_id']))
    {
        $productid = $_GET['del_id'];

        $delete = $conn->prepare("DELETE FROM cart WHERE product_id = :product_id AND users_id = :users_id");
        $delete->execute([

            ':product_id' => $productid,
            ':users_id' => $userid
            
        ]);

        header("Location: cart.php");
    }

  ?>

   <!-- delete from wishlist -->

<?php
    $userid = $_SESSION['users_id'];
    if(isset($_GET['delete_id']))
    {
        $productid = $_GET['delete_id'];

        $delete = $conn->prepare("DELETE FROM wishlist WHERE product_id = :product_id AND users_id = :users_id");
        $delete->execute([

            ':product_id' => $productid,
            ':users_id' => $userid
            
        ]);

        header("Location: wishlist.php");
    }

  ?>
  
 

  

