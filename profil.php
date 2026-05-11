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
    WHERE user_id = ? AND durum IN ('Beklemede', 'Hazırlanıyor')
    ORDER BY id DESC
");
$aktifStmt->bind_param("i", $user_id);
$aktifStmt->execute();
$aktifResult = $aktifStmt->get_result();

$gecmisStmt = $conn->prepare("
    SELECT * FROM siparisler
    WHERE user_id = ? AND durum = 'Tamamlandı'
    ORDER BY id DESC
");
$gecmisStmt->bind_param("i", $user_id);
$gecmisStmt->execute();
$gecmisResult = $gecmisStmt->get_result();

function durumClass($durum) {
    if ($durum === "Beklemede") return "beklemede";
    if ($durum === "Hazırlanıyor") return "hazirlaniyor";
    if ($durum === "Tamamlandı") return "tamamlandi";
    return "";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profilim</title>

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

.profile-card {
    background: white;
    border-radius: 20px;
    padding: 28px;
    margin-bottom: 28px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}

.profile-name {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 10px;
}

.profile-email {
    color: #666;
}

.logout-btn {
    display: inline-block;
    margin-top: 18px;
    background: #ff4d4d;
    color: white;
    text-decoration: none;
    padding: 12px 18px;
    border-radius: 12px;
    font-weight: bold;
}

.section-title {
    font-size: 28px;
    margin: 32px 0 20px;
}

.order-card {
    background: white;
    border-radius: 18px;
    padding: 22px;
    margin-bottom: 18px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.08);
}

.order-top {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 16px;
}

.order-id {
    font-size: 22px;
    font-weight: bold;
}

.status {
    padding: 8px 14px;
    border-radius: 999px;
    font-weight: bold;
    font-size: 14px;
}

.status.beklemede {
    background: #fff3cd;
    color: #856404;
}

.status.hazirlaniyor {
    background: #d1ecf1;
    color: #0c5460;
}

.status.tamamlandi {
    background: #d4edda;
    color: #155724;
}

.order-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 14px;
}

.info-box {
    background: #f7f7f7;
    border-radius: 14px;
    padding: 14px;
}

.info-title {
    font-size: 13px;
    color: #666;
    margin-bottom: 6px;
}

.info-value {
    font-weight: bold;
}

.empty {
    background: white;
    padding: 34px;
    border-radius: 18px;
    text-align: center;
    color: #666;
    margin-bottom: 18px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.06);
}

@media (max-width: 700px) {
    .container {
        width: 94%;
    }

    .profile-name {
        font-size: 24px;
    }

    .section-title {
        font-size: 24px;
    }
}
.order-products {
    background: #fff3ea;
    color: #333;
    padding: 12px 14px;
    border-radius: 12px;
    margin-bottom: 16px;
    font-weight: bold;
    line-height: 1.5;
}
</style>
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

        <a class="logout-btn" href="user-logout.php">Çıkış Yap</a>
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