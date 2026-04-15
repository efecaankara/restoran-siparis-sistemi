<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: /restoran/admin-login.php");
    exit;
}
?>