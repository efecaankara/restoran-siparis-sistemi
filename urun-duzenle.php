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
    $stmt->bind_param("sssssiii", $urun_adi, $kategori, $aciklama, $fiyat, $gorsel_yolu, $stok_durumu, $stok_miktari, $id);
    $stmt->execute();

    header("Location: urunleri-yonet.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürün Düzenle</title>
</head>
<body>
    <h1>Ürün Düzenle</h1>

    <form method="post">
        <label>Ürün Adı:</label><br>
        <input type="text" name="urun_adi" value="<?php echo htmlspecialchars($urun["urun_adi"]); ?>"><br><br>

        <label>Kategori:</label><br>
        <input type="text" name="kategori" value="<?php echo htmlspecialchars($urun["kategori"]); ?>"><br><br>

        <label>Açıklama:</label><br>
        <textarea name="aciklama"><?php echo htmlspecialchars($urun["aciklama"]); ?></textarea><br><br>

        <label>Fiyat:</label><br>
        <input type="text" name="fiyat" value="<?php echo htmlspecialchars($urun["fiyat"]); ?>"><br><br>

        <label>Görsel Yolu:</label><br>
        <input type="text" name="gorsel_yolu" value="<?php echo htmlspecialchars($urun["gorsel_yolu"]); ?>"><br><br>

        <label>Stok Miktarı:</label><br>
        <input type="number" name="stok_miktari" min="0" value="<?php echo (int)$urun["stok_miktari"]; ?>"><br><br>

        <label>
            <input type="checkbox" name="stok_durumu" <?php echo $urun["stok_durumu"] ? "checked" : ""; ?>>
            Stokta var
        </label><br><br>

        <button type="submit">Güncelle</button>
    </form>
</body>
</html>