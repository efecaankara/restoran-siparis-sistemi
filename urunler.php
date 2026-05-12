<?php
include "db.php";
session_start();

$kategori = $_GET["kategori"] ?? "Tümü";
$arama = trim($_GET["arama"] ?? "");

$aramaYapildi = $arama !== "";

$kategorilerResult = $conn->query("SELECT DISTINCT kategori FROM urunler ORDER BY kategori ASC");

if ($kategori !== "Tümü" && $arama !== "") {
    $stmt = $conn->prepare("
        SELECT * FROM urunler 
        WHERE kategori = ? 
        AND (urun_adi LIKE ? OR kategori LIKE ?)
        ORDER BY id DESC
    ");
    $like = "%".$arama."%";
    $stmt->bind_param("sss", $kategori, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();

} elseif ($kategori !== "Tümü") {
    $stmt = $conn->prepare("
        SELECT * FROM urunler 
        WHERE kategori = ? 
        ORDER BY id DESC
    ");
    $stmt->bind_param("s", $kategori);
    $stmt->execute();
    $result = $stmt->get_result();

} elseif ($arama !== "") {
    $stmt = $conn->prepare("
        SELECT * FROM urunler 
        WHERE urun_adi LIKE ? OR kategori LIKE ?
        ORDER BY id DESC
    ");
    $like = "%".$arama."%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();

} else {
    $result = $conn->query("SELECT * FROM urunler ORDER BY id DESC");
}

$featured = $conn->query("SELECT * FROM urunler WHERE stok_miktari > 0 ORDER BY id DESC LIMIT 4");
$sepetAdet = isset($_SESSION["cart"]) ? array_sum(array_column($_SESSION["cart"], "adet")) : 0;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gastronomy - Menü</title>

<link rel="stylesheet" href="assets/css/site.css">
</head>

<body>
<div id="top"></div>
<nav class="navbar">
    <div class="logo">GASTRO<span>NOMY</span></div>

    <div class="nav-links">
        <a href="urunler.php">MENÜ</a>
        <a href="urunler.php#one-cikanlar">ÖNE ÇIKANLAR</a>
        <a href="urunler.php#kategoriler">KATEGORİLER</a>
        <a href="urunler.php#menu">TÜM MENÜ</a>
    </div>

    <form class="search-box" method="get" action="urunler.php#menu">
        <?php if ($kategori !== "Tümü"): ?>
            <input type="hidden" name="kategori" value="<?php echo htmlspecialchars($kategori); ?>">
        <?php endif; ?>
        <input type="text" name="arama" placeholder="Yemek, burger, pizza ara..." value="<?php echo htmlspecialchars($arama); ?>">
        <button type="submit">Ara</button>
    </form>

    <div class="right-menu">
        <a class="cart-btn" href="sepet.php">Sepetim <span class="cart-count"><?php echo $sepetAdet; ?></span></a>
        <?php if (isset($_SESSION["user_id"])): ?>
            <a class="login-btn" href="profil.php">
                Profilim
            </a>
        <?php else: ?>
            <a class="login-btn" href="login.php">Giriş Yap / Kaydol</a>
        <?php endif; ?>
    </div>
</nav>

<?php if (!$aramaYapildi): ?>
<section class="hero">
    <div class="hero-content">
        <div class="small">Lezzetin En Doğal Hali</div>
        <h1>Gurme Lezzetler,<br><span>Kapında!</span></h1>
        <p>En taze malzemelerle hazırlanmış özel tariflerimizi keşfedin, sepetinize ekleyin ve kolayca siparişinizi oluşturun.</p>
        <a class="hero-btn" href="#menu">Sipariş Ver</a>
    </div>
</section>
<?php endif; ?>

<?php if (!$aramaYapildi): ?>
<section id="one-cikanlar" class="container">
    <div class="section-head">
        <h2>Öne Çıkan Ürünler</h2>
    </div>

    <div class="featured-grid">
        <?php if ($featured && $featured->num_rows > 0): ?>
            <?php while ($row = $featured->fetch_assoc()): ?>
                <?php
                    $urunId = (int)$row["id"];
                    $stokMiktari = (int)$row["stok_miktari"];
                    $sepettekiAdet = isset($_SESSION["cart"][$urunId]) ? (int)$_SESSION["cart"][$urunId]["adet"] : 0;
                    $kalanStok = $stokMiktari - $sepettekiAdet;
                ?>

                <div class="product-card">
                    <?php if (!empty($row["gorsel_yolu"])): ?>
                        <img src="<?php echo htmlspecialchars($row["gorsel_yolu"]); ?>" alt="<?php echo htmlspecialchars($row["urun_adi"]); ?>">
                    <?php endif; ?>

                    <div class="product-body">
                        <div class="product-title"><?php echo htmlspecialchars($row["urun_adi"]); ?></div>

                        <div class="product-bottom">
                            <div class="price"><?php echo number_format((float)$row["fiyat"], 2); ?> TL</div>

                            <?php if ($kalanStok > 0): ?>
                                <a class="btn" href="sepete-ekle.php?id=<?php echo $urunId; ?>">Sepete Ekle</a>
                            <?php else: ?>
                                <span class="btn btn-disabled">Tükendi</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php if (!$aramaYapildi): ?>
<section id="kategoriler" class="container">
    <div class="section-head">
        <h2>Kategoriler</h2>
    </div>

    <div class="filters">
        <a class="filter-btn <?php echo $kategori === 'Tümü' ? 'active' : ''; ?>" href="urunler.php#menu">Tümü</a>

        <?php if ($kategorilerResult && $kategorilerResult->num_rows > 0): ?>
            <?php while ($kat = $kategorilerResult->fetch_assoc()): ?>
                <a class="filter-btn <?php echo $kategori === $kat["kategori"] ? 'active' : ''; ?>"
                   href="urunler.php?kategori=<?php echo urlencode($kat["kategori"]); ?>#menu">
                    <?php echo htmlspecialchars($kat["kategori"]); ?>
                </a>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<section id="menu" class="container">
    <div class="section-head">
        <h2>
            <?php if ($aramaYapildi): ?>
                "<?php echo htmlspecialchars($arama); ?>" için arama sonuçları
            <?php elseif ($kategori !== "Tümü"): ?>
                <?php echo htmlspecialchars($kategori); ?> Ürünleri    
            <?php else: ?>
                Tüm Menü
            <?php endif; ?>
        </h2>
    </div>

    <div class="product-grid">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                    $urunId = (int)$row["id"];
                    $stokMiktari = (int)$row["stok_miktari"];
                    $sepettekiAdet = isset($_SESSION["cart"][$urunId]) ? (int)$_SESSION["cart"][$urunId]["adet"] : 0;
                    $kalanStok = $stokMiktari - $sepettekiAdet;
                ?>

                <div class="product-card" id="urun-<?php echo $urunId; ?>">
                    <?php if (!empty($row["gorsel_yolu"])): ?>
                        <img src="<?php echo htmlspecialchars($row["gorsel_yolu"]); ?>" alt="<?php echo htmlspecialchars($row["urun_adi"]); ?>">
                    <?php endif; ?>

                    <div class="product-body">
                        <div class="product-title"><?php echo htmlspecialchars($row["urun_adi"]); ?></div>
                        <div class="category"><?php echo htmlspecialchars($row["kategori"]); ?></div>

                        <div class="product-bottom">
                            <div class="price"><?php echo number_format((float)$row["fiyat"], 2); ?> TL</div>

                            <?php if ($kalanStok > 0): ?>
                                <a class="btn" href="sepete-ekle.php?id=<?php echo $urunId; ?>&return=<?php echo urlencode($_SERVER['REQUEST_URI'] . '#urun-' . $urunId); ?>">Sepete Ekle</a>
                            <?php else: ?>
                                <span class="btn btn-disabled">Tükendi</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Ürün bulunamadı.</p>
        <?php endif; ?>
    </div>

    <div class="features">
        <div class="feature">
            <h3>Hızlı Teslimat</h3>
            <p>Siparişleriniz en kısa sürede hazırlanır.</p>
        </div>
        <div class="feature">
            <h3>Taze Malzemeler</h3>
            <p>Günlük ve kaliteli ürünler kullanılır.</p>
        </div>
        <div class="feature">
            <h3>Kolay Sipariş</h3>
            <p>Sepete ekleyin, bilgilerinizi girin ve siparişinizi tamamlayın.</p>
        </div>
        <div class="feature">
            <h3>Müşteri Desteği</h3>
            <p>Sipariş durumları yönetici panelinden takip edilir.</p>
        </div>
    </div>
</section>

</body>
</html>