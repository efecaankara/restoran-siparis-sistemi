<?php
include "includes/auth.php";
include "db.php";

$id = $_GET["id"] ?? 0;

$stmt = $conn->prepare("DELETE FROM urunler WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: urunleri-yonet.php");
exit;