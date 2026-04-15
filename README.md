# Restoran Sipariş Sistemi

Bu proje, PHP ve MySQL kullanılarak geliştirilmiş bir restoran sipariş sistemi uygulamasıdır.  
Kullanıcılar ürünleri görüntüleyebilir, sepete ekleyebilir ve sipariş oluşturabilir.  
Admin paneli üzerinden sipariş ve sistem yönetimi yapılabilmektedir.

## Özellikler

- Ürün listeleme
- Sepete ürün ekleme
- Sepetten ürün silme
- Sepet güncelleme
- Sipariş oluşturma
- Sipariş kaydetme
- Sipariş tamamlama
- Admin giriş sistemi
- Admin paneli

## Kullanılan Teknolojiler

- PHP
- MySQL
- HTML
- CSS
- JavaScript
- XAMPP

## Kurulum

1. Projeyi indirin veya klonlayın:
  -git clone https://github.com/efecaankara/restoran-siparis-sistemi.git
2. Proje klasörünü xampp/htdocs/ içine atın.
3. XAMPP üzerinden:
  -Apache başlat
  -MySQL başlat
4. Tarayıcıdan phpMyAdmin açın:
  -http://localhost/phpmyadmin
5. Yeni bir veritabanı oluşturun (örnek: restoran_db)
6. Proje klasöründeki restoran_db.sql dosyasını içe aktarın:
  -phpMyAdmin → Import
  -restoran_db.sql seç → Go
7. db.php dosyasını kontrol edin:
  $host = "localhost";
  $user = "root";
  $pass = "";
  $dbname = "restoran_db";
8. Projeyi çalıştırın:
  -http://localhost/restoran

Admin giriş bilgisi:
-Kullanıcı adı:admin 
-Şifre:123456

Veritabanı:
Bu projeye ait tüm tablo yapıları ve örnek veriler restoran_db.sql dosyasında bulunmaktadır.

Notlar
-XAMPP açık değilse proje çalışmaz
-Veritabanı import edilmeden sistem çalışmaz
-Gerekirse db.php dosyasındaki veritabanı adı değiştirilebilir
