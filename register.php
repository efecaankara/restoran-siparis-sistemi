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
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f5f5f5;
}

.auth-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 30px;
}

.auth-card {
    width: 100%;
    max-width: 430px;
    background: white;
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
}

.logo {
    font-size: 28px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 25px;
}

.logo span {
    color: #ff7a00;
}

h1 {
    text-align: center;
    margin-bottom: 22px;
}

input {
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    border: 1px solid #ddd;
    margin-bottom: 14px;
    font-size: 14px;
}

button {
    width: 100%;
    background: #ff7a00;
    border: none;
    color: white;
    padding: 15px;
    border-radius: 12px;
    font-weight: bold;
    cursor: pointer;
    font-size: 16px;
}

.link {
    text-align: center;
    margin-top: 18px;
}

.link a {
    color: #ff7a00;
    text-decoration: none;
    font-weight: bold;
}

.msg {
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 14px;
}

.error {
    background: #ffe1e1;
    color: #b00000;
}

.success {
    background: #e2ffe7;
    color: #0f7a22;
}
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