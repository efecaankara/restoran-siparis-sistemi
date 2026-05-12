<?php
include "includes/auth.php";
include "db.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id <= 0) {
    header("Location: kategoriler-admin.php");
    exit;
}

$stmt = $conn->prepare("SELECT kategori_adi FROM kategoriler WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$kategori = $result->fetch_assoc();

if ($kategori) {
    $kategori_adi = $kategori["kategori_adi"];

    $stmtUpdate = $conn->prepare("UPDATE urunler SET kategori = '' WHERE kategori = ?");
    $stmtUpdate->bind_param("s", $kategori_adi);
    $stmtUpdate->execute();

    $stmtDelete = $conn->prepare("DELETE FROM kategoriler WHERE id = ?");
    $stmtDelete->bind_param("i", $id);
    $stmtDelete->execute();
}

header("Location: kategoriler-admin.php");
exit;
?>