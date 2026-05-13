<?php
include "includes/auth.php";
include "db.php";

// Toplam ürün sayısı
$urunResult = $conn->query("SELECT COUNT(*) AS toplam_urun FROM urunler");
$toplamUrun = $urunResult->fetch_assoc()["toplam_urun"] ?? 0;

// Toplam sıparis sayısı
$siparisResult = $conn->query("SELECT COUNT(*) AS toplam_siparis FROM siparisler");
$toplamSiparis = $siparisResult->fetch_assoc()["toplam_siparis"] ?? 0;

// Bekleyen sıparis sayısı
$bekleyenResult = $conn->query("SELECT COUNT(*) AS bekleyen_siparis FROM siparisler WHERE durum = 'Beklemede'");
$bekleyenSiparis = $bekleyenResult->fetch_assoc()["bekleyen_siparis"] ?? 0;

// Hazırlanan sipariş sayısı
$hazirlaniyorResult = $conn->query("SELECT COUNT(*) AS hazirlaniyor_siparis FROM siparisler WHERE durum = 'Hazırlanıyor'");
$hazirlaniyorSiparis = $hazirlaniyorResult->fetch_assoc()["hazirlaniyor_siparis"] ?? 0;

// Yolda ki sipariş sayısı
$yoldaResult = $conn->query("SELECT COUNT(*) AS yolda_siparis FROM siparisler WHERE durum = 'Yolda'");
$yoldaSiparis = $yoldaResult->fetch_assoc()["yolda_siparis"] ?? 0;

// Tesliim edilen sipariş
$teslimResult = $conn->query("SELECT COUNT(*) AS teslim_siparis FROM siparisler WHERE durum = 'Teslim Edildi'");
$teslimSiparis = $teslimResult->fetch_assoc()["teslim_siparis"] ?? 0;

//İptal edilen sipariş
$iptalResult = $conn->query("SELECT COUNT(*) AS iptal_siparis FROM siparisler WHERE durum = 'İptal Edildi'");
$iptalSiparis = $iptalResult->fetch_assoc()["iptal_siparis"] ?? 0;

