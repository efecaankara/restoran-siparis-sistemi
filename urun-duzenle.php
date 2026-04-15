<?php
include "includes/auth.php";
include "db.php";

$id = $_GET["id"];

$stmt = $conn->prepare("SELECT * FROM urunler WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$urun = $result->fetch_assoc();

if($_SERVER["REQUEST_METHOD"] === "POST") {
    $urun_adi = $_POST["urun_adi"];
    $fiyat = $_POST["fiyat"];

    $stmt = $conn->prepare("UPDATE urunler SET urun_adi=?, fiyat=? WHERE id=?");
    $stmt->bind_param("sdi", $urun_adi, $fiyat, $id);
    $stmt->execute();

    header("Location: urunleri-yonet.php");
    exit;
}
?>

<h1>Ürün Düzenle</h1>

<form method="post">
    Ürün Adı:<br>
    <input type="text" name="urun_adi" value="<?php echo $urun["urun_adi"]; ?>"><br><br>

    Fiyat:<br>
    <input type="text" name="fiyat" value="<?php echo $urun["fiyat"]; ?>"><br><br>

    <button type="submit">Güncelle</button>
</form>