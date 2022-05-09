<?php
$dbhost="localhost";
$dbname="phplogin";
$dbuser="root";
$dbpassword="";
$dsn="mysql:host=$dbhost;dbname=$dbname";
$pdo=new PDO($dsn,$dbuser,$dbpassword);
try{
    $conn=$pdo;
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    // echo "Connected Successfully";
}catch(PDOException $e){
    echo "Connection Failed".$e->getMessage();
}



?>