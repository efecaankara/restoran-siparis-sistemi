<?php
session_start();

$cart = $_SESSION["cart"] ?? [];
$toplam = 0;

foreach ($cart as $item) {
    $toplam += $item["fiyat"] * $item["adet"];
}

if (empty($cart)) {
    header("Location: sepet.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ödeme</title>

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

.layout {
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap: 28px;
}

.card {
    background: white;
    border-radius: 18px;
    padding: 24px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}

h1 {
    margin-bottom: 25px;
}

h2 {
    margin-top: 0;
}

input,
textarea,
select {
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    border: 1px solid #ddd;
    margin-bottom: 14px;
    font-size: 14px;
}

.payment-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-bottom: 18px;
}

.payment-card {
    border: 2px solid #ddd;
    border-radius: 14px;
    padding: 18px;
    text-align: center;
    font-weight: bold;
    cursor: pointer;
    background: #f7f7f7;
    transition: 0.2s;
}

.payment-card input {
    display: none;
}

.payment-card:has(input:checked) {
    border-color: #ff7a00;
    background: #fff3ea;
    color: #ff7a00;
}

.card-fields {
    display: none;
}

.card-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 14px;
}

.total {
    font-size: 22px;
    font-weight: bold;
    color: #ff7a00;
}

.complete-btn {
    width: 100%;
    background: #ff7a00;
    border: none;
    color: white;
    padding: 16px;
    border-radius: 12px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
}

.order-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    color: #444;
}

@media (max-width: 900px) {
    .layout {
        grid-template-columns: 1fr;
    }
}

</style>
</head>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const paymentRadios = document.querySelectorAll('input[name="odeme_yontemi"]');
    const cardFields = document.getElementById("cardFields");

    function toggleCardFields() {
        const selectedRadio = document.querySelector('input[name="odeme_yontemi"]:checked');

        if (!selectedRadio || !cardFields) return;

        if (selectedRadio.value === "Kart ile Ödeme") {
            cardFields.style.display = "block";
        } else {
            cardFields.style.display = "none";
        }
    }

    paymentRadios.forEach(function (radio) {
        radio.addEventListener("change", toggleCardFields);
    });

    toggleCardFields();
});
</script>
<body>

<nav class="navbar">
    <div class="logo">GASTRO<span>NOMY</span></div>
    <a class="back-btn" href="sepet.php">Sepete Dön</a>
</nav>

<div class="container">
    <h1>Ödeme ve Teslimat</h1>

    <form action="siparis-tamamla.php" method="post">
        <div class="layout">

            <div>
                <div class="card">
                    <h2>Teslimat Bilgileri</h2>

                    <input 
                        type="text" 
                        name="musteri_ad_soyad" 
                        placeholder="Ad Soyad" 
                        value="<?php echo isset($_SESSION["user_name"]) ? htmlspecialchars($_SESSION["user_name"]) : ''; ?>"
                        required
                    >

                    <input 
                        type="text" 
                        name="musteri_tel" 
                        placeholder="Telefon Numarası" 
                        value="<?php echo isset($_SESSION["user_phone"]) ? htmlspecialchars($_SESSION["user_phone"]) : ''; ?>"
                        required
                    >

                    <textarea name="adres" rows="5" placeholder="Teslimat Adresi" required></textarea>
                </div>

                <div class="payment-options">
                    <label class="payment-card">
                        <input type="radio" name="odeme_yontemi" value="Kapıda Ödeme" checked>
                        <span>Kapıda Ödeme</span>
                    </label>

                    <label class="payment-card">
                        <input type="radio" name="odeme_yontemi" value="Kart ile Ödeme">
                        <span>Kart ile Ödeme</span>
                    </label>
                </div>

                <div id="cardFields" class="card-fields">
                    <input type="text" name="kart_no" placeholder="Kart Numarası">
                    <div class="card-row">
                        <input type="text" name="son_kullanma" placeholder="AA/YY">
                        <input type="text" name="cvv" placeholder="CVV">
                    </div>
                </div>
            </div>

            <div class="card">
                <h2>Sipariş Özeti</h2>

                <?php foreach ($cart as $item): ?>
                    <div class="order-item">
                        <span><?php echo htmlspecialchars($item["urun_adi"]); ?> x <?php echo (int)$item["adet"]; ?></span>
                        <span><?php echo number_format($item["fiyat"] * $item["adet"], 2); ?> TL</span>
                    </div>
                <?php endforeach; ?>

                <hr>

                <div class="summary-row">
                    <span>Ürün Toplamı</span>
                    <span><?php echo number_format($toplam, 2); ?> TL</span>
                </div>

                <div class="summary-row">
                    <span>Teslimat</span>
                    <span>Ücretsiz</span>
                </div>

                <div class="summary-row total">
                    <span>Toplam</span>
                    <span><?php echo number_format($toplam, 2); ?> TL</span>
                </div>

                <button class="complete-btn" type="submit">
                    Siparişi Tamamla
                </button>
            </div>

        </div>
    </form>
</div>

</body>
</html>