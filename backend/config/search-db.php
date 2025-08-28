<?php

$sName = "localhost";
$uName = "publishers";
$pass = '9$Tv[!RQRvRX';
$db_name = "hancock_publishers";

try {
    $conn = new PDO(
        "mysql:host=$sName;dbname=$db_name",
        $uName,
        $pass
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed : " . $e->getMessage();
}