// Toplam ciro
$ciroResult = $conn->query("
    SELECT SUM(toplam_tutar) AS toplam_ciro 
    FROM siparisler
    WHERE durum != 'İptal Edildi'
");

$toplamCiro = $ciroResult->fetch_assoc()["toplam_ciro"] ?? 0;


// Bugünkü ciro
$bugunCiroResult = $conn->query("
    SELECT SUM(toplam_tutar) AS bugun_ciro 
    FROM siparisler
    WHERE DATE(siparis_tarihi) = CURDATE()
    AND durum != 'İptal Edildi'
");

$bugunCiro = $bugunCiroResult->fetch_assoc()["bugun_ciro"] ?? 0;
// Son sıparısler
$sonSiparisler = $conn->query(" SELECT id, musteri_ad_soyad, masa_no, adres, toplam_tutar, durum, siparis_tarihi FROM siparisler ORDER BY id DESC LIMIT 5 ");
if (!$sonSiparisler) {
    die("Son siparişler sorgu hatası: " . $conn->error);
}

// En cok satanlar
$enCokSatanlar = $conn->query(" SELECT u.urun_adi, SUM(sd.adet) AS toplam_satis FROM siparis_detay sd INNER JOIN urunler u ON sd.urun_id = u.id GROUP BY sd.urun_id, u.urun_adi ORDER BY toplam_satis DESC LIMIT 5 ");

//Haftalık gelir
$haftalikGelir = $conn->query(" SELECT DATE(siparis_tarihi) AS tarih, SUM(toplam_tutar) AS gelir FROM siparisler WHERE siparis_tarihi >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) GROUP BY DATE(siparis_tarihi) ORDER BY tarih ASC ");

$grafikLabels = [];
$grafikData = [];

if ($haftalikGelir && $haftalikGelir->num_rows > 0) {
    while ($row = $haftalikGelir->fetch_assoc()) {
        $grafikLabels[] = date("d.m", strtotime($row["tarih"]));
        $grafikData[] = (float)$row["gelir"];
    }
}

// Masa durumları
$masaDurumlari = $conn->query(" SELECT * FROM masalar ORDER BY id ASC ");

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
<title>Admin Panel</title>

<link rel="stylesheet" href="assets/css/admin.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <a class="active" href="admin-panel.php">📊 Yönetim Paneli</a>
        <a href="siparisler-admin.php">🛒 Siparişler</a>
        <a href="urunleri-yonet.php">🍔 Ürünler</a>
        <a href="kategoriler-admin.php">📂 Kategoriler</a>
        <a href="kullanicilar-admin.php">👥 Kullanıcılar</a>
        <a href="masa-yonetimi.php">🍽️ Masa Yönetimi</a>
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
            <div class="card-title">Yolda</div>
            <div class="card-value"><?php echo $yoldaSiparis; ?></div>
        </div>

        <div class="card">
            <div class="card-title">Teslim Edildi</div>
            <div class="card-value"><?php echo $teslimSiparis; ?></div>
        </div>

        <div class="card">
            <div class="card-title">İptal Edildi</div>
            <div class="card-value orange"><?php echo $iptalSiparis; ?></div>
        </div>

        <div class="card">
            <div class="card-title">Toplam Ciro</div>
            <div class="card-value orange">
                <?php echo number_format($toplamCiro, 2); ?>TL
            </div>
        </div>

    </div>

    <!-- TABLE -->

    <div class="dashboard-grid dashboard-top-grid">

    <div class="table-box action-box">
        <div class="side-actions">
            <a href="urun-ekle.php">➕ Yeni Ürün Ekle</a>
            <a href="urunleri-yonet.php">🍔 Ürünleri Yönet</a>
            <a href="siparisler-admin.php">🛒 Siparişleri Gör</a>
            <a href="urunler.php">🌐 Siteyi Görüntüle</a>
        </div>
    </div>

    <div class="table-box">
        <h2>Haftalık Gelir Grafiği</h2>

        <div class="chart-wrapper">
            <canvas id="gelirChart"></canvas>
        </div>
    </div>

    <div class="table-box">
        <h2>En Çok Satan Ürünler</h2>

        <div class="best-list">
            <?php if ($enCokSatanlar && $enCokSatanlar->num_rows > 0): ?>
                <?php while ($urun = $enCokSatanlar->fetch_assoc()): ?>
                    <div class="best-item">
                        <span><?php echo htmlspecialchars($urun["urun_adi"]); ?></span>
                        <strong><?php echo (int)$urun["toplam_satis"]; ?> adet</strong>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Henüz satış verisi yok.</p>
            <?php endif; ?>
        </div>
    </div>

</div>

    <div class="dashboard-grid">

        <div class="table-box">
            <div class="box-head">
                <h2>Son Siparişler</h2>
                <a href="siparisler-admin.php">Tümünü Gör</a>
            </div>

            <table>
                <tr>
                    <th>#</th>
                    <th>Müşteri</th>
                    <th>Masa / Adres</th>
                    <th>Tutar</th>
                    <th>Durum</th>
                </tr>

                <?php if ($sonSiparisler && $sonSiparisler->num_rows > 0): ?>
                    <?php while ($siparis = $sonSiparisler->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $siparis["id"]; ?></td>
                            <td><?php echo htmlspecialchars($siparis["musteri_ad_soyad"]); ?></td>
                            <td>
                                <?php 
                                    if (!empty($siparis["masa_no"])) {
                                        echo htmlspecialchars($siparis["masa_no"]);
                                    } else {
                                        echo "Paket Servis";
                                    }
                                ?>
                            </td>
                            <td><?php echo number_format((float)$siparis["toplam_tutar"], 2); ?> TL</td>
                            <td>
                                <span class="status <?php echo durumClass($siparis["durum"]); ?>">
                                    <?php echo htmlspecialchars($siparis["durum"]); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Henüz sipariş yok.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <div class="table-box">
            <div class="box-head">
                <h2>Masa Durumu</h2>
                <a href="masa-yonetimi.php">Tümünü Gör</a>
            </div>

            <div class="mini-masa-grid">
                <?php if ($masaDurumlari && $masaDurumlari->num_rows > 0): ?>
                    <?php while ($masa = $masaDurumlari->fetch_assoc()): ?>
                        <div class="mini-masa <?php echo $masa["durum"] === "Dolu" ? "mini-dolu" : "mini-bos"; ?>">
                            <strong><?php echo htmlspecialchars($masa["masa_adi"]); ?></strong>
                            <span><?php echo htmlspecialchars($masa["durum"]); ?></span>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Henüz masa eklenmemiş.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
const ctx = document.getElementById('gelirChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($grafikLabels); ?>,
        datasets: [{
            label: 'Gelir',
            data: <?php echo json_encode($grafikData); ?>,
            borderColor: '#8b5cf6',
            backgroundColor: 'rgba(139,92,246,0.12)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#8b5cf6',
            pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#eee'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>
</body>
</html>