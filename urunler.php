<?php
include "db.php";
session_start();

$kategori = $_GET["kategori"] ?? "Tümü";
$arama = trim($_GET["arama"] ?? "");

$aramaYapildi = $arama !== "";

$kategorilerResult = $conn->query("SELECT DISTINCT kategori FROM urunler ORDER BY kategori ASC");

if ($kategori !== "Tümü" && $arama !== "") {
    $stmt = $conn->prepare("SELECT * FROM urunler WHERE kategori = ? AND urun_adi LIKE ? ORDER BY id DESC");
    $like = "%".$arama."%";
    $stmt->bind_param("ss", $kategori, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} elseif ($kategori !== "Tümü") {
    $stmt = $conn->prepare("SELECT * FROM urunler WHERE kategori = ? ORDER BY id DESC");
    $stmt->bind_param("s", $kategori);
    $stmt->execute();
    $result = $stmt->get_result();
} elseif ($arama !== "") {
    $stmt = $conn->prepare("SELECT * FROM urunler WHERE urun_adi LIKE ? ORDER BY id DESC");
    $like = "%".$arama."%";
    $stmt->bind_param("s", $like);
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

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f7f7f7;
    color: #111;
}

.navbar {
    background: #111;
    color: white;
    padding: 18px 6%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    position: sticky;
    top: 0;
    z-index: 100;
}

.logo {
    font-size: 24px;
    font-weight: bold;
}

.logo span {
    color: #ff7a00;
}

.nav-links {
    display: flex;
    gap: 20px;
}

.nav-links a {
    color: white;
    text-decoration: none;
    font-size: 14px;
    font-weight: bold;
}

.search-box {
    display: flex;
    background: #222;
    border: 1px solid #333;
    border-radius: 12px;
    overflow: hidden;
    min-width: 300px;
}

.search-box input {
    flex: 1;
    padding: 12px;
    border: none;
    outline: none;
    background: #222;
    color: white;
}

.search-box button {
    border: none;
    background: #ff7a00;
    color: white;
    padding: 0 16px;
    cursor: pointer;
}

.right-menu {
    display: flex;
    align-items: center;
    gap: 10px;
}

.cart-btn,
.login-btn {
    color: white;
    text-decoration: none;
    border: 1px solid #444;
    padding: 10px 14px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: bold;
}

.cart-count {
    background: #ff7a00;
    padding: 3px 8px;
    border-radius: 20px;
    margin-left: 5px;
}

.hero {
    width: 100%;
    height: 520px;
    max-height: 520px;
    background:
        linear-gradient(90deg, rgba(0,0,0,0.92), rgba(0,0,0,0.55), rgba(0,0,0,0.15)),
        url("images/Hero/hero-burger.jpg") center center / cover no-repeat;
    display: flex;
    align-items: center;
    padding: 70px 6%;
    color: white;
}

.hero-content {
    max-width: 620px;
}

.hero-content .small {
    color: #ff9b33;
    font-size: 24px;
    font-style: italic;
    margin-bottom: 14px;
}

.hero-content h1 {
    font-size: 64px;
    line-height: 1.05;
    margin: 0 0 22px;
}

.hero-content h1 span {
    color: #ff7a00;
}

.hero-content p {
    font-size: 19px;
    line-height: 1.6;
    max-width: 500px;
}

.hero-btn {
    display: inline-block;
    margin-top: 20px;
    background: #ff7a00;
    color: white;
    padding: 15px 26px;
    text-decoration: none;
    border-radius: 12px;
    font-weight: bold;
}

.container {
    width: 88%;
    margin: 45px auto;
}

.section-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
}

.section-head h2 {
    margin: 0;
    font-size: 28px;
}

.featured-grid,
.product-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 22px;
}

.product-card {
    background: white;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 6px 22px rgba(0,0,0,0.08);
    transition: 0.2s;
}

.product-card:hover {
    transform: translateY(-4px);
}

.product-card img {
    width: 100%;
    height: 240px;
    object-fit: cover;
}

.product-body {
    padding: 16px;
}

.product-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 6px;
}

.category {
    color: #777;
    font-size: 14px;
    margin-bottom: 8px;
}


.product-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 14px;
    gap: 10px;
}

.price {
    color: #ff7a00;
    font-size: 18px;
    font-weight: bold;
}

.btn {
    background: #ff7a00;
    color: white;
    text-decoration: none;
    border-radius: 10px;
    padding: 10px 13px;
    font-size: 13px;
    font-weight: bold;
    white-space: nowrap;
}

.btn-disabled {
    background: #999;
    cursor: not-allowed;
}

.filters {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
}

.filter-btn {
    background: white;
    color: #222;
    text-decoration: none;
    padding: 16px 26px;
    border-radius: 16px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.08);
    border: 1px solid transparent;
    font-weight: bold;
}

.filter-btn.active {
    border-color: #ff7a00;
    color: #ff7a00;
}

.features {
    background: #111;
    color: white;
    border-radius: 20px;
    padding: 28px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    margin-top: 45px;
}

.feature h3 {
    color: #ff9b33;
    margin: 0 0 8px;
}

.feature p {
    margin: 0;
    color: #ddd;
    font-size: 14px;
}

@media (max-width: 1100px) {
    .featured-grid,
    .product-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    .navbar {
        flex-wrap: wrap;
    }

    .search-box {
        order: 3;
        width: 100%;
    }
}

@media (max-width: 768px) {
    .nav-links {
        display: none;
    }

    .navbar {
        padding: 16px 4%;
    }

    .hero {
        height: 430px;
        max-height: 430px;
        padding: 45px 5%;
        background-position: 62% center;
    }

    .hero-content h1 {
        font-size: 42px;
    }

    .hero-content p {
        font-size: 16px;
    }

    .container {
        width: 92%;
    }

    .featured-grid,
    .product-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    .features {
        grid-template-columns: 1fr 1fr;
    }

    .product-card img {
        height: 210px;
    }
}

@media (max-width: 520px) {
    .right-menu {
        width: 100%;
        justify-content: space-between;
    }

    .featured-grid,
    .product-grid {
        grid-template-columns: 1fr;
    }

    .features {
        grid-template-columns: 1fr;
    }

    .hero-content h1 {
        font-size: 34px;
    }

    .filter-btn {
        width: 100%;
        text-align: center;
    }
}
@media (min-width: 1400px) {
    .hero {
        height: 460px;
        max-height: 460px;
        background-size: cover;
        background-position: center center;
    }
}

@media (max-width: 520px) {
    .hero {
        height: 520px;
        max-height: 520px;
        background-position: 68% center;
    }

    .hero::before {
        content: "";
    }
}
html {
    scroll-behavior: smooth;
}

section {
    scroll-margin-top: 200px;
}

</style>
</head>

<body>
<div id="top"></div>
<nav class="navbar">
    <div class="logo">GASTRO<span>NOMY</span></div>

    <div class="nav-links">
        <a href="#top">MENÜ</a>
        <a href="#one-cikanlar">ÖNE ÇIKANLAR</a>
        <a href="#kategoriler">KATEGORİLER</a>
        <a href="#menu">TÜM MENÜ</a>
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
                <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
            </a>
            <a class="login-btn" href="user-logout.php">Çıkış</a>
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