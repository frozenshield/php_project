

<!-- db connection -->

<?php

$host = "localhost";
$dbname = "rlg";
$username = "root";
$pass = "";


try
{
$conn = new PDO("mysql:host=$host;dbname=$dbname",$username,$pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}

catch(PDOException $e)
    {
        echo "Connection Failed: " . $e->getMessage();
    }
?>




