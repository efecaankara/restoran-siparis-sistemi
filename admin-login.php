<?php
session_start();
include "db.php";

$hata = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($username === "" || $password === "") {
        $hata = "Kullanıcı adı ve şifre boş bırakılamaz.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();

            if (password_verify($password, $admin["password"])) {
                $_SESSION["admin_id"] = $admin["id"];
                $_SESSION["admin_username"] = $admin["username"];

                header("Location: admin-panel.php");
                exit;
            } else {
                $hata = "Şifre hatalı.";
            }
        } else {
            $hata = "Kullanıcı bulunamadı.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Giriş</title>
<link rel="stylesheet" href="assets/css/site.css">
</head>

<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="logo">GASTRO<span>NOMY</span></div>

        <h1>Admin Giriş</h1>

        <?php if ($hata): ?>
            <div class="error">
                <?php echo htmlspecialchars($hata); ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="username" placeholder="Kullanıcı Adı" required>

            <input type="password" name="password" placeholder="Şifre" required>

            <button type="submit">Giriş Yap</button>
        </form>

        <div class="link">
            <a href="urunler.php">Siteye Dön</a>
        </div>
    </div>
</div>

</body>
</html>