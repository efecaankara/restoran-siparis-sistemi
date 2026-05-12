<?php
session_start();
include "db.php";

$hata = "";
$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ad_soyad = trim($_POST["ad_soyad"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefon = trim($_POST["telefon"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($ad_soyad === "" || $email === "" || $password === "") {
        $hata = "Ad soyad, e-posta ve şifre zorunludur.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (ad_soyad, email, telefon, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $ad_soyad, $email, $telefon, $hashedPassword);

        if ($stmt->execute()) {
            $mesaj = "Kayıt başarılı. Giriş yapabilirsiniz.";
        } else {
            $hata = "Bu e-posta zaten kayıtlı olabilir.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kayıt Ol</title>
<link rel="stylesheet" href="assets/css/site.css">
</style>
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="logo">GASTRO<span>NOMY</span></div>
        <h1>Kayıt Ol</h1>

        <?php if ($hata): ?>
            <div class="msg error"><?php echo htmlspecialchars($hata); ?></div>
        <?php endif; ?>

        <?php if ($mesaj): ?>
            <div class="msg success"><?php echo htmlspecialchars($mesaj); ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="ad_soyad" placeholder="Ad Soyad" required>
            <input type="email" name="email" placeholder="E-posta" required>
            <input type="text" name="telefon" placeholder="Telefon">
            <input type="password" name="password" placeholder="Şifre" required>

            <button type="submit">Kayıt Ol</button>
        </form>

        <div class="link">
            Zaten hesabınız var mı? <a href="login.php">Giriş Yap</a>
        </div>

        <div class="link">
            <a href="urunler.php">Menüye Dön</a>
        </div>
    </div>
</div>

</body>
</html>