<?php
session_start();
include "db.php";

$cart = $_SESSION["cart"] ?? [];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: sepet.php");
    exit;
}

if (empty($cart)) {
    die("Sepet boş.");
}

$musteri_ad_soyad = trim($_POST["musteri_ad_soyad"] ?? "");
$musteri_tel = trim($_POST["musteri_tel"] ?? "");
$adres = trim($_POST["adres"] ?? "");

if ($musteri_ad_soyad === "" || $musteri_tel === "" || $adres === "") {
    die("Lütfen tüm alanları doldurun.");
}

$toplam_tutar = 0;

foreach ($cart as $item) {
    $toplam_tutar += $item["fiyat"] * $item["adet"];
}

$conn->begin_transaction();

try {
    $stmtSiparis = $conn->prepare("
        INSERT INTO siparisler (musteri_ad_soyad, musteri_tel, adres, toplam_tutar)
        VALUES (?, ?, ?, ?)
    ");
    $stmtSiparis->bind_param("sssd", $musteri_ad_soyad, $musteri_tel, $adres, $toplam_tutar);
    $stmtSiparis->execute();

    $siparis_id = $conn->insert_id;

    $stmtDetay = $conn->prepare("
        INSERT INTO siparis_detay (siparis_id, urun_id, adet, birim_fiyat)
        VALUES (?, ?, ?, ?)
    ");

    foreach ($cart as $item) {
    $urun_id = (int)$item["id"];
    $adet = (int)$item["adet"];
    $birim_fiyat = (float)$item["fiyat"];

    $stmtDetay->bind_param("iiid", $siparis_id, $urun_id, $adet, $birim_fiyat);
    $stmtDetay->execute();

    $stmtStok = $conn->prepare("UPDATE urunler SET stok_miktari = stok_miktari - ? WHERE id = ? AND stok_miktari >= ?");
    $stmtStok->bind_param("iii", $adet, $urun_id, $adet);
    $stmtStok->execute();

    if ($stmtStok->affected_rows === 0) {
        throw new Exception("Yetersiz stok bulundu.");
    }

    $stmtKontrol = $conn->prepare("UPDATE urunler SET stok_durumu = 0 WHERE id = ? AND stok_miktari <= 0");
    $stmtKontrol->bind_param("i", $urun_id);
    $stmtKontrol->execute();
}

    $conn->commit();

    unset($_SESSION["cart"]);

    echo "<h2>Sipariş başarıyla oluşturuldu.</h2>";
    echo "<p>Sipariş numarası: #" . $siparis_id . "</p>";
    echo "<p>Toplam tutar: " . number_format($toplam_tutar, 2) . " TL</p>";
    echo '<p><a href="urunler.php">Menüye dön</a></p>';
} catch (Exception $e) {
    $conn->rollback();
    die("Sipariş kaydedilemedi: " . $e->getMessage());
}
?>