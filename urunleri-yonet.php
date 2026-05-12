<?php
include "includes/auth.php";
include "db.php";

$result = $conn->query("SELECT * FROM urunler ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ürün Yönetimi</title>

<link rel="stylesheet" href="assets/css/admin.css">
</head>

<body>

<div class="sidebar">
    <div class="logo">GASTRO<span>NOMY</span></div>

    <div class="admin-info">
        <strong><?php echo htmlspecialchars($_SESSION["admin_username"]); ?></strong>
        <p>Yönetici Paneli</p>
    </div>

    <div class="menu">
        <a href="admin-panel.php">📊 Yönetim Paneli</a>
        <a href="siparisler-admin.php">🛒 Siparişler</a>
        <a class="active" href="urunleri-yonet.php">🍔 Ürünler</a>
        <a href="kategoriler-admin.php">📂 Kategoriler</a>
        <a href="kullanicilar-admin.php">👥 Kullanıcılar</a>
        <a href="masa-yonetimi.php">🍽️ Masa Yönetimi</a>
        <a href="urunler.php">🌐 Siteye Git</a>
        <a href="logout.php">🚪 Çıkış Yap</a>
    </div>
</div>

<div class="main">

    <div class="topbar">
        <h1>Ürün Yönetimi</h1>
        <a class="add-btn" href="urun-ekle.php">+ Yeni Ürün Ekle</a>
    </div>

    <?php if ($result && $result->num_rows > 0): ?>

        <div class="products-grid">

            <?php while($row = $result->fetch_assoc()): ?>

                <div class="product-card">

                    <?php if (!empty($row["gorsel_yolu"])): ?>
                        <img class="product-img" src="<?php echo htmlspecialchars($row["gorsel_yolu"]); ?>" alt="<?php echo htmlspecialchars($row["urun_adi"]); ?>">
                    <?php else: ?>
                        <div class="product-img"></div>
                    <?php endif; ?>

                    <div class="product-body">
                        <div class="product-title">
                            <?php echo htmlspecialchars($row["urun_adi"]); ?>
                        </div>

                        <div class="product-category">
                            <?php echo htmlspecialchars($row["kategori"]); ?>
                        </div>

                        <div class="product-info">
                            <div class="info-row">
                                <span>Fiyat</span>
                                <strong><?php echo number_format((float)$row["fiyat"], 2); ?> TL</strong>
                            </div>

                            <div class="info-row">
                                <span>Stok</span>
                                <strong><?php echo (int)$row["stok_miktari"]; ?></strong>
                            </div>

                            <div class="info-row">
                                <span>Durum</span>
                                <?php if ((int)$row["stok_durumu"] === 1 && (int)$row["stok_miktari"] > 0): ?>
                                    <span class="badge active-badge">Aktif</span>
                                <?php else: ?>
                                    <span class="badge passive-badge">Pasif / Tükendi</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="actions">
                            <a class="btn edit-btn" href="urun-duzenle.php?id=<?php echo $row["id"]; ?>">Düzenle</a>

                            <a 
                                class="btn passive-btn"
                                href="urun-pasif.php?id=<?php echo $row["id"]; ?>" 
                                onclick="return confirm('Bu ürün pasife alınsın mı?')"
                            >
                                Pasife Al
                            </a>
                        </div>
                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    <?php else: ?>

        <div class="empty">
            Henüz ürün bulunmuyor.
        </div>

    <?php endif; ?>

</div>

</body>
</html>