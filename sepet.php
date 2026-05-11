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

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f5f5f5;
    color: #111;
}

.navbar {
    background: #111;
    color: white;
    padding: 18px 6%;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    font-size: 24px;
    font-weight: bold;
}

.logo span {
    color: #ff7a00;
}

.back-btn {
    color: white;
    text-decoration: none;
    border: 1px solid #444;
    padding: 10px 14px;
    border-radius: 10px;
}

.container {
    width: 90%;
    margin: 40px auto;
}

.page-title {
    font-size: 34px;
    margin-bottom: 25px;
}

.layout {
    display: grid;
    grid-template-columns: 1.4fr 0.8fr;
    gap: 28px;
}

.cart-items {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.cart-card {
    background: white;
    border-radius: 18px;
    overflow: hidden;
    display: flex;
    gap: 18px;
    padding: 18px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.08);
}

.cart-card img {
    width: 160px;
    height: 140px;
    object-fit: cover;
    border-radius: 14px;
}

.cart-content {
    flex: 1;
}

.cart-title {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 6px;
}

.cart-price {
    color: #ff7a00;
    font-weight: bold;
    font-size: 20px;
    margin-bottom: 12px;
}

.quantity-box {
    display: flex;
    align-items: center;
    gap: 10px;
}

.qty-btn {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #ff7a00;
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: bold;
}

.qty-value {
    font-size: 18px;
    font-weight: bold;
}

.remove-btn {
    display: inline-block;
    margin-top: 14px;
    color: #d9534f;
    text-decoration: none;
    font-weight: bold;
}

.summary {
    background: white;
    border-radius: 18px;
    padding: 24px;
    height: fit-content;
    position: sticky;
    top: 100px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.08);
}

.summary h2 {
    margin-top: 0;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 16px;
}

.total {
    font-size: 22px;
    font-weight: bold;
    color: #ff7a00;
}

input,
textarea {
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    border: 1px solid #ddd;
    margin-bottom: 14px;
    font-size: 14px;
}

.checkout-btn {
    width: 100%;
    border: none;
    background: #ff7a00;
    color: white;
    padding: 16px;
    border-radius: 12px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
}

.empty-cart {
    background: white;
    padding: 60px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 5px 18px rgba(0,0,0,0.08);
}

.empty-cart h2 {
    margin-top: 0;
}

.shop-btn {
    display: inline-block;
    margin-top: 16px;
    background: #ff7a00;
    color: white;
    text-decoration: none;
    padding: 14px 20px;
    border-radius: 12px;
    font-weight: bold;
}

@media (max-width: 900px) {
    .layout {
        grid-template-columns: 1fr;
    }

    .summary {
        position: static;
    }
}

@media (max-width: 600px) {
    .container {
        width: 94%;
    }

    .page-title {
        font-size: 28px;
    }

    .cart-card {
        flex-direction: column;
    }

    .cart-card img {
        width: 100%;
        height: 220px;
    }

    .navbar {
        padding: 16px 4%;
    }
}
.coupon-box input {
    width: 100%;
    padding: 13px;
    border-radius: 10px;
    border: 1px solid #ddd;
    margin: 16px 0;
}

.checkout-btn-link {
    display: block;
    text-align: center;
    width: 100%;
    background: #ff7a00;
    color: white;
    padding: 16px;
    border-radius: 12px;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
}
.cart-card {
    position: relative;
}

.remove-x {
    position: absolute;
    top: 14px;
    right: 16px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #f1f1f1;
    color: #333;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    font-weight: bold;
}

.remove-x:hover {
    background: #ff4d4d;
    color: white;
}
</style>
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