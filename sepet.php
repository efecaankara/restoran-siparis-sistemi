<?php
session_start();
$cart = $_SESSION["cart"] ?? [];
$toplam = 0;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sepetim</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            padding: 20px;
        }

        .box {
            background: white;
            padding: 16px;
            margin-bottom: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.07);
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            padding: 10px 14px;
            border-radius: 8px;
            color: white;
            background: #ff7a00;
            margin-right: 6px;
        }

        .btn-danger {
            background: #d9534f;
        }

        .btn-secondary {
            background: #5bc0de;
        }

        .btn-dark {
            background: #444;
        }

        .toplam {
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
        }

        input, textarea {
            width: 300px;
            padding: 8px;
            margin-bottom: 12px;
        }

        .adet-kontrol {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>Sepetim</h1>
    <p><a class="btn" href="urunler.php">Menüye Dön</a></p>

    <?php if (!empty($cart)): ?>
        <?php foreach ($cart as $item): ?>
            <?php $araToplam = $item["fiyat"] * $item["adet"]; ?>
            <?php $toplam += $araToplam; ?>

            <div class="box">
                <h3><?php echo htmlspecialchars($item["urun_adi"]); ?></h3>
                <p>Adet: <?php echo (int)$item["adet"]; ?></p>
                <p>Birim Fiyat: <?php echo number_format($item["fiyat"], 2); ?> TL</p>
                <p>Ara Toplam: <?php echo number_format($araToplam, 2); ?> TL</p>

                <div class="adet-kontrol">
                    <a class="btn btn-secondary" href="sepet-guncelle.php?id=<?php echo $item["id"]; ?>&islem=azalt">-</a>
                    <a class="btn btn-secondary" href="sepet-guncelle.php?id=<?php echo $item["id"]; ?>&islem=artir">+</a>
                    <a class="btn btn-danger" href="sepetten-sil.php?id=<?php echo $item["id"]; ?>">Tamamını Sil</a>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="toplam">Toplam Tutar: <?php echo number_format($toplam, 2); ?> TL</div>

        <h2>Siparişi Tamamla</h2>
        <form action="siparis-tamamla.php" method="post">
            <label>Ad Soyad</label><br>
            <input type="text" name="musteri_ad_soyad" required><br>

            <label>Telefon</label><br>
            <input type="text" name="musteri_tel" required><br>

            <label>Adres</label><br>
            <textarea name="adres" required></textarea><br>

            <button class="btn" type="submit">Siparişi Onayla</button>
        </form>
    <?php else: ?>
        <p>Sepetiniz boş.</p>
    <?php endif; ?>
</body>
</html>