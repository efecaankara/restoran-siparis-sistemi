<?php
session_start();
include "db.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id <= 0) {
    header("Location: urunler.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM urunler WHERE id = ? AND stok_durumu = 1 AND stok_miktari > 0");
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

$mevcutAdet = isset($_SESSION["cart"][$id]) ? $_SESSION["cart"][$id]["adet"] : 0;

if ($mevcutAdet >= (int)$urun["stok_miktari"]) {
    header("Location: urunler.php");
    exit;
}

if (isset($_SESSION["cart"][$id])) {
    $_SESSION["cart"][$id]["adet"] += 1;
} else {
    $_SESSION["cart"][$id] = [
        "id" => $urun["id"],
        "urun_adi" => $urun["urun_adi"],
        "fiyat" => $urun["fiyat"],
        "gorsel_yolu" => $urun["gorsel_yolu"],
        "adet" => 1
    ];
}

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

if (isset($_SESSION["cart"][$id])) {
    $_SESSION["cart"][$id]["adet"] += 1;
} else {
    $_SESSION["cart"][$id] = [
        "id" => $urun["id"],
        "urun_adi" => $urun["urun_adi"],
        "fiyat" => $urun["fiyat"],
        "gorsel_yolu" => $urun["gorsel_yolu"],
        "adet" => 1
    ];
}

header("Location: urunler.php");
exit;
?>