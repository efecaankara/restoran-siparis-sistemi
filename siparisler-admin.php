<?php
include "includes/auth.php";
include "db.php";

$sql = "SELECT * FROM siparisler ORDER BY id DESC";
$result = $conn->query($sql);

function durumClass($durum) {
    if ($durum === "Beklemede") return "beklemede";
    if ($durum === "Hazırlanıyor") return "hazirlaniyor";
    if ($durum === "Yolda") return "yolda";
    if ($durum === "Teslim Edildi") return "teslimedildi";
    if ($durum === "İptal Edildi") return "iptaledildi";
    return "";
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Siparişler</title>

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
        <a class="active" href="siparisler-admin.php">🛒 Siparişler</a>
        <a href="urunleri-yonet.php">🍔 Ürünler</a>
        <a href="kategoriler-admin.php">📂 Kategoriler</a>
        <a href="kullanicilar-admin.php">👥 Kullanıcılar</a>
        <a href="masa-yonetimi.php">🍽️ Masa Yönetimi</a>
        <a href="urunler.php">🌐 Siteye Git</a>
        <a href="logout.php">🚪 Çıkış Yap</a>
    </div>
</div>

<div class="main">

    <div class="topbar">
        <h1>Siparişler</h1>
        <a class="back-link" href="admin-panel.php">Panele Dön</a>
    </div>

    <?php if ($result && $result->num_rows > 0): ?>
        <div class="orders-grid">

        <?php while ($siparis = $result->fetch_assoc()): ?>

            <?php
            $stmt = $conn->prepare("
                SELECT sd.adet, sd.birim_fiyat, u.urun_adi
                FROM siparis_detay sd
                INNER JOIN urunler u ON sd.urun_id = u.id
                WHERE sd.siparis_id = ?
            ");
            $stmt->bind_param("i", $siparis["id"]);
            $stmt->execute();
            $detayResult = $stmt->get_result();
            ?>

            <div class="order-card">

                <div class="order-header">
                    <div>
                        <div class="order-title">Sipariş #<?php echo $siparis["id"]; ?></div>
                        <div class="order-date"><?php echo htmlspecialchars($siparis["siparis_tarihi"]); ?></div>
                    </div>

                    <span class="status <?php echo durumClass($siparis["durum"]); ?>">
                        <?php echo htmlspecialchars($siparis["durum"]); ?>
                    </span>
                </div>

                <div class="info-grid">
                    <div class="info-box">
                        <div class="info-title">Müşteri</div>
                        <div class="info-value"><?php echo htmlspecialchars($siparis["musteri_ad_soyad"]); ?></div>
                    </div>

                    <div class="info-box">
                        <div class="info-title">Telefon</div>
                        <div class="info-value"><?php echo htmlspecialchars($siparis["musteri_tel"]); ?></div>
                    </div>

                    <div class="info-box">
                        <div class="info-title">Toplam Tutar</div>
                        <div class="info-value"><?php echo number_format((float)$siparis["toplam_tutar"], 2); ?> TL</div>
                    </div>

                    <div class="info-box">
                        <div class="info-title">Ödeme</div>
                        <div class="info-value"><?php echo htmlspecialchars($siparis["odeme_yontemi"] ?? "-"); ?></div>
                    </div>

                    <div class="info-box">
                        <div class="info-title">Adres</div>
                        <div class="info-value"><?php echo htmlspecialchars($siparis["adres"]); ?></div>
                    </div>
                </div>

                <div class="products-box">
                    <h3>Ürünler</h3>

                    <?php if ($detayResult->num_rows > 0): ?>
                        <ul class="product-list">
                            <?php while ($detay = $detayResult->fetch_assoc()): ?>
                                <li>
                                    <span>
                                        <?php echo htmlspecialchars($detay["urun_adi"]); ?> 
                                        x <?php echo (int)$detay["adet"]; ?>
                                    </span>

                                    <strong>
                                        <?php echo number_format((float)$detay["birim_fiyat"], 2); ?> TL
                                    </strong>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>Detay bulunamadı.</p>
                    <?php endif; ?>
                </div>

                <div class="actions">
                    <form action="siparis-durum-guncelle.php" method="post">
                        <input type="hidden" name="siparis_id" value="<?php echo $siparis["id"]; ?>">

                        <select name="durum">
                            <option value="Beklemede" <?php echo $siparis["durum"] === "Beklemede" ? "selected" : ""; ?>>Beklemede</option>
                            <option value="Hazırlanıyor" <?php echo $siparis["durum"] === "Hazırlanıyor" ? "selected" : ""; ?>>Hazırlanıyor</option>
                            <option value="Yolda" <?php echo $siparis["durum"] === "Yolda" ? "selected" : ""; ?>>Yolda</option>
                            <option value="Teslim Edildi" <?php echo $siparis["durum"] === "Teslim Edildi" ? "selected" : ""; ?>>Teslim Edildi</option>
                            <option value="İptal Edildi" <?php echo $siparis["durum"] === "İptal Edildi" ? "selected" : "";?>>İptal Edildi</option>
                        </select>

                        <button type="submit">Durumu Güncelle</button>
                    </form>

                    <?php if ($siparis["durum"] === "Teslim Edildi" || $siparis["durum"] === "İptal Edildi"): ?>
                        <a 
                            class="delete-btn"
                            href="siparis-sil.php?id=<?php echo $siparis["id"]; ?>" 
                            onclick="return confirm('Bu tamamlanan sipariş silinsin mi?')"
                        >
                            Siparişi Sil
                        </a>
                    <?php endif; ?>
                </div>

            </div>

        <?php endwhile; ?>

        </div>
    <?php else: ?>
        <div class="empty">Henüz sipariş yok.</div>
    <?php endif; ?>

</div>

</body>
</html>