# Restoran Sipariş Sistemi

Bu proje, PHP ve MySQL kullanılarak geliştirilmiş bir **restoran sipariş sistemi** uygulamasıdır.  
Kullanıcılar ürünleri görüntüleyebilir, sepete ekleyebilir ve sipariş oluşturabilir.  
Ayrıca yönetici paneli üzerinden sipariş ve sistem yönetimi işlemleri yapılabilir.

## Özellikler

- Ürün listeleme
- Sepete ürün ekleme
- Sepetten ürün silme
- Sepet güncelleme
- Sipariş oluşturma
- Sipariş kaydetme
- Sipariş tamamlama
- Admin giriş sistemi
- Admin paneli yönetimi
- Stok / sipariş takibi mantığı

## Kullanılan Teknolojiler

- PHP
- MySQL
- HTML
- CSS
- JavaScript
- XAMPP

## Proje Yapısı

Projede bulunan bazı temel dosyalar:

- `admin-login.php` → Yönetici giriş sayfası
- `admin-panel.php` → Yönetici paneli
- `db.php` → Veritabanı bağlantı dosyası
- `sepet.php` → Sepet görüntüleme sayfası
- `sepete-ekle.php` → Sepete ürün ekleme işlemi
- `sepetten-sil.php` → Sepetten ürün silme işlemi
- `sepet-guncelle.php` → Sepet güncelleme işlemi
- `siparis-olustur.php` → Sipariş oluşturma işlemi
- `siparis-kaydet.php` → Sipariş verisini kaydetme işlemi
- `siparis-tamamla.php` → Siparişi tamamlama işlemi

## Kurulum Adımları

1. Bu projeyi bilgisayarınıza indirin veya klonlayın.
2. Proje klasörünü `xampp/htdocs/` klasörünün içine yerleştirin.
3. XAMPP üzerinden **Apache** ve **MySQL** servislerini başlatın.
4. Tarayıcıdan `phpMyAdmin` sayfasını açın.
5. Yeni bir veritabanı oluşturun.
6. Projede bulunan `database.sql` dosyasını içe aktarın.
7. `db.php` dosyasındaki veritabanı bağlantı ayarlarını kontrol edin.
8. Tarayıcıdan projeyi açın.

Örnek çalışma adresi:

```bash
http://localhost/restoran
