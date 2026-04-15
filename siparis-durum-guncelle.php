<?php
include "includes/auth.php";
include "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: siparisler-admin.php");
    exit;
}

$siparis_id = (int)($_POST["siparis_id"] ?? 0);
$durum = trim($_POST["durum"] ?? "");

$izinliDurumlar = ["Beklemede", "Hazırlanıyor", "Tamamlandı"];

if ($siparis_id <= 0 || !in_array($durum, $izinliDurumlar, true)) {
    die("Geçersiz veri.");
}

$stmt = $conn->prepare("UPDATE siparisler SET durum = ? WHERE id = ?");
$stmt->bind_param("si", $durum, $siparis_id);

if ($stmt->execute()) {
    header("Location: siparisler-admin.php");
    exit;
} else {
    die("Durum güncellenemedi.");
}
?>