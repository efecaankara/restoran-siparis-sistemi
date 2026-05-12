-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 12 May 2026, 17:05:09
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
-- Tablo için tablo yapısı `kategoriler`
--

CREATE TABLE `kategoriler` (
  `id` int(11) NOT NULL,
  `kategori_adi` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `kategoriler`
--

INSERT INTO `kategoriler` (`id`, `kategori_adi`, `created_at`) VALUES
(1, 'Burger', '2026-05-12 11:21:11'),
(2, 'Yan Ürün', '2026-05-12 11:21:11'),
(3, 'İçecek', '2026-05-12 11:21:11'),
(4, 'Tatlı', '2026-05-12 11:21:11'),
(5, 'Pizza', '2026-05-12 11:21:11'),
(6, 'Icecek', '2026-05-12 11:21:11');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `masalar`
--

CREATE TABLE `masalar` (
  `id` int(11) NOT NULL,
  `masa_adi` varchar(50) NOT NULL,
  `durum` enum('Boş','Dolu') DEFAULT 'Boş',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `masalar`
--

INSERT INTO `masalar` (`id`, `masa_adi`, `durum`, `created_at`) VALUES
(1, 'Masa 01', 'Dolu', '2026-05-12 13:29:45'),
(2, 'Masa 02', 'Boş', '2026-05-12 13:29:48'),
(3, 'Masa 03', 'Boş', '2026-05-12 13:29:51'),
(4, 'Masa 04', 'Boş', '2026-05-12 13:29:54'),
(5, 'Masa 05', 'Boş', '2026-05-12 13:29:57'),
(6, 'Masa 06', 'Boş', '2026-05-12 13:29:59'),
(7, 'Masa 07', 'Boş', '2026-05-12 13:30:02'),
(8, 'Masa 08', 'Boş', '2026-05-12 13:30:05'),
(9, 'Masa 09', 'Boş', '2026-05-12 13:30:13'),
(10, 'Masa 10', 'Boş', '2026-05-12 13:30:18'),
(11, 'Masa 11', 'Boş', '2026-05-12 13:30:22'),
(12, 'Masa 12', 'Boş', '2026-05-12 13:30:27'),
(13, 'Masa 13', 'Boş', '2026-05-12 13:31:04'),
(14, 'Masa 14', 'Boş', '2026-05-12 13:31:07'),
(15, 'Masa 15', 'Boş', '2026-05-12 13:31:11'),
(16, 'Masa 16', 'Boş', '2026-05-12 13:31:15'),
(17, 'Masa 17', 'Boş', '2026-05-12 13:31:20'),
(18, 'Masa 18', 'Boş', '2026-05-12 13:31:24'),
(19, 'Masa 19', 'Boş', '2026-05-12 13:31:27'),
(20, 'Masa 20', 'Boş', '2026-05-12 13:31:32');

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
  `durum` enum('Beklemede','Hazırlanıyor','Yolda','Teslim Edildi','İptal Edildi') DEFAULT 'Beklemede',
  `odeme_yontemi` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `siparisler`
--

INSERT INTO `siparisler` (`id`, `musteri_ad_soyad`, `musteri_tel`, `adres`, `masa_no`, `toplam_tutar`, `siparis_tarihi`, `durum`, `odeme_yontemi`, `user_id`) VALUES
(7, 'Efecan KARA', '05528463734', 'Fatih Mahallesi Ünverdi Caddesi NO:70 Daire:4 Esenyurt\\İstanbul', NULL, 300.00, '2026-05-11 19:49:02', 'Beklemede', NULL, NULL),
(8, 'Efecan KARA', '05528463734', 'Fatih Mahallesi Ünverdi Caddesi NO:70 Daire:4 Esenyurt\\İstanbul', NULL, 60.00, '2026-05-11 19:55:07', 'Beklemede', NULL, NULL),
(9, 'efe', 'fadf', 'adfadf', NULL, 200.00, '2026-05-11 19:57:53', 'Beklemede', NULL, NULL),
(10, 'debneme2', '2', '2', NULL, 260.00, '2026-05-11 20:29:56', 'Beklemede', 'Kapıda Ödeme', NULL),
(11, 'Efecan', '05528463734', 'Fatih Mahallesi Ünverdi Caddesi NO:70 Daire:4 Esenyurt\\İstanbul', NULL, 400.00, '2026-05-11 20:36:09', '', 'Kapıda Ödeme', 1),
(12, 'Efecan', '05528463734', '.', NULL, 200.00, '2026-05-11 20:48:25', '', 'Kart ile Ödeme', 1),
(13, 'Efecan', '05528463734', 'efe', NULL, 200.00, '2026-05-12 07:08:25', '', 'Kapıda Ödeme', 1),
(14, 'Efecan', '123', 'trafdasda', NULL, 589.99, '2026-05-12 07:10:32', 'Beklemede', 'Kapıda Ödeme', 2),
(15, 'Denemegırıssız', 'deneme', 'deneme gıreıssız', NULL, 300.00, '2026-05-12 07:12:33', 'İptal Edildi', 'Kapıda Ödeme', NULL),
(17, 'telefon', 'telefon', 'telefon', NULL, 120.00, '2026-05-12 09:37:49', 'Teslim Edildi', 'Kart ile Ödeme', NULL),
(18, 'deneme1', 'deneme', 'telefon3', NULL, 60.00, '2026-05-12 09:38:16', 'Yolda', 'Kapıda Ödeme', 3),
(19, 'deneme1', 'deneme', 'debe2', NULL, 200.00, '2026-05-12 12:15:04', 'Hazırlanıyor', 'Kapıda Ödeme', 3),
(20, 'deneme1', 'deneme', 'dfedfe', NULL, 400.00, '2026-05-12 12:15:48', 'Beklemede', 'Kapıda Ödeme', 3),
(21, 'deneme1', 'deneme', 'deneme', NULL, 100.00, '2026-05-12 12:54:03', 'İptal Edildi', 'Kapıda Ödeme', 3),
(22, 'deneme1', 'deneme', '', 'Masa 01', 200.00, '2026-05-12 13:56:04', 'Hazırlanıyor', 'Kapıda Ödeme', 3);

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
(21, 7, 10, 5, 60.00),
(22, 8, 10, 1, 60.00),
(23, 9, 8, 1, 200.00),
(36, 10, 8, 1, 200.00),
(37, 10, 6, 1, 60.00),
(38, 11, 9, 1, 200.00),
(39, 11, 8, 1, 200.00),
(40, 12, 9, 1, 200.00),
(41, 13, 8, 1, 200.00),
(42, 14, 9, 2, 200.00),
(43, 14, 5, 1, 149.99),
(44, 14, 4, 1, 40.00),
(45, 15, 9, 1, 200.00),
(46, 15, 6, 1, 60.00),
(47, 15, 4, 1, 40.00),
(49, 17, 1, 1, 120.00),
(50, 18, 10, 1, 60.00),
(51, 19, 9, 1, 200.00),
(52, 20, 9, 2, 200.00),
(53, 21, 12, 1, 40.00),
(54, 21, 10, 1, 60.00),
(55, 22, 8, 1, 200.00);

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
(1, 'Cheeseburger', 'Burger', 'Lezzetli burger', 120.00, 'images\\Burger\\cheeseburger.png', 1, 70),
(2, 'Double Burger', 'Burger', 'Çift köfte', 150.00, 'images\\Burger\\double-burger.png', 1, 60),
(3, 'Patates Kızartması', 'Yan Ürün', 'Çıtır patates', 50.00, 'images\\Yan Urun\\patates.png', 0, 1000),
(4, 'Cola', 'İçecek', 'Soğuk içecek', 40.00, 'images\\Icecek\\cola.png', 1, 1),
(5, 'Chicken Burger', 'Burger', 'Tavuk Burger', 149.99, 'images\\Burger\\chicken-burger.png', 1, 100),
(6, 'Tiramisu', 'Tatlı', 'Tatlı', 60.00, 'images\\Tatli\\tiramisu.png', 1, 56),
(7, 'Fanta', 'İçecek', 'Kola', 50.00, 'images\\Icecek\\fanta.png', 1, 75),
(8, 'Pizza Margherita', 'Pizza', 'Margarita pizza, domates, mozarella, fesleğen, zeytinyağı ve tuzla yapılan Napoli pizzasıdır.', 200.00, 'images\\Pizza\\margherita-pizza.png', 1, 99),
(9, 'Pizza Pepperoni', 'Pizza', 'Pepperoni karakteristik olarak yumuşak, hafif isli ve parlak kırmızı renktedir. İnce dilimlenmiş pepperoni, Amerikan pizzalarında kullanılan popüler bir malzemedir.', 200.00, 'images\\Pizza\\pepperoni-pizza.png', 1, 97),
(10, 'Sufle', 'Tatlı', 'çıkolatalı tatlı', 60.00, 'images\\Tatli\\sufle.png', 1, 59),
(12, 'Ayran', 'Icecek', '', 40.00, '', 1, 49);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `ad_soyad` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `durum` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `ad_soyad`, `email`, `telefon`, `password`, `created_at`, `durum`) VALUES
(1, 'Efecan', 'efecankara@gmail.com', '05528463734', '$2y$10$2o0PyGQgtdS/FAt2ZASohu6OefeVB4H2PVDs3LHzMFTYiiMiaFtQO', '2026-05-11 20:35:11', 1),
(2, 'Efecan', '123@gmail.com', '123', '$2y$10$9R741Xkht5U82OcWObqU9es7D/x5SC8aaZOz0JOFsStdXQV9M8urG', '2026-05-12 07:10:05', 1),
(3, 'deneme1', 'deneme@fgmail.com', 'deneme', '$2y$10$mrYnvMrw9.vFqS2jFnvoEOv79h7iXuNmf/wrNHK8WqV1VFVBG5iha', '2026-05-12 09:28:33', 1);

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
-- Tablo için indeksler `kategoriler`
--
ALTER TABLE `kategoriler`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kategori_adi` (`kategori_adi`);

--
-- Tablo için indeksler `masalar`
--
ALTER TABLE `masalar`
  ADD PRIMARY KEY (`id`);

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
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `kategoriler`
--
ALTER TABLE `kategoriler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `masalar`
--
ALTER TABLE `masalar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Tablo için AUTO_INCREMENT değeri `siparisler`
--
ALTER TABLE `siparisler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Tablo için AUTO_INCREMENT değeri `siparis_detay`
--
ALTER TABLE `siparis_detay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Tablo için AUTO_INCREMENT değeri `urunler`
--
ALTER TABLE `urunler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
