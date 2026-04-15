<?php
session_start();

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id > 0 && isset($_SESSION["cart"][$id])) {
    unset($_SESSION["cart"][$id]);
}

header("Location: sepet.php");
exit;
?>