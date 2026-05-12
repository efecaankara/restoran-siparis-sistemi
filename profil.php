<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION["user_id"];

$aktifStmt = $conn->prepare("
    SELECT * FROM siparisler
    WHERE user_id = ? AND durum IN ('Beklemede', 'Hazırlanıyor', 'Yolda')
    ORDER BY id DESC
");
$aktifStmt->bind_param("i", $user_id);
$aktifStmt->execute();
$aktifResult = $aktifStmt->get_result();

$gecmisStmt = $conn->prepare("
    SELECT * FROM siparisler
    WHERE user_id = ? AND durum IN ('Teslim Edildi', 'İptal Edildi')
    ORDER BY id DESC
");
$gecmisStmt->bind_param("i", $user_id);
$gecmisStmt->execute();
$gecmisResult = $gecmisStmt->get_result();

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
<title>Profilim</title>

<link rel="stylesheet" href="assets/css/site.css">
</head>

<body>

<nav class="navbar">
    <div class="logo">GASTRO<span>NOMY</span></div>
    <a class="back-btn" href="urunler.php">Menüye Dön</a>
</nav>

<div class="container">

    <div class="profile-card">
        <div class="profile-name">
            <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
        </div>

        <div class="profile-email">
            <?php echo htmlspecialchars($_SESSION["user_email"]); ?>
        </div>

        <a 
            class="logout-btn" 
            href="user-logout.php"
            onclick="return confirm('Çıkış yapmak istediğinize emin misiniz?')"
        >
            Çıkış Yap
        </a>
    </div>

    <h2 class="section-title">Aktif Siparişler</h2>

    <?php if ($aktifResult->num_rows > 0): ?>
        <?php $aktifSira = 1; ?>
        <?php while ($siparis = $aktifResult->fetch_assoc()): ?>
            <div class="order-card">
                <div class="order-top">
                    <div class="order-id">
                        Sipariş #<?php echo $aktifSira; ?>
                    </div>

                    <div class="status <?php echo durumClass($siparis["durum"]); ?>">
                        <?php echo htmlspecialchars($siparis["durum"]); ?>
                    </div>
                </div>

                <?php
                $detayStmt = $conn->prepare("
                    SELECT sd.adet, u.urun_adi
                    FROM siparis_detay sd
                    INNER JOIN urunler u ON sd.urun_id = u.id
                    WHERE sd.siparis_id = ?
                ");

                    $detayStmt->bind_param("i", $siparis["id"]);
                    $detayStmt->execute();
                    $detayResult = $detayStmt->get_result();

                    $urunlerText = [];

                    while ($detay = $detayResult->fetch_assoc()) {
                        $urunlerText[] = $detay["urun_adi"] . " x" . $detay["adet"];
                    }
                    ?>

                    <div class="order-products">
                        <?php echo htmlspecialchars(implode(", ", $urunlerText)); ?>
                    </div>

                <div class="order-info">
                    <div class="info-box">
                        <div class="info-title">Toplam Tutar</div>
                        <div class="info-value">
                            <?php echo number_format((float)$siparis["toplam_tutar"], 2); ?> TL
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-title">Ödeme Yöntemi</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($siparis["odeme_yontemi"] ?? "-"); ?>
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-title">Sipariş Tarihi</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($siparis["siparis_tarihi"]); ?>
                        </div>
                    </div>
                </div>
                <?php if ($siparis["durum"] === "Beklemede" || $siparis["durum"] === "Hazırlanıyor"): ?>
                    <a class="cancel-order-btn" href="siparis-iptal.php?id=<?php echo (int)$siparis["id"]; ?>" onclick="return confirm('Bu siparişi iptal etmek istediğinize emin misiniz?')">
                        Siparişi İptal Et
                    </a>
                <?php endif; ?>
            </div>
            <?php $aktifSira++; ?>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="empty">Aktif siparişiniz bulunmuyor.</div>
    <?php endif; ?>


    <h2 class="section-title">Sipariş Geçmişi</h2>

    <?php if ($gecmisResult->num_rows > 0): ?>
        <?php $gecmisSira = 1; ?>
        <?php while ($siparis = $gecmisResult->fetch_assoc()): ?>
            <div class="order-card">
                <div class="order-top">
                    <div class="order-id">
                        Sipariş #<?php echo $gecmisSira; ?>
                    </div>

                    <div class="status <?php echo durumClass($siparis["durum"]); ?>">
                        <?php echo htmlspecialchars($siparis["durum"]); ?>
                    </div>
                </div>

                <?php
                $detayStmt = $conn->prepare("
                    SELECT sd.adet, u.urun_adi
                    FROM siparis_detay sd
                    INNER JOIN urunler u ON sd.urun_id = u.id
                    WHERE sd.siparis_id = ?
                ");

                $detayStmt->bind_param("i", $siparis["id"]);
                $detayStmt->execute();
                $detayResult = $detayStmt->get_result();

                $urunlerText = [];

                while ($detay = $detayResult->fetch_assoc()) {
                    $urunlerText[] = $detay["urun_adi"] . " x" . $detay["adet"];
                }
                ?>

                <div class="order-products">
                    <?php echo htmlspecialchars(implode(", ", $urunlerText)); ?>
                </div>

                <div class="order-info">
                    <div class="info-box">
                        <div class="info-title">Toplam Tutar</div>
                        <div class="info-value">
                            <?php echo number_format((float)$siparis["toplam_tutar"], 2); ?> TL
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-title">Ödeme Yöntemi</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($siparis["odeme_yontemi"] ?? "-"); ?>
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-title">Sipariş Tarihi</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($siparis["siparis_tarihi"]); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php $gecmisSira++; ?>
        <?php endwhile; ?>
        
    <?php else: ?>
        <div class="empty">Tamamlanmış siparişiniz bulunmuyor.</div>
    <?php endif; ?>

</div>

</body>
</html>