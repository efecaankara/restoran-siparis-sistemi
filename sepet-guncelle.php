<?php
session_start();
include "db.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$islem = $_GET["islem"] ?? "";

if ($id <= 0 || !isset($_SESSION["cart"][$id])) {
    header("Location: sepet.php");
    exit;
}

if ($islem === "artir") {
    $stmt = $conn->prepare("SELECT stok_miktari FROM urunler WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $urun = $result->fetch_assoc();
        $stok_miktari = (int)$urun["stok_miktari"];

        if ($_SESSION["cart"][$id]["adet"] < $stok_miktari) {
            $_SESSION["cart"][$id]["adet"] += 1;
        }
    }
} elseif ($islem === "azalt") {
    $_SESSION["cart"][$id]["adet"] -= 1;

    if ($_SESSION["cart"][$id]["adet"] <= 0) {
        unset($_SESSION["cart"][$id]);
    }
}

header("Location: sepet.php");
exit;
?>