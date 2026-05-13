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

    $stmtKontrol = $conn->prepare("SELECT COUNT(*) AS urun_sayisi FROM urunler WHERE kategori = ?");
    $stmtKontrol->bind_param("s", $kategori_adi);
    $stmtKontrol->execute();
    $kontrolResult = $stmtKontrol->get_result();
    $kontrol = $kontrolResult->fetch_assoc();

    if ((int)$kontrol["urun_sayisi"] > 0) {
        header("Location: kategoriler-admin.php?hata=urun_var");
        exit;
    }

    $stmtDelete = $conn->prepare("DELETE FROM kategoriler WHERE id = ?");
    $stmtDelete->bind_param("i", $id);
    $stmtDelete->execute();
}

header("Location: kategoriler-admin.php");
exit;
?>