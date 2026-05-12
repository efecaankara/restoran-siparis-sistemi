<?php
include "includes/auth.php";
include "db.php";

$mesaj = "";
$hata = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $kategori_adi = trim($_POST["kategori_adi"] ?? "");

    if ($kategori_adi === "") {
        $hata = "Kategori adı boş bırakılamaz.";
    } else {
        $stmt = $conn->prepare("
            INSERT INTO urunler (urun_adi, kategori, aciklama, fiyat, gorsel_yolu, stok_durumu, stok_miktari)
            VALUES (?, ?, '', 0, '', 0, 0)
        ");

        $urun_adi = "Kategori: " . $kategori_adi;
        $stmt->bind_param("ss", $urun_adi, $kategori_adi);

        if ($stmt->execute()) {
            $mesaj = "Kategori eklendi. Bu kategoriye ürün ekleyebilirsiniz.";
        } else {
            $hata = "Kategori eklenirken hata oluştu.";
        }
    }
}

$result = $conn->query("
    SELECT kategori, COUNT(*) AS urun_sayisi
    FROM urunler
    WHERE kategori IS NOT NULL AND kategori != ''
    GROUP BY kategori
    ORDER BY kategori ASC
");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kategoriler</title>
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
        <a href="urunleri-yonet.php">🍔 Ürünler</a>
        <a class="active" href="kategoriler-admin.php">📂 Kategoriler</a>
        <a href="kullanicilar-admin.php">👥 Kullanıcılar</a>
        <a href="masa-yonetimi.php">🍽️ Masa Yönetimi</a>
        <a href="urunler.php">🌐 Siteye Git</a>
        <a href="logout.php">🚪 Çıkış Yap</a>
    </div>
</div>

<div class="main">
    <div class="topbar">
        <h1>Kategoriler</h1>
        <a class="back-link" href="admin-panel.php">Panele Dön</a>
    </div>

    <?php if ($mesaj): ?>
        <div class="alert success-alert"><?php echo htmlspecialchars($mesaj); ?></div>
    <?php endif; ?>

    <?php if ($hata): ?>
        <div class="alert error-alert"><?php echo htmlspecialchars($hata); ?></div>
    <?php endif; ?>

    <div class="form-card" style="margin-bottom:24px;">
        <h2 style="margin-bottom:18px;">Yeni Kategori Ekle</h2>

        <form method="post">
            <div class="form-grid">
                <div class="form-group">
                    <label>Kategori Adı</label>
                    <input type="text" name="kategori_adi" placeholder="Örn: Pizza">
                </div>
            </div>

            <button type="submit">Kategori Ekle</button>
        </form>
    </div>

    <div class="table-box">
        <h2>Kategori Listesi</h2>

        <table>
            <tr>
                <th>Kategori</th>
                <th>Ürün Sayısı</th>
                <th>İşlem</th>
            </tr>

            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["kategori"]); ?></td>
                        <td><?php echo (int)$row["urun_sayisi"]; ?></td>
                        <td>
                            <a 
                                class="delete-btn"
                                href="kategori-sil.php?kategori=<?php echo urlencode($row["kategori"]); ?>"
                                onclick="return confirm('Bu kategori silinsin mi? Ürünler kategorisiz kalacak.')"
                            >
                                Sil
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Kategori bulunamadı.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

</body>
</html>