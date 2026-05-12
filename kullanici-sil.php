<?php
include "includes/auth.php";
include "db.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id <= 0) {
    header("Location: kullanicilar-admin.php");
    exit;
}

$stmt = $conn->prepare("SELECT durum FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {

    $yeniDurum = $user["durum"] ? 0 : 1;

    $stmtUpdate = $conn->prepare("
        UPDATE users 
        SET durum = ?
        WHERE id = ?
    ");

    $stmtUpdate->bind_param("ii", $yeniDurum, $id);
    $stmtUpdate->execute();
}

header("Location: kullanicilar-admin.php");
exit;
?>