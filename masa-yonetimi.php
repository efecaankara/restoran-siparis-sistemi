<?php
include "includes/auth.php";
include "db.php";

$mesaj = "";
$hata = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $masa_adi = trim($_POST["masa_adi"] ?? "");

    if ($masa_adi === "") {
        $hata = "Masa adı boş bırakılamaz.";
    } else {
        $stmt = $conn->prepare("INSERT INTO masalar (masa_adi) VALUES (?)");
        $stmt->bind_param("s", $masa_adi);

        if ($stmt->execute()) {
            $mesaj = "Masa başarıyla eklendi.";
        } else {
            $hata = "Masa eklenirken hata oluştu.";
        }
    }
}

$result = $conn->query("SELECT * FROM masalar ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Masa Yönetimi</title>
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
        <a href="kategoriler-admin.php">📂 Kategoriler</a>
        <a href="kullanicilar-admin.php">👥 Kullanıcılar</a>
        <a class="active" href="masa-yonetimi.php">🍽️ Masa Yönetimi</a>
        <a href="urunler.php">🌐 Siteye Git</a>
        <a href="logout.php">🚪 Çıkış Yap</a>
    </div>
</div>

<div class="main">
    <div class="topbar">
        <h1>Masa Yönetimi</h1>
        <a class="back-link" href="admin-panel.php">Panele Dön</a>
    </div>

    <?php if ($mesaj): ?>
        <div class="alert success-alert"><?php echo htmlspecialchars($mesaj); ?></div>
    <?php endif; ?>

    <?php if ($hata): ?>
        <div class="alert error-alert"><?php echo htmlspecialchars($hata); ?></div>
    <?php endif; ?>

    <div class="form-card" style="margin-bottom:24px;">
        <h2 style="margin-bottom:18px;">Yeni Masa Ekle</h2>

        <form method="post">
            <div class="form-grid">
                <div class="form-group">
                    <label>Masa Adı</label>
                    <input type="text" name="masa_adi" placeholder="Örn: Masa 01">
                </div>
            </div>

            <button type="submit">Masa Ekle</button>
        </form>
    </div>

    <div class="table-box">
        <h2>Masa Listesi</h2>

        <div class="masa-grid">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($masa = $result->fetch_assoc()): ?>

                    <div class="masa-card <?php echo $masa["durum"] === "Dolu" ? "masa-dolu" : "masa-bos"; ?>">
                        <h3><?php echo htmlspecialchars($masa["masa_adi"]); ?></h3>

                        <p><?php echo htmlspecialchars($masa["durum"]); ?></p>

                        <a 
                            class="btn <?php echo $masa["durum"] === "Dolu" ? "edit-btn" : "passive-btn"; ?>"
                            href="masa-durum.php?id=<?php echo (int)$masa["id"]; ?>"
                            onclick="return confirm('Masa durumu değiştirilsin mi?')"
                        >
                            <?php echo $masa["durum"] === "Dolu" ? "Boş Yap" : "Dolu Yap"; ?>
                        </a>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty">Henüz masa eklenmemiş.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>