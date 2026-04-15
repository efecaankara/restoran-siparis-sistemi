<?php
include "db.php";

$sql = "SELECT * FROM urunler WHERE stok_durumu = 1 ORDER BY kategori, urun_adi";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sipariş Oluştur</title>
</head>
<body>
    <h1>Sipariş Oluştur</h1>

    <form action="siparis-kaydet.php" method="post">
        <label>Ad Soyad:</label><br>
        <input type="text" name="musteri_ad_soyad" required><br><br>

        <label>Telefon:</label><br>
        <input type="text" name="musteri_tel" required><br><br>

        <label>Adres:</label><br>
        <textarea name="adres" required></textarea><br><br>

        <h3>Ürünler</h3>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
                    <strong><?php echo htmlspecialchars($row["urun_adi"]); ?></strong><br>
                    Kategori: <?php echo htmlspecialchars($row["kategori"]); ?><br>
                    Fiyat: <?php echo number_format($row["fiyat"], 2); ?> TL<br><br>

                    <input type="hidden" name="urun_id[]" value="<?php echo $row["id"]; ?>">

                    <label>Adet:</label>
                    <input type="number" name="adet[]" min="0" value="0">
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Ürün bulunamadı.</p>
        <?php endif; ?>

        <button type="submit">Siparişi Kaydet</button>
    </form>
</body>
</html>