<?php
session_start();
include "db.php";

$hata = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($email === "" || $password === "") {
        $hata = "E-posta ve şifre zorunludur.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if ((int)$user["durum"] === 0) {
                $hata = "Hesabınız pasif durumdadır.";
            } elseif (password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["ad_soyad"];
                $_SESSION["user_email"] = $user["email"];
                $_SESSION["user_phone"] = $user["telefon"];

                header("Location: urunler.php");
                exit;
            } else {
                $hata = "Şifre hatalı.";
            }
        } else {
            $hata = "Bu e-posta ile kayıtlı kullanıcı bulunamadı.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Giriş Yap</title>
<link rel="stylesheet" href="assets/css/site.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="logo">GASTRO<span>NOMY</span></div>
        <h1>Giriş Yap</h1>

        <?php if ($hata): ?>
            <div class="error"><?php echo htmlspecialchars($hata); ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="email" name="email" placeholder="E-posta" required>
            <input type="password" name="password" placeholder="Şifre" required>

            <button type="submit">Giriş Yap</button>
        </form>

        <div class="link">
            Hesabınız yok mu? <a href="register.php">Kayıt Ol</a>
        </div>

        <div class="link">
            <a href="urunler.php">Menüye Dön</a>
        </div>
    </div>
</div>

</body>
</html>