<?php
include "includes/auth.php";
include "db.php";

$result = $conn->query("SELECT * FROM urunler ORDER BY id DESC");
?>

<h1>Ürün Yönetimi</h1>
<a href="admin-panel.php">Panele Dön</a>

<?php while($row = $result->fetch_assoc()): ?>
    <div style="border:1px solid #ccc; margin:10px; padding:10px;">
        <b><?php echo $row["urun_adi"]; ?></b> - <?php echo $row["fiyat"]; ?> TL
        
        <br><br>

        <a href="urun-duzenle.php?id=<?php echo $row["id"]; ?>">Düzenle</a> |
        <a href="urun-sil.php?id=<?php echo $row["id"]; ?>" onclick="return confirm('Silinsin mi?')">Sil</a>
    </div>
<?php endwhile; ?>