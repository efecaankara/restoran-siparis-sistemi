-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 15 Nis 2026, 20:15:17
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `restoran_db`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$Y9DIXrdFlSSA.3bIKMeCguxcdlyASoJLtSZf2qQszjKqQVSwojBWG', '2026-04-15 06:44:44');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparisler`
--

CREATE TABLE `siparisler` (
  `id` int(11) NOT NULL,
  `musteri_ad_soyad` varchar(100) DEFAULT NULL,
  `musteri_tel` varchar(20) DEFAULT NULL,
  `adres` text DEFAULT NULL,
  `masa_no` varchar(10) DEFAULT NULL,
  `toplam_tutar` decimal(10,2) DEFAULT NULL,
  `siparis_tarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  `durum` enum('Beklemede','Hazırlanıyor','Tamamlandı','İptal') DEFAULT 'Beklemede'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `siparisler`
--

INSERT INTO `siparisler` (`id`, `musteri_ad_soyad`, `musteri_tel`, `adres`, `masa_no`, `toplam_tutar`, `siparis_tarihi`, `durum`) VALUES
(1, 'Efecan Kara', '05528463734', 'Fatih Mahallesi Ünverdi Caddesi NO:70 Daire:4 Esenyurt\\İstanbul', NULL, 8000.00, '2026-04-15 06:52:52', 'Tamamlandı'),
(2, 'Efecan Kara', '05528463734', 'Fatih Mahallesi Ünverdi Caddesi NO:70 Daire:4 Esenyurt\\İstanbul', NULL, 8000.00, '2026-04-15 07:14:19', 'Tamamlandı'),
(3, 'Efecan Kara', '05528463734', 'Fatih Mahallesi Ünverdi Caddesi NO:70 Daire:4 Esenyurt\\İstanbul', NULL, 8000.00, '2026-04-15 07:22:20', 'Tamamlandı'),
(4, 'Efecan KARA', '05528463734', 'Fatih Mahallesi Ünverdi Caddesi NO:70 Daire:4 Esenyurt\\İstanbul', NULL, 330.00, '2026-04-15 10:22:50', 'Tamamlandı'),
(5, 'kola stok bıtıerme', '05528463734', 'Fatih Mahallesi Ünverdi Caddesi NO:70 Daire:4 Esenyurt\\İstanbul', NULL, 80.00, '2026-04-15 10:52:46', 'Tamamlandı');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparis_detay`
--

CREATE TABLE `siparis_detay` (
  `id` int(11) NOT NULL,
  `siparis_id` int(11) DEFAULT NULL,
  `urun_id` int(11) DEFAULT NULL,
  `adet` int(11) DEFAULT NULL,
  `birim_fiyat` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `siparis_detay`
--

INSERT INTO `siparis_detay` (`id`, `siparis_id`, `urun_id`, `adet`, `birim_fiyat`) VALUES
(1, 1, 1, 5, 120.00),
(2, 1, 2, 6, 150.00),
(3, 1, 4, 10, 40.00),
(4, 1, 5, 50, 120.00),
(5, 1, 3, 2, 50.00),
(6, 2, 1, 5, 120.00),
(7, 2, 2, 6, 150.00),
(8, 2, 4, 10, 40.00),
(9, 2, 5, 50, 120.00),
(10, 2, 3, 2, 50.00),
(11, 3, 1, 5, 120.00),
(12, 3, 2, 6, 150.00),
(13, 3, 4, 10, 40.00),
(14, 3, 5, 50, 120.00),
(15, 3, 3, 2, 50.00),
(16, 4, 5, 2, 120.00),
(17, 4, 4, 1, 40.00),
(18, 4, 3, 1, 50.00),
(19, 5, 4, 2, 40.00);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `urunler`
--

CREATE TABLE `urunler` (
  `id` int(11) NOT NULL,
  `urun_adi` varchar(100) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `aciklama` text DEFAULT NULL,
  `fiyat` decimal(10,2) NOT NULL,
  `gorsel_yolu` varchar(255) DEFAULT NULL,
  `stok_durumu` tinyint(1) DEFAULT 1,
  `stok_miktari` int(11) NOT NULL DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `urunler`
--

INSERT INTO `urunler` (`id`, `urun_adi`, `kategori`, `aciklama`, `fiyat`, `gorsel_yolu`, `stok_durumu`, `stok_miktari`) VALUES
(1, 'Cheeseburger', 'Burger', 'Lezzetli burger', 120.00, '', 1, 10),
(2, 'Double Burger', 'Burger', 'Çift köfte', 150.00, '', 1, 10),
(3, 'Patates Kızartması', 'Yan Ürün', 'Çıtır patates', 50.00, '', 1, 2),
(4, 'Cola', 'İçecek', 'Soğuk içecek', 40.00, '', 1, 5),
(5, 'Pizza1', 'Pizza', 'Pizza', 120.00, 'images\\pizza.jpg', 1, 1);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Tablo için indeksler `siparisler`
--
ALTER TABLE `siparisler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `siparis_detay`
--
ALTER TABLE `siparis_detay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siparis_id` (`siparis_id`),
  ADD KEY `urun_id` (`urun_id`);

--
-- Tablo için indeksler `urunler`
--
ALTER TABLE `urunler`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `siparisler`
--
ALTER TABLE `siparisler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `siparis_detay`
--
ALTER TABLE `siparis_detay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Tablo için AUTO_INCREMENT değeri `urunler`
--
ALTER TABLE `urunler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `siparis_detay`
--
ALTER TABLE `siparis_detay`
  ADD CONSTRAINT `siparis_detay_ibfk_1` FOREIGN KEY (`siparis_id`) REFERENCES `siparisler` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `siparis_detay_ibfk_2` FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
