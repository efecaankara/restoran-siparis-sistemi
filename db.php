<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "restoran_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Veritabanı bağlantı hatası: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>