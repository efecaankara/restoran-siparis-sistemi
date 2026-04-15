<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: siparis-olustur.php");
    exit;
}

$musteri_ad_soyad = trim($_POST["musteri_ad_soyad"] ?? "");
$musteri_tel = trim($_POST["musteri_tel"] ?? "");
$adres = trim($_POST["adres"] ?? "");
$urun_id_list = $_POST["urun_id"] ?? [];
$adet_list = $_POST["adet"] ?? [];

if ($musteri_ad_soyad === "" || $musteri_tel === "" || $adres === "") {
    die("Müşteri bilgileri eksik.");
}

if (!is_array($urun_id_list) || !is_array($adet_list) || count($urun_id_list) !== count($adet_list)) {
    die("Ürün verileri hatalı.");
}

$secilenUrunler = [];
$toplam_tutar = 0;

for ($i = 0; $i < count($urun_id_list); $i++) {
    $urun_id = (int)$urun_id_list[$i];
    $adet = (int)$adet_list[$i];

    if ($adet > 0) {
        $stmt = $conn->prepare("SELECT id, urun_adi, fiyat FROM urunler WHERE id = ? AND stok_durumu = 1");
        $stmt->bind_param("i", $urun_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $urun = $result->fetch_assoc();
            $birim_fiyat = (float)$urun["fiyat"];
            $ara_toplam = $birim_fiyat * $adet;
            $toplam_tutar += $ara_toplam;

            $secilenUrunler[] = [
                "urun_id" => $urun["id"],
                "adet" => $adet,
                "birim_fiyat" => $birim_fiyat
            ];
        }
    }
}

if (count($secilenUrunler) === 0) {
    die("En az bir ürün seçmelisiniz.");
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

    foreach ($secilenUrunler as $item) {
        $stmtDetay->bind_param(
            "iiid",
            $siparis_id,
            $item["urun_id"],
            $item["adet"],
            $item["birim_fiyat"]
        );
        $stmtDetay->execute();
    }

    $conn->commit();

    echo "<h2>Sipariş başarıyla oluşturuldu.</h2>";
    echo "<p>Sipariş numarası: #" . $siparis_id . "</p>";
    echo "<p>Toplam tutar: " . number_format($toplam_tutar, 2) . " TL</p>";
    echo '<p><a href="siparis-olustur.php">Yeni sipariş oluştur</a></p>';
} catch (Exception $e) {
    $conn->rollback();
    die("Sipariş kaydedilirken hata oluştu: " . $e->getMessage());
}
?>