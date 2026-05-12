<?php
include "includes/auth.php";
include "db.php";

$mesaj = "";
$hata = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $urun_adi = trim($_POST["urun_adi"] ?? "");
    $kategori = trim($_POST["kategori"] ?? "");
    $aciklama = trim($_POST["aciklama"] ?? "");
    $fiyat = trim($_POST["fiyat"] ?? "");
    $gorsel_yolu = trim($_POST["gorsel_yolu"] ?? "");
    $stok_durumu = isset($_POST["stok_durumu"]) ? 1 : 0;
    $stok_miktari = trim($_POST["stok_miktari"] ?? "0");

    if ($urun_adi === "" || $fiyat === "") {
        $hata = "Ürün adı ve fiyat zorunludur.";
    } elseif (!is_numeric($fiyat)) {
        $hata = "Fiyat sayısal olmalıdır.";
    } elseif (!is_numeric($stok_miktari)) {
        $hata = "Stok miktarı sayısal olmalıdır.";
    } else {
        $stmt = $conn->prepare("INSERT INTO urunler (urun_adi, kategori, aciklama, fiyat, gorsel_yolu, stok_durumu, stok_miktari) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssii", $urun_adi, $kategori, $aciklama, $fiyat, $gorsel_yolu, $stok_durumu, $stok_miktari);

        if ($stmt->execute()) {
            $mesaj = "Ürün başarıyla eklendi.";
        } else {
            $hata = "Ürün eklenirken hata oluştu: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ürün Ekle</title>
<link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<div class="sidebar">
    <div class="logo">GASTRO<span>NOMY</span></div>
    <div class="admin-info">
        <strong><?php echo htmlspecialchars($_SESSION["admin_username"]); ?></strong>
        <p>Yönetici Paneli</p>
    </div>
    <div class="menu">
        <a href="admin-panel.php">📊 Yönetim Paneli</a>
        <a href="siparisler-admin.php">🛒 Siparişler</a>
        <a class="active" href="urunleri-yonet.php">🍔 Ürünler</a>
        <a href="#">📂 Kategoriler</a>
        <a href="#">👥 Kullanıcılar</a>
        <a href="#">🎁 Kampanyalar</a>
        <a href="#">🍽️ Masa Yönetimi</a>
        <a href="urunler.php">🌐 Siteye Git</a>
        <a href="logout.php">🚪 Çıkış Yap</a>
    </div>
</div>
<div class="main">
    <div class="topbar">
        <h1>Yeni Ürün Ekle</h1>
        <a class="back-link" href="urunleri-yonet.php">Ürünlere Dön</a>
    </div>
    <?php if ($mesaj): ?>
        <div class="alert success-alert">
            <?php echo htmlspecialchars($mesaj); ?>
        </div>
    <?php endif; ?>
    <?php if ($hata): ?>
        <div class="alert error-alert">
            <?php echo htmlspecialchars($hata); ?>
        </div>
    <?php endif; ?>

    <div class="form-card">

        <form method="post">

            <div class="form-grid">

                <div class="form-group">
                    <label>Ürün Adı</label>
                    <input type="text" name="urun_adi" placeholder="Örn: Double Burger">
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <input type="text" name="kategori" placeholder="Örn: Burger">
                </div>

                <div class="form-group full">
                    <label>Açıklama</label>
                    <textarea name="aciklama" rows="4" placeholder="Ürün açıklaması"></textarea>
                </div>

                <div class="form-group">
                    <label>Fiyat</label>
                    <input type="text" name="fiyat" placeholder="Örn: 180">
                </div>

                <div class="form-group">
                    <label>Stok Miktarı</label>
                    <input type="number" name="stok_miktari" min="0" value="10">
                </div>

                <div class="form-group full">
                    <label>Görsel Yolu</label>
                    <input type="text" name="gorsel_yolu" placeholder="Örn: images/Burger/double-burger.png">
                </div>

                <div class="form-group full">
                    <label class="checkbox-row">
                        <input type="checkbox" name="stok_durumu" checked>
                        Ürün aktif / stokta görünsün
                    </label>
                </div>
            </div>
            <button type="submit">Ürün Ekle</button>
        </form>
    </div>
</div>
</body>
</html>