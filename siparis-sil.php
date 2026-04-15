<?php
include "includes/auth.php";
include "db.php";

$siparis_id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($siparis_id <= 0) {
    header("Location: siparisler-admin.php");
    exit;
}

// Önce sipariş durumunu kontrol et
$stmt = $conn->prepare("SELECT durum FROM siparisler WHERE id = ?");
$stmt->bind_param("i", $siparis_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: siparisler-admin.php");
    exit;
}

$siparis = $result->fetch_assoc();

// Sadece tamamlanan siparişler silinsin
if ($siparis["durum"] !== "Tamamlandı") {
    die("Sadece tamamlanan siparişler silinebilir.");
}

// Siparişi sil
$stmtDelete = $conn->prepare("DELETE FROM siparisler WHERE id = ?");
$stmtDelete->bind_param("i", $siparis_id);
$stmtDelete->execute();

header("Location: siparisler-admin.php");
exit;
?>