<?php
include "db.php";

$sql = "SELECT * FROM urunler WHERE stok_durumu = 1 ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Menü</title>

<style>
body {
    font-family: Arial;
    background: #f5f5f5;
}

.container {
    width: 90%;
    margin: auto;
}

.card {
    background: white;
    width: 250px;
    display: inline-block;
    margin: 15px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.card-body {
    padding: 10px;
}

.card-title {
    font-size: 18px;
    font-weight: bold;
}

.price {
    color: green;
    font-weight: bold;
}

button {
    background: orange;
    border: none;
    padding: 8px;
    width: 100%;
    cursor: pointer;
    color: white;
}
</style>

</head>
<body>

<div class="container">
<h1>Menü</h1>

<?php
include "db.php";
$result = $conn->query("SELECT * FROM urunler WHERE stok_durumu=1");

while($row = $result->fetch_assoc()):
?>

<div class="card">
    <img src="<?php echo $row["gorsel_yolu"]; ?>">
    
    <div class="card-body">
        <div class="card-title"><?php echo $row["urun_adi"]; ?></div>
        <div><?php echo $row["aciklama"]; ?></div>
        <div class="price"><?php echo $row["fiyat"]; ?> TL</div>
        
        <button>Sepete Ekle</button>
    </div>
</div>

<?php endwhile; ?>

</div>

</body>
</html>