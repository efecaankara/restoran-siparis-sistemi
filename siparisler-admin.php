<?php
include "includes/auth.php";
include "db.php";

$sql = "SELECT * FROM siparisler ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Siparişler</title>
</head>
<body>
    <h1>Gelen Siparişler</h1>
    <p><a href="admin-panel.php">Panele Dön</a></p>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($siparis = $result->fetch_assoc()): ?>
            <div style="border:1px solid #999; padding:12px; margin-bottom:15px;">
                <h3>Sipariş #<?php echo $siparis["id"]; ?></h3>

                <p><strong>Müşteri:</strong> <?php echo htmlspecialchars($siparis["musteri_ad_soyad"]); ?></p>
                <p><strong>Telefon:</strong> <?php echo htmlspecialchars($siparis["musteri_tel"]); ?></p>
                <p><strong>Adres:</strong> <?php echo htmlspecialchars($siparis["adres"]); ?></p>
                <p><strong>Toplam:</strong> <?php echo number_format($siparis["toplam_tutar"], 2); ?> TL</p>
                <p><strong>Durum:</strong> <?php echo htmlspecialchars($siparis["durum"]); ?></p>
                <p><strong>Tarih:</strong> <?php echo htmlspecialchars($siparis["siparis_tarihi"]); ?></p>

                <h4>Ürünler</h4>
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

                <?php if ($detayResult->num_rows > 0): ?>
                    <ul>
                        <?php while ($detay = $detayResult->fetch_assoc()): ?>
                            <li>
                                <?php echo htmlspecialchars($detay["urun_adi"]); ?> -
                                <?php echo (int)$detay["adet"]; ?> adet -
                                <?php echo number_format($detay["birim_fiyat"], 2); ?> TL
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>Detay bulunamadı.</p>
                <?php endif; ?>

                <form action="siparis-durum-guncelle.php" method="post">
                    <input type="hidden" name="siparis_id" value="<?php echo $siparis["id"]; ?>">

                    <select name="durum">
                        <option value="Beklemede" <?php echo $siparis["durum"] === "Beklemede" ? "selected" : ""; ?>>Beklemede</option>
                        <option value="Hazırlanıyor" <?php echo $siparis["durum"] === "Hazırlanıyor" ? "selected" : ""; ?>>Hazırlanıyor</option>
                        <option value="Tamamlandı" <?php echo $siparis["durum"] === "Tamamlandı" ? "selected" : ""; ?>>Tamamlandı</option>
                    </select>

                    <button type="submit">Durumu Güncelle</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Henüz sipariş yok.</p>
    <?php endif; ?>
</body>
</html>