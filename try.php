<!-- connection -->

<?php

$host = "localhost";
$dbname = "trylang";
$username = "root";
$pass = "";

$conn = new PDO("mysql:host=$host;dbname=$dbname",$username,$pass);

if($conn == true)
    {
        echo " ";
    }
else
    {
        echo "connection failed";
    }

?>

<!-- add -->
<?php

if(isset($_POST['register']))
    {
        $username = $_POST['username'];
        $pass = $_POSTp['pass'];
        if(empty($username) || empty($pass))
        {
            echo "<script>alert('Some input are empty');
            location.windows.href='index.php';
            </scipt>";
        }
        else
        {
        $insert = $conn->prepare("INSERT INTO users (username,mypass) VALUES (:username,:mypass)");
        $insert->execute([

            ':username' => $username,
            ':mypass' => password_hash($pass,PASSWORD_DEFAULT)

        ]);
    }


?>


<!-- delete -->

<?php

if(isset($_GET['delete_id']))
    {
        $id= $_GET['delete_id']
        
        $delete = $conn->prepare("DELETE * FROM users WHERE user_id = :user_id ");
        $delete->execute([

            ':user_id' => $id
        ]);
    }

?>


<!-- update -->


<?php

if(isset($_GET['upd_id']))
    {
        $id = $_GET['upd_id']

    }

if(isset($_POST['update']))
    {
        $username = $_POST['username'];
        $email = $_POST['email'];

        $update = $conn->prepare("UPDATE users SET username = :username, email = :email WHERE id = '$id' ");
        $update->execute([

            ':username' => $username,
            ':email' => $email

        ]);
    }

?>





