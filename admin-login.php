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
    <title>Admin Giriş</title>
</head>
<body>
    <h1>Admin Giriş</h1>

    <?php if ($hata): ?>
        <p style="color:red;"><?php echo htmlspecialchars($hata); ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Kullanıcı Adı:</label><br>
        <input type="text" name="username"><br><br>

        <label>Şifre:</label><br>
        <input type="password" name="password"><br><br>

        <button type="submit">Giriş Yap</button>
    </form>
</body>
</html>