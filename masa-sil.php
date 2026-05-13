<?php
include "includes/auth.php";
include "db.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id <= 0) {
    header("Location: masa-yonetimi.php");
    exit;
}

$stmt = $conn->prepare("SELECT masa_adi FROM masalar WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$masa = $result->fetch_assoc();

if ($masa) {
    $masa_adi = $masa["masa_adi"];

    $stmtKontrol = $conn->prepare("
        SELECT COUNT(*) AS aktif_siparis
        FROM siparisler
        WHERE masa_no = ?
        AND durum != 'Teslim Edildi'
        AND durum != 'İptal Edildi'
    ");

    $stmtKontrol->bind_param("s", $masa_adi);
    $stmtKontrol->execute();

    $kontrolResult = $stmtKontrol->get_result();
    $kontrol = $kontrolResult->fetch_assoc();

    if ((int)$kontrol["aktif_siparis"] > 0) {
        header("Location: masa-yonetimi.php?hata=aktif_siparis");
        exit;
    }

    $stmtDelete = $conn->prepare("DELETE FROM masalar WHERE id = ?");
    $stmtDelete->bind_param("i", $id);
    $stmtDelete->execute();
}

header("Location: masa-yonetimi.php");
exit;
?>