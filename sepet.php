<?php
session_start();

$cart = $_SESSION["cart"] ?? [];
$toplam = 0;
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sepetim</title>

<link rel="stylesheet" href="assets/css/site.css">
</head>

<body>

<nav class="navbar">
    <div class="logo">GASTRO<span>NOMY</span></div>

    <a class="back-btn" href="urunler.php">
        Menüye Dön
    </a>
</nav>

<div class="container">
    <h1 class="page-title">Sepetim</h1>

    <?php if (!empty($cart)): ?>

    <div class="layout">

        <div class="cart-items">

            <?php foreach ($cart as $item): ?>
                <?php
                    $araToplam = $item["fiyat"] * $item["adet"];
                    $toplam += $araToplam;
                ?>

                <div class="cart-card">

                    <?php if (!empty($item["gorsel_yolu"])): ?>
                        <img src="<?php echo htmlspecialchars($item["gorsel_yolu"]); ?>">
                    <?php endif; ?>

                    <div class="cart-content">

                        <div class="cart-title">
                            <?php echo htmlspecialchars($item["urun_adi"]); ?>
                        </div>

                        <div class="cart-price">
                            <?php echo number_format($item["fiyat"], 2); ?> TL
                        </div>

                        <div class="quantity-box">
                            <a class="qty-btn" href="sepet-guncelle.php?id=<?php echo $item["id"]; ?>&islem=azalt">-</a>

                            <div class="qty-value">
                                <?php echo (int)$item["adet"]; ?>
                            </div>

                            <a class="qty-btn" href="sepet-guncelle.php?id=<?php echo $item["id"]; ?>&islem=artir">+</a>
                        </div>

                        <a 
                            class="remove-x" 
                            href="sepetten-sil.php?id=<?php echo $item["id"]; ?>"
                            onclick="return confirm('Bu ürün sepetten kaldırılsın mı?')"
                        >
                            ×
                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <div class="summary">

            <h2>Sipariş Özeti</h2>

            <div class="summary-row">
                <span>Ürün Toplamı</span>
                <span><?php echo number_format($toplam, 2); ?> TL</span>
            </div>

            <div class="summary-row">
                <span>Teslimat</span>
                <span>Ücretsiz</span>
            </div>

            <hr>

            <div class="summary-row total">
                <span>Toplam</span>
                <span><?php echo number_format($toplam, 2); ?> TL</span>
            </div>

            <form class="coupon-box">
                <input type="text" placeholder="İndirim Kodu Gir">
            </form>

            <a class="checkout-btn-link" href="odeme.php">
                Sepeti Onayla
            </a>

        </div>

    </div>

    <?php else: ?>

        <div class="empty-cart">

            <h2>Sepetiniz boş</h2>

            <p>Henüz sepetinize ürün eklemediniz.</p>

            <a class="shop-btn" href="urunler.php">
                Menüye Git
            </a>

        </div>

    <?php endif; ?>

</div>

</body>
</html>