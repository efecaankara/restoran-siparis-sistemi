<?php
session_start();
include "db.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id <= 0) {
    header("Location: urunler.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM urunler WHERE id = ? AND stok_miktari > 0");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: urunler.php");
    exit;
}

$urun = $result->fetch_assoc();

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

$mevcutAdet = isset($_SESSION["cart"][$id]) ? (int)$_SESSION["cart"][$id]["adet"] : 0;
$stokMiktari = (int)$urun["stok_miktari"];

if ($mevcutAdet >= $stokMiktari) {
    header("Location: urunler.php");
    exit;
}

if (isset($_SESSION["cart"][$id])) {
    $_SESSION["cart"][$id]["adet"] += 1;
} else {
    $_SESSION["cart"][$id] = [
        "id" => (int)$urun["id"],
        "urun_adi" => $urun["urun_adi"],
        "fiyat" => (float)$urun["fiyat"],
        "gorsel_yolu" => $urun["gorsel_yolu"],
        "adet" => 1
    ];
}

header("Location: urunler.php");
exit;
?>