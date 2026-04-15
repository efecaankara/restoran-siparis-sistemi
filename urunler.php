<?php
include "db.php";
session_start();

$kategori = $_GET["kategori"] ?? "Tümü";

$kategorilerResult = $conn->query("SELECT DISTINCT kategori FROM urunler ORDER BY kategori ASC");

if ($kategori !== "Tümü") {
    $stmt = $conn->prepare("SELECT * FROM urunler WHERE kategori = ? ORDER BY id DESC");
    $stmt->bind_param("s", $kategori);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM urunler ORDER BY id DESC";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Menü</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 30px auto;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .cart-link {
            text-decoration: none;
            background: #ff7a00;
            color: white;
            padding: 10px 14px;
            border-radius: 8px;
        }

        .filters {
            margin: 20px 0;
        }

        .filter-btn {
            display: inline-block;
            padding: 8px 14px;
            margin: 4px;
            text-decoration: none;
            background: #ddd;
            color: #222;
            border-radius: 8px;
        }

        .filter-btn.active {
            background: #ff7a00;
            color: white;
        }

        .cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            width: 260px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }

        .card img {
            width: 100%;
            height: 170px;
            object-fit: cover;
        }

        .card-body {
            padding: 14px;
        }

        .card-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .price {
            color: green;
            font-weight: bold;
            margin: 8px 0;
        }

        .btn {
            display: inline-block;
            background: #ff7a00;
            color: white;
            text-decoration: none;
            padding: 10px 14px;
            border-radius: 8px;
        }

        .muted {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="topbar">
            <h1>Menü</h1>
            <a class="cart-link" href="sepet.php">
                Sepetim
                (<?php echo isset($_SESSION["cart"]) ? array_sum(array_column($_SESSION["cart"], "adet")) : 0; ?>)
            </a>
        </div>

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

        <div class="cards">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card">
                        <?php if (!empty($row["gorsel_yolu"])): ?>
                            <img src="<?php echo htmlspecialchars($row["gorsel_yolu"]); ?>" alt="<?php echo htmlspecialchars($row["urun_adi"]); ?>">
                        <?php endif; ?>

                        <div class="card-body">
                            <div class="card-title"><?php echo htmlspecialchars($row["urun_adi"]); ?></div>
                            <div class="muted"><?php echo htmlspecialchars($row["kategori"]); ?></div>
                            <p><?php echo htmlspecialchars($row["aciklama"]); ?></p>
                            <div class="price"><?php echo number_format($row["fiyat"], 2); ?> TL</div>
                            <p class="muted">Kalan Stok: <?php echo max(0, $kalanStok); ?></p>
                        
                            <?php
                                $sepettekiAdet = isset($_SESSION["cart"][$row["id"]]) ? (int)$_SESSION["cart"][$row["id"]]["adet"] : 0;
                                $kalanStok = (int)$row["stok_miktari"] - $sepettekiAdet;
                            ?>

                            <?php if ($kalanStok > 0): ?>
                                <a class="btn" href="sepete-ekle.php?id=<?php echo $row["id"]; ?>">Sepete Ekle</a>
                            <?php else: ?>
                                <span class="btn" style="background:#999; cursor:not-allowed;">Tükendi</span>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Bu kategoride ürün bulunmuyor.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>