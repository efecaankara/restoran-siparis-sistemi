<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION["user_id"];
$siparis_id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($siparis_id <= 0) {
    header("Location: profil.php");
    exit;
}

$stmt = $conn->prepare("
    UPDATE siparisler
    SET durum = 'İptal Edildi'
    WHERE id = ?
    AND user_id = ?
    AND durum IN ('Beklemede', 'Hazırlanıyor')
");

$stmt->bind_param("ii", $siparis_id, $user_id);
$stmt->execute();

header("Location: profil.php");
exit;
?>