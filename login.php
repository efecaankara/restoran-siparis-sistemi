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

            if (password_verify($password, $user["password"])) {
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

.error {
    background: #ffe1e1;
    color: #b00000;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 14px;
}
</style>
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