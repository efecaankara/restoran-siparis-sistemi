<?php
include "includes/auth.php";
include "db.php";

// Toplam ürün sayısı
$urunResult = $conn->query("SELECT COUNT(*) AS toplam_urun FROM urunler");
$toplamUrun = $urunResult->fetch_assoc()["toplam_urun"] ?? 0;

// Toplam sipariş sayısı
$siparisResult = $conn->query("SELECT COUNT(*) AS toplam_siparis FROM siparisler");
$toplamSiparis = $siparisResult->fetch_assoc()["toplam_siparis"] ?? 0;

// Bekleyen sipariş sayısı
$bekleyenResult = $conn->query("SELECT COUNT(*) AS bekleyen_siparis FROM siparisler WHERE durum = 'Beklemede'");
$bekleyenSiparis = $bekleyenResult->fetch_assoc()["bekleyen_siparis"] ?? 0;

// Hazırlanan sipariş sayısı
$hazirlaniyorResult = $conn->query("SELECT COUNT(*) AS hazirlaniyor_siparis FROM siparisler WHERE durum = 'Hazırlanıyor'");
$hazirlaniyorSiparis = $hazirlaniyorResult->fetch_assoc()["hazirlaniyor_siparis"] ?? 0;

// Tamamlanan sipariş sayısı
$tamamlananResult = $conn->query("SELECT COUNT(*) AS tamamlanan_siparis FROM siparisler WHERE durum = 'Tamamlandı'");
$tamamlananSiparis = $tamamlananResult->fetch_assoc()["tamamlanan_siparis"] ?? 0;

// Toplam ciro
$ciroResult = $conn->query("SELECT SUM(toplam_tutar) AS toplam_ciro FROM siparisler");
$toplamCiro = $ciroResult->fetch_assoc()["toplam_ciro"] ?? 0;

// Bugünkü ciro
$bugunCiroResult = $conn->query("SELECT SUM(toplam_tutar) AS bugun_ciro FROM siparisler WHERE DATE(siparis_tarihi) = CURDATE()");
$bugunCiro = $bugunCiroResult->fetch_assoc()["bugun_ciro"] ?? 0;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel</title>

<link rel="stylesheet" href="assets/css/admin.css">
</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

    <div class="logo">
        GASTRO<span>NOMY</span>
    </div>

    <div class="admin-info">
        <strong><?php echo htmlspecialchars($_SESSION["admin_username"]); ?></strong>
        <p>Yönetici Paneli</p>
    </div>

    <div class="menu">
        <a href="admin-panel.php">📊 Yönetim Paneli</a>
        <a href="siparisler-admin.php">🛒 Siparişler</a>
        <a href="urunleri-yonet.php">🍔 Ürünler</a>
        <a href="#">📂 Kategoriler</a>
        <a href="#">👥 Kullanıcılar</a>
        <a href="#">🎁 Kampanyalar</a>
        <a href="#">🍽️ Masa Yönetimi</a>
        <a href="urunler.php">🌐 Siteye Git</a>
        <a href="logout.php">🚪 Çıkış Yap</a>
    </div>

</div>

<!-- MAIN -->

<div class="main">

    <div class="topbar">
        <h1>Dashboard</h1>

        <div class="date">
            <?php echo date("d.m.Y"); ?>
        </div>
    </div>

    <!-- STATS -->

    <div class="stats">

        <div class="card">
            <div class="card-title">Toplam Ürün</div>
            <div class="card-value"><?php echo $toplamUrun; ?></div>
        </div>

        <div class="card">
            <div class="card-title">Toplam Sipariş</div>
            <div class="card-value"><?php echo $toplamSiparis; ?></div>
        </div>

        <div class="card">
            <div class="card-title">Bekleyen Sipariş</div>
            <div class="card-value orange"><?php echo $bekleyenSiparis; ?></div>
        </div>

        <div class="card">
            <div class="card-title">Hazırlanıyor</div>
            <div class="card-value"><?php echo $hazirlaniyorSiparis; ?></div>
        </div>

        <div class="card">
            <div class="card-title">Tamamlanan</div>
            <div class="card-value"><?php echo $tamamlananSiparis; ?></div>
        </div>

        <div class="card">
            <div class="card-title">Toplam Ciro</div>
            <div class="card-value orange">
                <?php echo number_format((float)$toplamCiro,2); ?> TL
            </div>
        </div>

    </div>

    <!-- QUICK ACTIONS -->

    <div class="quick-actions">

        <a class="action-btn" href="urun-ekle.php">
            ➕ Yeni Ürün Ekle
        </a>

        <a class="action-btn" href="urunleri-yonet.php">
            🍔 Ürünleri Yönet
        </a>

        <a class="action-btn" href="siparisler-admin.php">
            🛒 Siparişleri Gör
        </a>

        <a class="action-btn" href="urunler.php">
            🌐 Siteyi Görüntüle
        </a>

    </div>

    <!-- TABLE -->

    <div class="table-box">

        <h2>Genel Sistem Özeti</h2>

        <table>

            <tr>
                <th>Sistem</th>
                <th>Durum</th>
            </tr>

            <tr>
                <td>Ürün Yönetimi</td>
                <td><span class="status tamamlandi">Aktif</span></td>
            </tr>

            <tr>
                <td>Sipariş Sistemi</td>
                <td><span class="status tamamlandi">Aktif</span></td>
            </tr>

            <tr>
                <td>Ödeme Sistemi</td>
                <td><span class="status tamamlandi">Aktif</span></td>
            </tr>

            <tr>
                <td>Kullanıcı Sistemi</td>
                <td><span class="status tamamlandi">Aktif</span></td>
            </tr>

        </table>

    </div>

</div>

</body>
</html>