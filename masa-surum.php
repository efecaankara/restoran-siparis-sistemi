<?php
include "includes/auth.php";
include "db.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id <= 0) {
    header("Location: masa-yonetimi.php");
    exit;
}

$stmt = $conn->prepare("SELECT durum FROM masalar WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$masa = $result->fetch_assoc();

if ($masa) {
    $yeniDurum = $masa["durum"] === "Dolu" ? "Boş" : "Dolu";

    $stmtUpdate = $conn->prepare("UPDATE masalar SET durum = ? WHERE id = ?");
    $stmtUpdate->bind_param("si", $yeniDurum, $id);
    $stmtUpdate->execute();
}

header("Location: masa-yonetimi.php");
exit;
?>