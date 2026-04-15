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
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 30px auto;
        }

        h1 {
            margin-bottom: 10px;
        }

        .welcome {
            margin-bottom: 20px;
            color: #444;
        }

        .stats {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            width: 220px;
            padding: 18px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }

        .card h3 {
            margin: 0 0 10px;
            font-size: 18px;
            color: #333;
        }

        .card p {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            color: #ff7a00;
        }

        .menu-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }

        .menu-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-box li {
            margin-bottom: 12px;
        }

        .menu-box a {
            text-decoration: none;
            color: white;
            background: #ff7a00;
            padding: 10px 14px;
            border-radius: 8px;
            display: inline-block;
        }

        .menu-box a:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>
        <p class="welcome">Hoş geldin, <?php echo htmlspecialchars($_SESSION["admin_username"]); ?></p>

        <div class="stats">
            <div class="card">
                <h3>Toplam Ürün</h3>
                <p><?php echo $toplamUrun; ?></p>
            </div>

            <div class="card">
                <h3>Toplam Sipariş</h3>
                <p><?php echo $toplamSiparis; ?></p>
            </div>

            <div class="card">
                <h3>Bekleyen Sipariş</h3>
                <p><?php echo $bekleyenSiparis; ?></p>
            </div>

            <div class="card">
                <h3>Hazırlanıyor</h3>
                <p><?php echo $hazirlaniyorSiparis; ?></p>
            </div>

            <div class="card">
                <h3>Tamamlanan</h3>
                <p><?php echo $tamamlananSiparis; ?></p>
            </div>

            <div class="card">
                <h3>Toplam Ciro</h3>
                <p><?php echo number_format((float)$toplamCiro, 2); ?> TL</p>
            </div>

            <div class="card">
                <h3>Bugünkü Ciro</h3>
                <p><?php echo number_format((float)$bugunCiro, 2); ?> TL</p>
            </div>
        </div>

        <div class="menu-box">
            <ul>
                <li><a href="urun-ekle.php">Ürün Ekle</a></li>
                <li><a href="urunleri-yonet.php">Ürünleri Yönet</a></li>
                <li><a href="urunler.php">Ürünleri Gör</a></li>
                <li><a href="siparisler-admin.php">Siparişleri Gör</a></li>
                <li><a href="logout.php">Çıkış Yap</a></li>
            </ul>
        </div>
    </div>
</body>
</html>