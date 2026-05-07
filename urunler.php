<?php
include "db.php";
session_start();

$kategori = $_GET["kategori"] ?? "Tümü";
$arama = trim($_GET["arama"] ?? "");

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

$sepetAdet = isset($_SESSION["cart"]) ? array_sum(array_column($_SESSION["cart"], "adet")) : 0;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
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
            height: 72px;
            background: #111;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 42px;
        }

        .logo {
            font-size: 26px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .logo span {
            color: #ff7a00;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin: 0 14px;
            font-weight: bold;
            font-size: 14px;
        }

        .search-box {
            display: flex;
            background: #222;
            border: 1px solid #333;
            border-radius: 10px;
            overflow: hidden;
        }

        .search-box input {
            width: 300px;
            padding: 12px;
            border: none;
            outline: none;
            background: #222;
            color: white;
        }

        .search-box button {
            padding: 12px 16px;
            border: none;
            background: #ff7a00;
            color: white;
            cursor: pointer;
        }

        .right-menu {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .cart-btn, .login-btn {
            color: white;
            text-decoration: none;
            border: 1px solid #444;
            padding: 10px 14px;
            border-radius: 10px;
            font-weight: bold;
        }

        .cart-count {
            background: #ff7a00;
            padding: 3px 8px;
            border-radius: 50px;
            margin-left: 6px;
        }

        .hero {
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            min-height: 430px;
            background: #111;
        }

        .hero-text {
            padding: 70px 48px;
            color: white;
            background: linear-gradient(90deg, #111 40%, rgba(0,0,0,0.6));
        }

        .hero-text .small {
            color: #ff9b33;
            font-size: 22px;
            font-style: italic;
            margin-bottom: 14px;
        }

        .hero-text h1 {
            font-size: 54px;
            line-height: 1.05;
            margin: 0 0 22px;
        }

        .hero-text h1 span {
            color: #ff7a00;
        }

        .hero-text p {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 28px;
        }

        .hero-btn {
            background: #ff7a00;
            color: white;
            padding: 14px 24px;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
        }

        .hero-image {
            background: url('images/hero-burger.jpg') center/cover no-repeat;
            min-height: 430px;
        }

        .container {
            width: 92%;
            margin: 34px auto;
        }

        .section-title {
            font-size: 24px;
            margin-bottom: 18px;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 30px;
        }

        .filter-btn {
            background: white;
            color: #222;
            text-decoration: none;
            padding: 14px 22px;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
            border: 1px solid transparent;
            font-weight: bold;
        }

        .filter-btn.active {
            border-color: #ff7a00;
            color: #ff7a00;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 24px;
        }

        .product-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 18px rgba(0,0,0,0.08);
        }

        .product-card img {
            width: 100%;
            height: 185px;
            object-fit: cover;
        }

        .product-body {
            padding: 16px;
        }

        .product-title {
            font-size: 19px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .category {
            color: #777;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .desc {
            min-height: 42px;
            color: #444;
            font-size: 14px;
            line-height: 1.4;
        }

        .product-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 16px;
        }

        .price {
            color: #ff7a00;
            font-weight: bold;
            font-size: 18px;
        }

        .btn {
            background: #ff7a00;
            color: white;
            text-decoration: none;
            border-radius: 9px;
            padding: 10px 14px;
            font-weight: bold;
            font-size: 13px;
        }

        .btn-disabled {
            background: #999;
            cursor: not-allowed;
        }

        .features {
            margin: 40px 0;
            background: #111;
            color: white;
            border-radius: 18px;
            padding: 26px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .feature h3 {
            margin: 0 0 8px;
            color: #ff9b33;
        }

        .feature p {
            margin: 0;
            color: #ddd;
            font-size: 14px;
        }

        @media (max-width: 900px) {
            .navbar {
                height: auto;
                flex-direction: column;
                gap: 14px;
                padding: 18px;
            }

            .hero {
                grid-template-columns: 1fr;
            }

            .hero-text h1 {
                font-size: 38px;
            }

            .search-box input {
                width: 220px;
            }

            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="logo">GASTRO<span>NOMY</span></div>

    <div class="nav-links">
        <a href="urunler.php">MENÜ</a>
        <a href="#kategoriler">KATEGORİLER</a>
        <a href="#urunler">ÜRÜNLER</a>
    </div>

    <form class="search-box" method="get" action="urunler.php">
        <?php if ($kategori !== "Tümü"): ?>
            <input type="hidden" name="kategori" value="<?php echo htmlspecialchars($kategori); ?>">
        <?php endif; ?>
        <input type="text" name="arama" placeholder="Yemek, burger, pizza ara..." value="<?php echo htmlspecialchars($arama); ?>">
        <button type="submit">Ara</button>
    </form>

    <div class="right-menu">
        <a class="cart-btn" href="sepet.php">Sepetim <span class="cart-count"><?php echo $sepetAdet; ?></span></a>
        <a class="login-btn" href="login.php">Giriş Yap / Kaydol</a>
    </div>
</nav>

<section class="hero">
    <div class="hero-text">
        <div class="small">Lezzetin En Doğal Hali</div>
        <h1>Gurme Lezzetler,<br><span>Kapında!</span></h1>
        <p>En taze malzemelerle hazırlanmış özel tariflerimizi keşfedin ve kolayca siparişinizi oluşturun.</p>
        <a class="hero-btn" href="#urunler">Sipariş Ver</a>
    </div>
    <div class="hero-image"></div>
</section>

<div class="container">
    <h2 id="kategoriler" class="section-title">Kategoriler</h2>

    <div class="filters">
        <a class="filter-btn <?php echo $kategori === 'Tümü' ? 'active' : ''; ?>" href="urunler.php">Tümü</a>

        <?php if ($kategorilerResult && $kategorilerResult->num_rows > 0): ?>
            <?php while ($kat = $kategorilerResult->fetch_assoc()): ?>
                <a class="filter-btn <?php echo $kategori === $kat["kategori"] ? 'active' : ''; ?>"
                   href="urunler.php?kategori=<?php echo urlencode($kat["kategori"]); ?>">
                    <?php echo htmlspecialchars($kat["kategori"]); ?>
                </a>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <h2 id="urunler" class="section-title">Tüm Menüler</h2>

    <div class="product-grid">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
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
                        <div class="category"><?php echo htmlspecialchars($row["kategori"]); ?></div>
                        <div class="desc"><?php echo htmlspecialchars($row["aciklama"]); ?></div>

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
            <p>Sepete ekle, bilgilerini gir ve siparişini tamamla.</p>
        </div>
        <div class="feature">
            <h3>Müşteri Desteği</h3>
            <p>Sipariş durumun admin panelinden takip edilir.</p>
        </div>
    </div>
</div>

</body>
</html>