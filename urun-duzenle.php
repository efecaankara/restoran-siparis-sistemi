<?php
include "includes/auth.php";
include "db.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

$stmt = $conn->prepare("SELECT * FROM urunler WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$urun = $result->fetch_assoc();

if (!$urun) {
    die("Ürün bulunamadı.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $urun_adi = trim($_POST["urun_adi"] ?? "");
    $kategori = trim($_POST["kategori"] ?? "");
    $aciklama = trim($_POST["aciklama"] ?? "");
    $fiyat = trim($_POST["fiyat"] ?? "");
    $gorsel_yolu = trim($_POST["gorsel_yolu"] ?? "");
    $stok_miktari = trim($_POST["stok_miktari"] ?? "0");
    $stok_durumu = isset($_POST["stok_durumu"]) ? 1 : 0;

    if ($urun_adi === "" || $fiyat === "") {
        die("Ürün adı ve fiyat zorunludur.");
    }

    $stmt = $conn->prepare("
        UPDATE urunler 
        SET urun_adi = ?, kategori = ?, aciklama = ?, fiyat = ?, gorsel_yolu = ?, stok_durumu = ?, stok_miktari = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "sssssiii",
        $urun_adi,
        $kategori,
        $aciklama,
        $fiyat,
        $gorsel_yolu,
        $stok_durumu,
        $stok_miktari,
        $id
    );

    $stmt->execute();

    header("Location: urunleri-yonet.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ürün Düzenle</title>
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
        <h1>Ürün Düzenle</h1>
        <a class="back-link" href="urunleri-yonet.php">Ürünlere Dön</a>
    </div>

    <div class="form-card">

        <form method="post">

            <div class="form-grid">

                <div class="form-group">
                    <label>Ürün Adı</label>
                    <input 
                        type="text" 
                        name="urun_adi" 
                        value="<?php echo htmlspecialchars($urun["urun_adi"]); ?>"
                    >
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <input 
                        type="text" 
                        name="kategori" 
                        value="<?php echo htmlspecialchars($urun["kategori"]); ?>"
                    >
                </div>

                <div class="form-group full">
                    <label>Açıklama</label>
                    <textarea name="aciklama" rows="4"><?php echo htmlspecialchars($urun["aciklama"]); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Fiyat</label>
                    <input 
                        type="text" 
                        name="fiyat" 
                        value="<?php echo htmlspecialchars($urun["fiyat"]); ?>"
                    >
                </div>

                <div class="form-group">
                    <label>Stok Miktarı</label>
                    <input 
                        type="number" 
                        name="stok_miktari" 
                        min="0" 
                        value="<?php echo (int)$urun["stok_miktari"]; ?>"
                    >
                </div>

                <div class="form-group full">
                    <label>Görsel Yolu</label>
                    <input 
                        type="text" 
                        name="gorsel_yolu" 
                        value="<?php echo htmlspecialchars($urun["gorsel_yolu"]); ?>"
                    >
                    <small>Örnek: images/double-burger.png</small>
                </div>

                <?php if (!empty($urun["gorsel_yolu"])): ?>
                    <div class="form-group full">
                        <label>Mevcut Görsel</label>
                        <img 
                            src="<?php echo htmlspecialchars($urun["gorsel_yolu"]); ?>" 
                            alt="<?php echo htmlspecialchars($urun["urun_adi"]); ?>"
                            style="max-width:260px; border-radius:16px;"
                        >
                    </div>
                <?php endif; ?>

                <div class="form-group full">
                    <label class="checkbox-row">
                        <input 
                            type="checkbox" 
                            name="stok_durumu" 
                            <?php echo $urun["stok_durumu"] ? "checked" : ""; ?>
                        >
                        Ürün aktif / stokta görünsün
                    </label>
                </div>

            </div>

            <button type="submit">Güncelle</button>

        </form>

    </div>

</div>

</body>
</html>