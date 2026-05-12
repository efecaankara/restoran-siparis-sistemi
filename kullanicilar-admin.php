<?php
include "includes/auth.php";
include "db.php";

$result = $conn->query("
    SELECT 
        id,
        ad_soyad,
        email,
        telefon,
        created_at,
        durum
    FROM users
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kullanıcılar</title>

<link rel="stylesheet" href="assets/css/admin.css">
</head>

<body>

<div class="sidebar">

    <div class="logo">
        GASTRO<span>NOMY</span>
    </div>

    <div class="admin-info">
        <strong><?php echo htmlspecialchars($_SESSION["admin_username"]); ?></strong>
        <p>Yönetici Paneli</p>
    </div>

    <div class="menu">
        <a href="admin-panel.php">📊 Yönetim Paneli</a>
        <a href="siparisler-admin.php">🛒 Siparişler</a>
        <a href="urunleri-yonet.php">🍔 Ürünler</a>
        <a href="kategoriler-admin.php">📂 Kategoriler</a>
        <a class="active" href="kullanicilar-admin.php">👥 Kullanıcılar</a>
        <a href="masa-yonetimi.php">🍽️ Masa Yönetimi</a>
        <a href="urunler.php">🌐 Siteye Git</a>
        <a href="logout.php">🚪 Çıkış Yap</a>
    </div>

</div>

<div class="main">

    <div class="topbar">
        <h1>Kullanıcılar</h1>

        <a class="back-link" href="admin-panel.php">
            Panele Dön
        </a>
    </div>

    <div class="table-box">

        <h2>Kayıtlı Kullanıcılar</h2>

        <table>

            <tr>
                <th>ID</th>
                <th>Ad Soyad</th>
                <th>E-posta</th>
                <th>Telefon</th>
                <th>Kayıt Tarihi</th>
                <th>Durum</th>
                <th>İşlem</th>
            </tr>

            <?php if ($result && $result->num_rows > 0): ?>

                <?php while ($user = $result->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo (int)$user["id"]; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($user["ad_soyad"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($user["email"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($user["telefon"] ?: "-"); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($user["created_at"]); ?>
                        </td>

                        <td>
                            <?php if ((int)$user["durum"] === 1): ?>
                                <span class="badge active-badge">Aktif</span>
                            <?php else: ?>
                                <span class="badge passive-badge">Pasif</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a 
                                class="btn <?php echo (int)$user["durum"] === 1 ? "passive-btn" : "edit-btn"; ?>"
                                href="kullanici-sil.php?id=<?php echo (int)$user["id"]; ?>"
                                onclick="return confirm('Kullanıcı durumu değiştirilsin mi?')">
                                <?php echo (int)$user["durum"] === 1 ? "Pasif Yap" : "Aktif Yap"; ?>
                            </a>
                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="6">
                        Kayıtlı kullanıcı bulunamadı.
                    </td>
                </tr>

            <?php endif; ?>

        </table>

    </div>

</div>

</body>
</html>