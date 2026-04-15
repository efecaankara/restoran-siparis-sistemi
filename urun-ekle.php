<?php
include "includes/auth.php";
include "db.php";

$mesaj = "";
$hata = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $urun_adi = trim($_POST["urun_adi"] ?? "");
    $kategori = trim($_POST["kategori"] ?? "");
    $aciklama = trim($_POST["aciklama"] ?? "");
    $fiyat = trim($_POST["fiyat"] ?? "");
    $gorsel_yolu = trim($_POST["gorsel_yolu"] ?? "");
    $stok_durumu = isset($_POST["stok_durumu"]) ? 1 : 0;

    if ($urun_adi === "" || $fiyat === "") {
        $hata = "Ürün adı ve fiyat zorunludur.";
    } elseif (!is_numeric($fiyat)) {
        $hata = "Fiyat sayısal olmalıdır.";
    } else {
        $stmt = $conn->prepare("INSERT INTO urunler (urun_adi, kategori, aciklama, fiyat, gorsel_yolu, stok_durumu) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $urun_adi, $kategori, $aciklama, $fiyat, $gorsel_yolu, $stok_durumu);

        if ($stmt->execute()) {
            $mesaj = "Ürün başarıyla eklendi.";
        } else {
            $hata = "Ürün eklenirken hata oluştu: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürün Ekle</title>
</head>
<body>
    <h1>Ürün Ekle</h1>

    <p><a href="admin-panel.php">Panele Dön</a></p>

    <?php if ($mesaj): ?>
        <p style="color:green;"><?php echo htmlspecialchars($mesaj); ?></p>
    <?php endif; ?>

    <?php if ($hata): ?>
        <p style="color:red;"><?php echo htmlspecialchars($hata); ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Ürün Adı:</label><br>
        <input type="text" name="urun_adi"><br><br>

        <label>Kategori:</label><br>
        <input type="text" name="kategori"><br><br>

        <label>Açıklama:</label><br>
        <textarea name="aciklama"></textarea><br><br>

        <label>Fiyat:</label><br>
        <input type="text" name="fiyat"><br><br>

        <label>Görsel Yolu:</label><br>
        <input type="text" name="gorsel_yolu"><br><br>

        <label>
            <input type="checkbox" name="stok_durumu" checked> Stokta var
        </label><br><br>

        <button type="submit">Ürün Ekle</button>
    </form>
</body>
</html>