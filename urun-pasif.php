<?php
include "includes/auth.php";
include "db.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id <= 0) {
    header("Location: urunleri-yonet.php");
    exit;
}

$stmt = $conn->prepare("UPDATE urunler SET stok_durumu = 0, stok_miktari = 0 WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: urunleri-yonet.php");
exit;
?>