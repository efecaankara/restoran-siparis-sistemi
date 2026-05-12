<?php
session_start();
include "db.php";

$cart = $_SESSION["cart"] ?? [];
$toplam = 0;

foreach ($cart as $item) {
    $toplam += $item["fiyat"] * $item["adet"];
}

if (empty($cart)) {
    header("Location: sepet.php");
    exit;
}

$masalar = $conn->query("SELECT * FROM masalar WHERE durum = 'Boş' ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ödeme</title>
<link rel="stylesheet" href="assets/css/site.css">
</head>

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

                    <div class="payment-options">
                        <label class="payment-card">
                            <input type="radio" name="siparis_tipi" value="Paket Servis" checked>
                            <span>Paket Servis</span>
                        </label>

                        <label class="payment-card">
                            <input type="radio" name="siparis_tipi" value="Masa Siparişi">
                            <span>Masa Siparişi</span>
                        </label>
                    </div>

                    <div id="adresAlani">
                        <textarea name="adres" rows="5" placeholder="Teslimat Adresi"></textarea>
                    </div>

                    <div id="masaAlani" style="display:none;">
                        <select name="masa_no">
                            <option value="">Masa Seçiniz</option>

                            <?php if ($masalar && $masalar->num_rows > 0): ?>
                                <?php while ($masa = $masalar->fetch_assoc()): ?>
                                    <option value="<?php echo htmlspecialchars($masa["masa_adi"]); ?>">
                                        <?php echo htmlspecialchars($masa["masa_adi"]); ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="card">
                    <h2>Ödeme Yöntemi</h2>

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

<script>
document.addEventListener("DOMContentLoaded", function () {
    const paymentRadios = document.querySelectorAll('input[name="odeme_yontemi"]');
    const cardFields = document.getElementById("cardFields");

    const orderTypeRadios = document.querySelectorAll('input[name="siparis_tipi"]');
    const adresAlani = document.getElementById("adresAlani");
    const masaAlani = document.getElementById("masaAlani");
    const adresTextarea = document.querySelector('textarea[name="adres"]');
    const masaSelect = document.querySelector('select[name="masa_no"]');

    function toggleCardFields() {
        const selectedRadio = document.querySelector('input[name="odeme_yontemi"]:checked');

        if (!selectedRadio || !cardFields) return;

        cardFields.style.display = selectedRadio.value === "Kart ile Ödeme" ? "block" : "none";
    }

    function toggleOrderType() {
        const selectedType = document.querySelector('input[name="siparis_tipi"]:checked');

        if (!selectedType) return;

        if (selectedType.value === "Masa Siparişi") {
            adresAlani.style.display = "none";
            masaAlani.style.display = "block";

            adresTextarea.removeAttribute("required");
            masaSelect.setAttribute("required", "required");
        } else {
            adresAlani.style.display = "block";
            masaAlani.style.display = "none";

            adresTextarea.setAttribute("required", "required");
            masaSelect.removeAttribute("required");
        }
    }

    paymentRadios.forEach(function (radio) {
        radio.addEventListener("change", toggleCardFields);
    });

    orderTypeRadios.forEach(function (radio) {
        radio.addEventListener("change", toggleOrderType);
    });

    toggleCardFields();
    toggleOrderType();
});
</script>

</body>
</html>