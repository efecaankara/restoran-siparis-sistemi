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
$odeme_yontemi = trim($_POST["odeme_yontemi"] ?? "Kapıda Ödeme");

if ($musteri_ad_soyad === "" || $musteri_tel === "" || $adres === "") {
    die("Lütfen tüm alanları doldurun.");
}

$toplam_tutar = 0;

foreach ($cart as $item) {
    $toplam_tutar += $item["fiyat"] * $item["adet"];
}

$conn->begin_transaction();

try {
    $user_id = isset($_SESSION["user_id"]) ? (int)$_SESSION["user_id"] : null;
    $odeme_yontemi = trim($_POST["odeme_yontemi"] ?? "Kapıda Ödeme");
    
    $stmtSiparis = $conn->prepare("
        INSERT INTO siparisler (user_id, musteri_ad_soyad, musteri_tel, adres, toplam_tutar, odeme_yontemi)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    if (!$stmtSiparis) {
        throw new Exception("Sipariş sorgusu hazırlanamadı: " . $conn->error);
    }
    $stmtSiparis->bind_param("isssds",$user_id,$musteri_ad_soyad,$musteri_tel,$adres,$toplam_tutar,$odeme_yontemi);

    if (!$stmtSiparis->execute()) {
        throw new Exception("Sipariş ana kaydı oluşturulamadı: " . $stmtSiparis->error);
    }

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

    ?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sipariş Tamamlandı</title>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f5f5f5;
    color: #111;
}

.success-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 30px;
}

.success-card {
    background: white;
    max-width: 520px;
    width: 100%;
    padding: 40px;
    border-radius: 22px;
    text-align: center;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
}

.success-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #ff7a00;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 42px;
    margin: 0 auto 20px;
}

.success-card h1 {
    margin: 0 0 12px;
    font-size: 30px;
}

.success-card p {
    color: #555;
    font-size: 16px;
}

.order-info {
    background: #f7f7f7;
    border-radius: 16px;
    padding: 18px;
    margin: 24px 0;
    text-align: left;
}

.order-info div {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
}

.order-info div:last-child {
    margin-bottom: 0;
}

.order-info strong {
    color: #111;
}

.btn {
    display: inline-block;
    background: #ff7a00;
    color: white;
    text-decoration: none;
    padding: 14px 22px;
    border-radius: 12px;
    font-weight: bold;
}
</style>
</head>

<body>

<div class="success-wrapper">
    <div class="success-card">
        <div class="success-icon">✓</div>

        <h1>Siparişiniz Alındı</h1>
        <p>Siparişiniz başarıyla oluşturuldu. En kısa sürede hazırlanacaktır.</p>

        <div class="order-info">
            <div>
                <span>Sipariş Numarası</span>
                <strong>#<?php echo $siparis_id; ?></strong>
            </div>

            <div>
                <span>Toplam Tutar</span>
                <strong><?php echo number_format($toplam_tutar, 2); ?> TL</strong>
            </div>
        </div>

        <a class="btn" href="urunler.php">Menüye Dön</a>
    </div>
</div>

</body>
</html>
<?php
} catch (Exception $e) {
    $conn->rollback();
    die("Sipariş kaydedilemedi: " . $e->getMessage());
}
?>